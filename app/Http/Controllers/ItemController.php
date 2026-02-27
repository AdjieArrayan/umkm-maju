<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $items = Item::when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('page.items.list-items', compact('items'));
    }

    public function create(): View
    {
        $categories = Category::all();

        return view('page.items.add-items', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'unit' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($validated);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        return view('page.items.edit-items', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {

            // Hapus gambar lama
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $imagePath = $request->file('image')->store('items', 'public');
            $item->image = $imagePath;
        }

        $item->update([
            'name' => $request->name,
            'unit' => $request->unit,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $item->image,
        ]);

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        $item->delete(); // soft delete saja

        return redirect()
            ->route('items.index')
            ->with('success', 'Item berhasil dihapus.');
    }
}
