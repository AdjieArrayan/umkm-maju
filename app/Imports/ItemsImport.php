<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\StockIn;
use Carbon\Carbon;

class ItemsImport implements ToModel, WithHeadingRow
{

    public function headingRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {

        if(!isset($row['name']) || $row['name'] == null){
            return null;
        }

        $category = Category::where('name', $row['category'])->first();

        $item = Item::create([
            'name' => $row['name'],
            'category_id' => $category ? $category->id : null,
            'unit' => $row['unit'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'minimum_stock' => $row['minimum_stock'],
            'description' => $row['description'] ?? null
        ]);

        if($row['stock'] > 0){

            StockIn::create([
                'item_id' => $item->id,
                'quantity' => $row['stock'],
                'date' => Carbon::now(),
                'description' => 'Import stok awal'
            ]);

        }

        return $item;
    }
}