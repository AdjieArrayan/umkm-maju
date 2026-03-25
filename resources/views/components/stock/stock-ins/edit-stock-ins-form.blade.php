@extends('layouts.app')

@section('content')

<div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">

    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Edit Stock In
    </h3>

</div>

<form action="{{ route('stock-ins.update', $stockIn) }}" method="POST" class="space-y-5">
    @csrf
    @method('PUT')

    <div id="itemsContainer">
        <div class="item-form border rounded-xl p-5 mb-6">

    <div>
        <label class="block mb-1 text-sm font-medium dark:text-white/90">Tanggal</label>

        <input type="date"
               name="date"
               value="{{ old('date', \Carbon\Carbon::parse($stockIn->date)->format('Y-m-d')) }}"
               class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">

        @error('date')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium dark:text-white/90">Item</label>

        <select name="item_id"
        class="w-full h-11 rounded-lg border px-4 text-sm
               bg-white text-gray-900
               dark:bg-gray-800 dark:text-white dark:border-gray-700">
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
        <label class="block mb-1 text-sm font-medium dark:text-white/90">Jumlah</label>

        <input type="number"
               name="quantity"
               min="1"
               value="{{ old('quantity', $stockIn->quantity) }}"
               class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">

        @error('quantity')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium dark:text-white/90">Deskripsi (Opsional)</label>

        <textarea name="description"
                  rows="3"
                  class="w-full rounded-lg border px-4 py-2 text-sm dark:text-white/90">{{ old('description', $stockIn->description) }}</textarea>

        @error('description')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>
</div>

    <div class="pt-4 flex gap-3">
        <button type="submit"
            class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
            Update Stock
        </button>

        <a href="{{ route('stock-ins.index') }}"
           class="px-6 py-2 text-sm font-medium border rounded-lg hover:bg-gray-50 dark:text-white/90">
            Batal
        </a>
    </div>

</form>

@endsection
