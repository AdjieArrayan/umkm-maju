<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockOut;
use App\Models\Item;

class StockOutSeeder extends Seeder
{
    public function run(): void
    {
        $items = Item::all();

        foreach ($items as $item) {
            $qty = rand(5, 25);

            StockOut::create([
                'item_id' => $item->id,
                'quantity' => $qty,
                'date' => now()->subDays(rand(1, 10)),
                'description' => 'Penjualan barang',
                'is_over_limit' => $qty > $item->stock,
            ]);
        }
    }
}
