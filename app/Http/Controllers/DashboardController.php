<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        /* =========================================================
         | 1. METRIK UTAMA
         ========================================================= */

        $totalItems = Item::count();

        $totalStock = Item::sum('stock');

        $totalStockIn  = StockIn::sum('quantity');
        $totalStockOut = StockOut::sum('quantity');

        /* =========================================================
         | 2. GRAFIK STOK MASUK & KELUAR (PER BULAN)
         ========================================================= */

        $year = $request->year ?? now()->year;

        $months = range(1, 12);

        $chartLabels = collect($months)->map(function ($month) {
            return Carbon::create()->month($month)->format('M');
        })->toArray();

        $stockInData = StockIn::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(quantity) as total')
            )
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('total', 'month');

        $stockOutData = StockOut::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(quantity) as total')
            )
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('total', 'month');

        $stockInChart = [];
        $stockOutChart = [];

        foreach ($months as $month) {
            $stockInChart[]  = $stockInData[$month]  ?? 0;
            $stockOutChart[] = $stockOutData[$month] ?? 0;
        }

        /* =========================================================
        | 3. PERINGATAN STOK (BERBASIS FORECASTING)
        ========================================================= */

        $allForecastItems = Item::all()->map(function ($item) {

            $data = StockOut::where('item_id', $item->id)
                ->orderBy('date', 'asc')
                ->pluck('quantity')
                ->toArray();

            if (count($data) < 3) return null;

            $forecast = array_sum(array_slice($data, -3)) / 3;

            if ($forecast <= 0) return null;

            $daysLeft = $item->stock / $forecast;

            return [
                'name' => $item->name,
                'stock' => $item->stock,
                'forecast' => $forecast,
                'days_left' => $daysLeft,
            ];
        })->filter();


        // ✅ COUNT (untuk product metrics)
        $lowStockCount = $allForecastItems
            ->filter(fn($item) => $item['days_left'] <= 5)
            ->count();

        $outOfStockCount = $allForecastItems
            ->filter(fn($item) => $item['days_left'] <= 0)
            ->count();


        // ✅ LIST (untuk stock warning)
        $lowStockItems = $allForecastItems
            ->filter(fn($item) => $item['days_left'] <= 5)
            ->sortBy('days_left')
            ->take(5)
            ->map(function ($item) {
                return [
                    'name' => $item['name'],
                    'stock' => $item['stock'],
                    'days_left' => round($item['days_left'], 1),
                    'recommended' => ceil($item['forecast'] * 7)
                ];
            })
            ->values();

        /* =========================================================
         | 4. ALUR BARANG (STOCK FLOW)
         ========================================================= */

        $stockInFlow = StockIn::with(['item.category'])
            ->latest('date')
            ->take(5)
            ->get()
            ->map(function ($row) {
                return [
                    'type'     => 'Masuk',
                    'item'     => $row->item->name,
                    'category' => $row->item->category->name ?? '-',
                    'quantity' => $row->quantity,
                    'date'     => Carbon::parse($row->date)->format('d/m/Y'),
                    'status'   => 'Masuk',
                ];
            });

        $stockOutFlow = StockOut::with(['item.category'])
            ->latest('date')
            ->take(5)
            ->get()
            ->map(function ($row) {
                return [
                    'type'     => 'Keluar',
                    'item'     => $row->item->name,
                    'category' => $row->item->category->name ?? '-',
                    'quantity' => $row->quantity,
                    'date'     => Carbon::parse($row->date)->format('d/m/Y'),
                    'status'   => 'Keluar',
                ];
            });

        $productFlows = $stockInFlow
            ->merge($stockOutFlow)
            ->sortByDesc('date')
            ->take(10)
            ->values();


        /* =========================================================
         | 5. KIRIM KE VIEW
         ========================================================= */

        return view('page.dashboard.main-dashboard', [
            'title'            => 'Dashboard',
            'totalItems'       => $totalItems,
            'totalStock'       => $totalStock,
            'totalStockIn'     => $totalStockIn,
            'totalStockOut'    => $totalStockOut,
            'stockInChart'     => $stockInChart,
            'stockOutChart'    => $stockOutChart,
            'lowStockItems'    => $lowStockItems,
            'lowStockCount'    => $lowStockCount,
            'outOfStockCount'  => $outOfStockCount,
            'productFlows'     => $productFlows,
            'chartLabels'      => $chartLabels,
        ]);
    }
}