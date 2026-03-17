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

        // Gunakan stok aktual dari tabel items
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
         | 3. PERINGATAN STOK & REKOMENDASI RESTOK
         ========================================================= */

        $lowStockLimit = 10;

        $lowStockItems = Item::where('stock', '>', 0)
            ->where('stock', '<=', $lowStockLimit)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get()
            ->map(function ($item) use ($lowStockLimit) {

                $avgDailyOut = StockOut::where('item_id', $item->id)
                    ->whereBetween('date', [
                        Carbon::now()->subDays(7),
                        Carbon::now()
                    ])
                    ->avg('quantity') ?? 0;

                $recommendedStock = max(
                    ($lowStockLimit + ($avgDailyOut * 7)) - $item->stock,
                    0
                );

                return [
                    'name'        => $item->name,
                    'stock'       => $item->stock,
                    'recommended' => (int) round($recommendedStock),
                ];
            });

        $lowStockCount = Item::where('stock', '>', 0)
            ->where('stock', '<=', $lowStockLimit)
            ->count();

        $outOfStockCount = Item::where('stock', 0)->count();

        $stockHealth = $totalItems > 0
            ? round((($totalItems - $outOfStockCount) / $totalItems) * 100, 2)
            : 100;


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
            'lowStockCount'    => $lowStockCount,
            'outOfStockCount'  => $outOfStockCount,
            'stockHealth'      => $stockHealth,
            'lowStockItems'    => $lowStockItems,
            'productFlows'     => $productFlows,
            'chartLabels' => $chartLabels,
        ]);
    }
}
