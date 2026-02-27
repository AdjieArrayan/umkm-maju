<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $sembako = Category::where('name', 'Sembako')->first();
        $minuman = Category::where('name', 'Minuman')->first();

        Item::insert([
            [
                'name' => 'Gula Pasir',
                'category_id' => $sembako->id,
                'unit' => 'Kg',
                'stock' => 20,
                'minimum_stock' => 5,
                'price' => 14000,
                'image' => null,
                'description' => 'Gula pasir kemasan 1 Kg',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mie Instan',
                'category_id' => $sembako->id,
                'unit' => 'Pcs',
                'stock' => 50,
                'minimum_stock' => 10,
                'price' => 3000,
                'image' => null,
                'description' => 'Mie instan berbagai rasa',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Air Mineral 600ml',
                'category_id' => $minuman->id,
                'unit' => 'Pcs',
                'stock' => 30,
                'minimum_stock' => 10,
                'price' => 5000,
                'image' => null,
                'description' => 'Air mineral botol 600ml',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
