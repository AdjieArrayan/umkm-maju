<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Imports\ItemsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsTemplateExport;

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
        $request->validate([
            'items.*.name' => 'required',
            'items.*.category_id' => 'required',
            'items.*.unit' => 'required',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.stock' => 'required|numeric|min:0',
            'items.*.minimum_stock' => 'required|numeric|min:0',
            'items.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'items.*.description' => 'nullable|string'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        if (!$request->items) {

            Item::create($request->all());

        } else {

            foreach ($request->items as $index => $item) {

                $imagePath = null;

                if ($request->hasFile("items.$index.image")) {
                    $imagePath = $request->file("items.$index.image")
                        ->store('items', 'public');
                }

                Item::create([
                    'name' => $item['name'],
                    'category_id' => $item['category_id'],
                    'unit' => $item['unit'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'minimum_stock' => $item['minimum_stock'],
                    'description' => $item['description'] ?? null,
                    'image' => $imagePath
                ]);
            }

        }

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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ItemsImport, $request->file('file'));

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil diimport.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new ItemsTemplateExport, 'template-import-items.xlsx');
    }
}
