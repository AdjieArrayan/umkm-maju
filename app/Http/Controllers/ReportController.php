<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('page.report.reports');
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required'
        ],[
            'start_date.required' => 'Tanggal mulai dan akhir harus diisi.',
            'end_date.required' => 'Tanggal mulai dan akhir harus diisi.',
            'type.required' => 'Jenis laporan harus dipilih.'
        ]);

        $start = $request->start_date;
        $end = $request->end_date;

        $type = $request->type ?? 'semua';

        $filename = "laporan-stok_{$start}_{$end}_{$type}.xlsx";

        return Excel::download(new ReportExport($request), $filename);
    }

        public function exportPDF(Request $request)
    {

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required'
        ],[
            'start_date.required' => 'Tanggal mulai dan akhir harus diisi.',
            'end_date.required' => 'Tanggal mulai dan akhir harus diisi.',
            'type.required' => 'Jenis laporan harus dipilih.'
        ]);

        $start = $request->start_date;
        $end = $request->end_date;

        $type = $request->type ?? 'semua';

        $filename = "laporan-stok_{$start}_{$end}_{$type}.pdf";

        $stockIns = StockIn::with('item')
            ->whereBetween('date', [$start, $end])
            ->get();

        $stockOuts = StockOut::with('item')
            ->whereBetween('date', [$start, $end])
            ->get();

        $transactions = collect();

        foreach ($stockIns as $row) {
            $transactions->push([
                'date'=>$row->date,
                'item'=>$row->item->name,
                'type'=>'Masuk',
                'qty'=>$row->quantity,
                'desc'=>$row->description
            ]);
        }

        foreach ($stockOuts as $row) {
            $transactions->push([
                'date'=>$row->date,
                'item'=>$row->item->name,
                'type'=>'Keluar',
                'qty'=>$row->quantity,
                'desc'=>$row->description
            ]);
        }

        $transactions = $transactions->sortBy('date');

        $items = Item::all();

        $totalMasuk = $stockIns->sum('quantity');
        $totalKeluar = $stockOuts->sum('quantity');

        $pdf = Pdf::loadView('page.report.pdf-reports', compact(
            'transactions',
            'items',
            'start',
            'end',
            'totalMasuk',
            'totalKeluar'
        ));

        return $pdf->download($filename);
    }
}