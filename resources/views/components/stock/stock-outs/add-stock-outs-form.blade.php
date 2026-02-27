@extends('layouts.app')

@section('content')

<form action="{{ route('stock-outs.store') }}" method="POST" class="space-y-5 max-w-xl">
    @csrf

    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Tambah Stock Keluar
    </h3>

    <div>
        <label class="block mb-1 text-sm font-medium">Item</label>

        <select name="item_id" class="w-full border rounded-lg p-2">
            @foreach($items as $item)
                <option value="{{ $item->id }}">
                    {{ $item->name }} (Stock: {{ $item->stock }})
                </option>
            @endforeach
        </select>

    <div>
        <label class="block mb-1 text-sm font-medium">Jumlah</label>
        <input type="number" name="quantity" class="w-full border rounded-lg p-2">
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Tanggal</label>
        <input type="date" name="date" class="w-full border rounded-lg p-2">
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Deskripsi</label>
        <textarea name="description" class="w-full border rounded-lg p-2"></textarea>
    </div>

        <div class="pt-4">
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
                Simpan Stock Keluar
            </button>
        </div>

    </form>


@endsection
