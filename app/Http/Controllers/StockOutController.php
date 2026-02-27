<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\Item;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOut::with('item')->latest();

        // SEARCH
        if ($request->search) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // FILTER
        if ($request->filter) {
            match ($request->filter) {
                'today'   => $query->whereDate('date', Carbon::today()),
                '7days'   => $query->whereDate('date', '>=', Carbon::now()->subDays(7)),
                '1month'  => $query->whereDate('date', '>=', Carbon::now()->subMonth()),
                '1year'   => $query->whereDate('date', '>=', Carbon::now()->subYear()),
            };
        }

        $stockOuts = $query->paginate(10)->withQueryString();

        return view('page.stock.stock-outs.list-stock-outs', compact('stockOuts'));
    }

    public function create()
    {
        $items = Item::all();
        return view('page.stock.stock-outs.add-stock-outs', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $item = Item::findOrFail($request->item_id);

        $isOverLimit = false;

        if ($request->quantity > $item->stock) {
            $isOverLimit = true;
        }

        // Kurangi stok
        $item->stock -= $request->quantity;
        $item->save();

        StockOut::create([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'description' => $request->description,
            'is_over_limit' => $isOverLimit
        ]);

        return redirect()->route('stock-outs.index')
            ->with('success', 'Stock keluar berhasil ditambahkan');
    }

    public function edit(StockOut $stockOut)
    {
        $items = Item::all();
        return view('page.stock.stock-outs.edit-stock-outs', compact('stockOut', 'items'));
    }

    public function update(Request $request, StockOut $stockOut)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Kembalikan stok lama dulu
        $item->stock += $stockOut->quantity;

        $isOverLimit = false;

        if ($request->quantity > $item->stock) {
            $isOverLimit = true;
        }

        // Kurangi stok baru
        $item->stock -= $request->quantity;
        $item->save();

        $stockOut->update([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'description' => $request->description,
            'is_over_limit' => $isOverLimit
        ]);

        return redirect()->route('stock-outs.index')
            ->with('success', 'Stock keluar berhasil diupdate');
    }

    public function destroy(StockOut $stockOut)
    {
        $item = $stockOut->item;

        // Kembalikan stok
        $item->stock += $stockOut->quantity;
        $item->save();

        $stockOut->delete();

        return redirect()->route('stock-outs.index')
            ->with('success', 'Stock keluar berhasil dihapus');
    }
}
