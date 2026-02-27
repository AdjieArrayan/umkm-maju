<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockIn;
use App\Models\Item;

class StockInSeeder extends Seeder
{
    public function run(): void
    {
        $items = Item::all();

        foreach ($items as $item) {
            StockIn::create([
                'item_id' => $item->id,
                'quantity' => rand(10, 30),
                'date' => now()->subDays(rand(5, 20)),
                'description' => 'Stok masuk awal',
            ]);
        }
    }
}
