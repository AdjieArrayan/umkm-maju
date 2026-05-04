<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\StockOut;
use Carbon\Carbon;

class StockOutSeeder extends Seeder
{
    public function run()
    {
        $items = Item::all();

        foreach ($items as $item) {

            // generate 30 hari ke belakang
            for ($i = 30; $i >= 1; $i--) {

                StockOut::create([
                    'item_id' => $item->id,
                    'quantity' => rand(5, 20), // random penjualan
                    'date' => Carbon::now()->subDays($i),
                    'description' => 'Dummy data',
                    'is_over_limit' => false
                ]);
            }
        }
    }
}
