<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsTemplateExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'name',
            'category',
            'unit',
            'price',
            'stock',
            'minimum_stock',
            'description'
        ];
    }

    public function array(): array
    {
        return [
            [
                'Contoh Item',
                'Nama Kategori',
                'pcs',
                5000,
                10,
                3,
                'contoh deskripsi'
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /*
                |--------------------------------------------------------------------------
                | STYLE CONFIG
                |--------------------------------------------------------------------------
                */

                $header = [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'color' => ['rgb' => '2F75B5']
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center'
                    ]
                ];

                $border = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin'
                        ]
                    ]
                ];

                /*
                |--------------------------------------------------------------------------
                | TITLE
                |--------------------------------------------------------------------------
                */

                $sheet->insertNewRowBefore(1,2);

                $sheet->setCellValue('A1','TEMPLATE IMPORT DATA BARANG');

                $sheet->mergeCells('A1:G1');

                $sheet->getStyle('A1')->applyFromArray([
                    'font'=>[
                        'bold'=>true,
                        'size'=>16
                    ],
                    'alignment'=>[
                        'horizontal'=>'center'
                    ]
                ]);

                /*
                |--------------------------------------------------------------------------
                | HEADER TABLE ITEMS
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A3:G3')->applyFromArray($header);
                $sheet->getStyle('A3:G4')->applyFromArray($border);

                /*
                |--------------------------------------------------------------------------
                | SAMPLE ROW COLOR
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A4:G4')->applyFromArray([
                    'fill'=>[
                        'fillType'=>'solid',
                        'color'=>['rgb'=>'E7F3FF']
                    ]
                ]);

                /*
                |--------------------------------------------------------------------------
                | DAFTAR KATEGORI
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('I1','DAFTAR KATEGORI');
                $sheet->mergeCells('I1:K1');

                $sheet->getStyle('I1')->applyFromArray([
                    'font'=>[
                        'bold'=>true,
                        'size'=>16
                    ],
                    'alignment'=>[
                        'horizontal'=>'center'
                    ]
                ]);

                $sheet->setCellValue('I2','ID');
                $sheet->setCellValue('J2','CATEGORY NAME');
                $sheet->mergeCells('J2:K2');

                $sheet->getStyle('I2:K2')->applyFromArray($header);

                $categories = Category::all();

                $row = 3;

                foreach ($categories as $category) {

                    $sheet->setCellValue('I'.$row, $category->id);
                    $sheet->setCellValue('J'.$row, $category->name);

                    $sheet->mergeCells("J{$row}:K{$row}");

                    $row++;
                }

                $lastRow = $row - 1;

                $sheet->getStyle("I2:K{$lastRow}")
                    ->applyFromArray($border);

                /*
                |--------------------------------------------------------------------------
                | INFO PETUNJUK
                |--------------------------------------------------------------------------
                */

                $sheet->setCellValue('M2','Petunjuk:');
                $sheet->setCellValue('M3','1. Isi data mulai dari baris ke 4.');
                $sheet->setCellValue('M4','2. Kolom category harus sesuai dengan nama kategori.');
                $sheet->setCellValue('M5','3. Jangan menghapus header kolom.');

                $sheet->getStyle('M2')->applyFromArray([
                    'font'=>['bold'=>true]
                ]);

            }

        ];
    }
}