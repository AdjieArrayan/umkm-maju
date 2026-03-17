<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockInController extends Controller
{

    public function index(Request $request)
    {
        $query = StockIn::with('item')->latest();

        if ($request->filter) {
            switch ($request->filter) {
                case 'today':
                    $query->whereDate('date', Carbon::today());
                    break;

                case '7days':
                    $query->whereDate('date', '>=', Carbon::now()->subDays(7));
                    break;

                case '1month':
                    $query->whereDate('date', '>=', Carbon::now()->subMonth());
                    break;

                case '1year':
                    $query->whereDate('date', '>=', Carbon::now()->subYear());
                    break;
            }
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('item', function ($itemQuery) use ($request) {
                    $itemQuery->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $stockIns = $query->latest()->paginate(10)->withQueryString();

        return view('page.stock.stock-ins.list-stock-ins', compact('stockIns'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();

        return view('page.stock.stock-ins.add-stock-ins', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            $stockIn = StockIn::create([
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'date' => $request->date,
                'description' => $request->description,
            ]);

            $item = Item::findOrFail($request->item_id);
            $item->increment('stock', $request->quantity);
        });

        return redirect()
            ->route('stock-ins.index')
            ->with('success', 'Stock berhasil ditambahkan.');
    }

    public function edit(StockIn $stockIn)
    {
        $items = Item::orderBy('name')->get();

        return view('page.stock.stock-ins.edit-stock-ins', compact('stockIn', 'items'));
    }

    public function update(Request $request, StockIn $stockIn)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $stockIn) {

            $oldItem = Item::findOrFail($stockIn->item_id);

            $oldItem->decrement('stock', $stockIn->quantity);

            $stockIn->update([
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'date' => $request->date,
                'description' => $request->description,
            ]);

            $newItem = Item::findOrFail($request->item_id);
            $newItem->increment('stock', $request->quantity);
        });

        return redirect()
            ->route('stock-ins.index')
            ->with('success', 'Stock berhasil diperbarui.');
    }

    public function destroy(StockIn $stockIn)
    {
        DB::transaction(function () use ($stockIn) {

            $item = Item::findOrFail($stockIn->item_id);

            $item->decrement('stock', $stockIn->quantity);

            $stockIn->delete();
        });

        return redirect()
            ->route('stock-ins.index')
            ->with('success', 'Stock berhasil dihapus.');
    }
}
