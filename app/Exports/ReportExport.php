<?php

namespace App\Exports;

use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromArray, WithStyles, ShouldAutoSize, WithEvents
{

    protected $start;
    protected $end;
    protected $summaryHeader;
    protected $summaryEnd;
    protected $trxHeader;
    protected $trxEnd;
    protected $rekapHeader;
    protected $rekapEnd;
    protected $warningHeader;
    protected $warningEnd;

    public function __construct($request)
    {
        $this->start = $request->start_date;
        $this->end = $request->end_date;
    }

    public function array(): array
    {

        $rows = [];

        $transactions = collect();

        $stockIns = StockIn::with('item')
            ->whereBetween('date', [$this->start,$this->end])
            ->get();

        $stockOuts = StockOut::with('item')
            ->whereBetween('date', [$this->start,$this->end])
            ->get();

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

        $totalMasuk = $stockIns->sum('quantity');
        $totalKeluar = $stockOuts->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $rows[] = ['LAPORAN STOK BARANG'];
        $rows[] = ['Periode : '.$this->start.' s/d '.$this->end];

        /*
        |--------------------------------------------------------------------------
        | JARAK 2 BARIS
        |--------------------------------------------------------------------------
        */

        $rows[] = [''];
        $rows[] = [''];

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN
        |--------------------------------------------------------------------------
        */

        $rows[] = ['RINGKASAN',''];

        $rows[] = ['Keterangan','Jumlah'];

        $this->summaryHeader = count($rows);

        $rows[] = ['Total Transaksi',$transactions->count()];
        $rows[] = ['Total Stok Masuk',$totalMasuk];
        $rows[] = ['Total Stok Keluar',$totalKeluar];

        $this->summaryEnd = count($rows);

        /*
        |--------------------------------------------------------------------------
        | JARAK 2 BARIS
        |--------------------------------------------------------------------------
        */

        $rows[] = [''];
        $rows[] = [''];

        /*
        |--------------------------------------------------------------------------
        | TABEL TRANSAKSI
        |--------------------------------------------------------------------------
        */

        $rows[] = ['TABEL TRANSAKSI'];

        $rows[] = ['Tanggal','Item','Jenis','Jumlah','Deskripsi'];

        $this->trxHeader = count($rows);

        foreach($transactions as $trx){

            $rows[] = [
                $trx['date'],
                $trx['item'],
                $trx['type'],
                $trx['qty'],
                $trx['desc']
            ];

        }

        $this->trxEnd = count($rows);

        /*
        |--------------------------------------------------------------------------
        | JARAK 2 BARIS
        |--------------------------------------------------------------------------
        */

        $rows[] = [''];
        $rows[] = [''];

        /*
        |--------------------------------------------------------------------------
        | REKAP STOK
        |--------------------------------------------------------------------------
        */

        $rows[] = ['REKAP STOK'];

        $rows[] = ['Item','Total Masuk','Total Keluar','Sisa'];

        $this->rekapHeader = count($rows);

        $items = Item::all();

        foreach($items as $item){

            $totalIn = StockIn::where('item_id',$item->id)->sum('quantity');
            $totalOut = StockOut::where('item_id',$item->id)->sum('quantity');

            $rows[] = [
                $item->name,
                $totalIn,
                $totalOut,
                $totalIn - $totalOut
            ];

        }

        $this->rekapEnd = count($rows);

        /*
        |--------------------------------------------------------------------------
        | JARAK 2 BARIS
        |--------------------------------------------------------------------------
        */

        $rows[] = [''];
        $rows[] = [''];

        /*
        |--------------------------------------------------------------------------
        | PERINGATAN STOK RENDAH
        |--------------------------------------------------------------------------
        */

        $rows[] = ['PERINGATAN STOK RENDAH'];

        $rows[] = ['Item','Sisa'];

        $this->warningHeader = count($rows);

        foreach($items as $item){

            $totalIn = StockIn::where('item_id',$item->id)->sum('quantity');
            $totalOut = StockOut::where('item_id',$item->id)->sum('quantity');

            $sisa = $totalIn - $totalOut;

            if($sisa <= 5){

                $rows[] = [
                    $item->name,
                    $sisa
                ];

            }

        }

        $this->warningEnd = count($rows);

        return $rows;

    }

    public function styles(Worksheet $sheet)
    {

        return [

            1=>[
                'font'=>['bold'=>true,'size'=>18],
                'alignment'=>['horizontal'=>'center']
            ],

            2=>[
                'font'=>['italic'=>true],
                'alignment'=>['horizontal'=>'center']
            ],

        ];

    }

    public function registerEvents(): array
    {

        return [

            AfterSheet::class => function(AfterSheet $event){

                $sheet = $event->sheet->getDelegate();

                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A5:B5');

                $header = [

                    'font'=>[
                        'bold'=>true,
                        'color'=>['rgb'=>'FFFFFF']
                    ],

                    'fill'=>[
                        'fillType'=>'solid',
                        'startColor'=>['rgb'=>'2F75B5']
                    ],

                    'alignment'=>[
                        'horizontal'=>'center'
                    ],

                    'borders'=>[
                        'allBorders'=>[
                            'borderStyle'=>'thin'
                        ]
                    ]

                ];

                $border = [

                    'borders'=>[
                        'allBorders'=>[
                            'borderStyle'=>'thin'
                        ]
                    ]

                ];

                /*
                |--------------------------------------------------------------------------
                | RINGKASAN
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A{$this->summaryHeader}:B{$this->summaryHeader}")
                    ->applyFromArray($header);

                $sheet->getStyle("A{$this->summaryHeader}:B{$this->summaryEnd}")
                    ->applyFromArray($border);

                /*
                |--------------------------------------------------------------------------
                | TRANSAKSI
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A{$this->trxHeader}:E{$this->trxHeader}")
                    ->applyFromArray($header);

                $sheet->getStyle("A{$this->trxHeader}:E{$this->trxEnd}")
                    ->applyFromArray($border);

                /*
                |--------------------------------------------------------------------------
                | REKAP
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A{$this->rekapHeader}:D{$this->rekapHeader}")
                    ->applyFromArray($header);

                $sheet->getStyle("A{$this->rekapHeader}:D{$this->rekapEnd}")
                    ->applyFromArray($border);

                /*
                |--------------------------------------------------------------------------
                | WARNING
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A{$this->warningHeader}:B{$this->warningHeader}")
                    ->applyFromArray($header);

                $sheet->getStyle("A{$this->warningHeader}:B{$this->warningEnd}")
                    ->applyFromArray($border);

                /*
                |--------------------------------------------------------------------------
                | ALIGN
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A:A")->getAlignment()->setHorizontal('center');
                $sheet->getStyle("C:D")->getAlignment()->setHorizontal('center');

            }

        ];

    }

}