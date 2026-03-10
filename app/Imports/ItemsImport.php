<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow
{

    public function headingRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {

        if(empty(trim($row['name'] ?? ''))){
            return null;
        }

        $category = Category::where('name', trim($row['category']))->first();

        return new Item([
            'name' => $row['name'],
            'category_id' => $category ? $category->id : null,
            'unit' => $row['unit'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'minimum_stock' => $row['minimum_stock'],
            'description' => $row['description'] ?? null
        ]);
    }
}