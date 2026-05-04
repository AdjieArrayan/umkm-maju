<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\StockOut;
use App\Services\ForecastService;

class ForecastController extends Controller
{
    protected $forecastService;

    public function __construct(ForecastService $forecastService)
    {
        $this->forecastService = $forecastService;
    }

    /**
     * Forecast untuk 1 item
     */
    public function forecastItem($itemId)
    {
        $data = StockOut::where('item_id', $itemId)
            ->orderBy('date', 'asc')
            ->pluck('quantity')
            ->toArray();

        if (count($data) < 3) {
            return response()->json([
                'message' => 'Data tidak cukup untuk forecasting'
            ]);
        }

        $forecast = $this->forecastService->movingAverage($data, 3);

        $item = Item::find($itemId);

        // Hindari pembagian nol
        $daysLeft = $forecast > 0 ? $item->stock / $forecast : 0;

        // Status
        $status = 'Aman';
        if ($daysLeft <= 2) {
            $status = 'Kritis';
        } elseif ($daysLeft <= 5) {
            $status = 'Perlu Restock';
        }

        // Rekomendasi
        $rekomendasi = 'Tidak perlu restock';
        if ($daysLeft <= 2) {
            $rekomendasi = 'Segera lakukan restock';
        } elseif ($daysLeft <= 5) {
            $rekomendasi = 'Siapkan restock dalam waktu dekat';
        }

        return response()->json([
            'item_name' => $item->name,
            'current_stock' => $item->stock,
            'forecast_per_day' => round($forecast, 2),
            'estimated_days_left' => round($daysLeft, 1),
            'status' => $status,
            'rekomendasi' => $rekomendasi
        ]);
    }

    /**
     * Forecast untuk semua item
     */
    public function forecastAll()
    {
        $items = Item::all();

        $results = [];

        foreach ($items as $item) {
            $data = StockOut::where('item_id', $item->id)
                ->orderBy('date', 'asc')
                ->pluck('quantity')
                ->toArray();

            if (count($data) >= 3) {
                $forecast = $this->forecastService->movingAverage($data, 3);

                // Hindari pembagian nol
                $daysLeft = $forecast > 0 ? $item->stock / $forecast : 0;

                // Status
                $status = 'Aman';
                if ($daysLeft <= 2) {
                    $status = 'Kritis';
                } elseif ($daysLeft <= 5) {
                    $status = 'Perlu Restock';
                }

                // Rekomendasi
                $rekomendasi = 'Tidak perlu restock';
                if ($daysLeft <= 2) {
                    $rekomendasi = 'Segera lakukan restock';
                } elseif ($daysLeft <= 5) {
                    $rekomendasi = 'Siapkan restock dalam waktu dekat';
                }

                $results[] = [
                    'item_name' => $item->name,
                    'current_stock' => $item->stock,
                    'forecast_per_day' => round($forecast, 2),
                    'estimated_days_left' => round($daysLeft, 1),
                    'status' => $status,
                    'rekomendasi' => $rekomendasi
                ];
            }
        }

        return response()->json($results);
    }

    public function analytic(Request $request)
{
    $items = Item::all();
    $selectedItemId = $request->item_id ?? $items->first()->id;
    $item = Item::find($selectedItemId);

    // Ambil data historis
    $data = StockOut::where('item_id', $selectedItemId)
        ->orderBy('date', 'asc')
        ->pluck('quantity')
        ->toArray();

    // Forecast (Moving Average 3)
    $forecast = null;
    if (count($data) >= 3) {
        $forecast = array_sum(array_slice($data, -3)) / 3;
    }

    // Estimasi hari stok habis
    $daysLeft = $forecast > 0 ? $item->stock / $forecast : 0;

    // Hitung MAPE (akurasi)
    $mape = null;
    if (count($data) >= 3) {
        $errors = [];

        for ($i = 3; $i < count($data); $i++) {
            $slice = array_slice($data, $i - 3, 3);
            $pred = array_sum($slice) / 3;
            $actual = $data[$i];

            if ($actual == 0) continue;

            $errors[] = abs($actual - $pred) / $actual;
        }

        if (count($errors) > 0) {
            $mape = (array_sum($errors) / count($errors)) * 100;
        }
    }

    // Reorder Point (ROP)
    $rop = $forecast ? ($forecast * 3) + 10 : null;

    // Forecast 7 hari ke depan
    $future = [];
    if (count($data) >= 3) {
        $temp = $data;

        for ($i = 0; $i < 7; $i++) {
            $next = array_sum(array_slice($temp, -3)) / 3;
            $future[] = round($next, 2);
            $temp[] = $next;
        }
    }

    return view('analytics', compact(
        'items',
        'item',
        'forecast',
        'daysLeft',
        'mape',
        'rop',
        'future'
    ));
}

    public function analytics(Request $request)
    {
        $items = Item::all();

        $selectedItemId = $request->item_id ?? $items->first()->id;

        $item = Item::find($selectedItemId);

        $data = StockOut::where('item_id', $selectedItemId)
            ->orderBy('date', 'asc')
            ->get();

        $labels = $data->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'));
        $values = $data->pluck('quantity');

        // Forecast
        $forecast = null;
        if ($values->count() >= 3) {
            $last3 = $values->slice(-3);
            $forecast = $last3->avg();
        }

        $daysLeft = $forecast > 0 ? $item->stock / $forecast : 0;

        $mape = null;

        if ($values->count() >= 3) {
            $mape = $this->forecastService->calculateMAPE($values->toArray(), 3);
        }

        $valuesArray = $values->toArray();

        // MA 3
        $ma3 = $this->forecastService->movingAverageSeries($valuesArray, 3);
        $mape3 = $this->forecastService->calculateMAPE($valuesArray, 3);

        // MA 5
        $ma5 = $this->forecastService->movingAverageSeries($valuesArray, 5);
        $mape5 = $this->forecastService->calculateMAPE($valuesArray, 5);

        $rop = null;

        if ($forecast > 0) {
            $rop = $this->forecastService->calculateROP($forecast, 3, 10);
        }

        $trend = 'stable';

        if ($values->count() >= 3) {
            $last = $values->slice(-3)->values();

            if ($last[2] > $last[1] && $last[1] > $last[0]) {
                $trend = 'up';
            } elseif ($last[2] < $last[1] && $last[1] < $last[0]) {
                $trend = 'down';
            }
        }

        $insight = $this->forecastService->generateInsight(
            $trend,
            $daysLeft,
            $rop,
            $item->stock,
            $mape
        );

        $future = [];

        if ($values->count() >= 3) {
            $future = $this->forecastService->forecastNextDays(
                $values->toArray(),
                3,
                7
            );
        }

        $futureLabels = collect(range(1, 7))->map(function ($i) {
            return 'H+' . $i;
        });

        return view('page.analytics.analytics-page', [
            'items' => $items,
            'selectedItem' => $item,
            'labels' => $labels,
            'values' => $values,
            'forecast' => round($forecast, 2),
            'daysLeft' => round($daysLeft, 1),
            'mape' => $mape ? round($mape, 2) : null,
            'ma3' => $ma3,
            'ma5' => $ma5,
            'mape3' => $mape3 ? round($mape3, 2) : null,
            'mape5' => $mape5 ? round($mape5, 2) : null,
            'rop' => $rop ? round($rop) : null,
            'insight' => $insight,
            'future' => $future,
            'futureLabels' => $futureLabels,
        ]);
    }
}

