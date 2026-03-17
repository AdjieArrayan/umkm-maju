@extends('layouts.app')

@section('content')

<form action="{{ route('stock-ins.update', $stockIn) }}" method="POST" class="space-y-5 max-w-xl">
    @csrf
    @method('PUT')

    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Edit Stock In
    </h3>

    <div>
        <label class="block mb-1 text-sm font-medium">Tanggal</label>

        <input type="date"
               name="date"
               value="{{ old('date', \Carbon\Carbon::parse($stockIn->date)->format('Y-m-d')) }}"
               class="w-full h-11 rounded-lg border px-4 text-sm">

        @error('date')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Item</label>

        <select name="item_id"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            <option value="">-- Pilih Item --</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}"
                    {{ old('item_id', $stockIn->item_id) == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>

        @error('item_id')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Jumlah</label>

        <input type="number"
               name="quantity"
               min="1"
               value="{{ old('quantity', $stockIn->quantity) }}"
               class="w-full h-11 rounded-lg border px-4 text-sm">

        @error('quantity')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Deskripsi (Opsional)</label>

        <textarea name="description"
                  rows="3"
                  class="w-full rounded-lg border px-4 py-2 text-sm">{{ old('description', $stockIn->description) }}</textarea>

        @error('description')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-4 flex gap-3">
        <button type="submit"
            class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
            Update Stock
        </button>

        <a href="{{ route('stock-ins.index') }}"
           class="px-6 py-2 text-sm font-medium border rounded-lg hover:bg-gray-50">
            Batal
        </a>
    </div>

</form>

@endsection
