@extends('layouts.app')

@section('content')

<form action="{{ route('stock-ins.store') }}" method="POST" class="space-y-5 max-w-xl">
    @csrf

    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Tambah Stock In
    </h3>

    {{-- Tanggal --}}
    <div>
        <label class="block mb-1 text-sm font-medium">Tanggal</label>

        <input type="date"
               name="date"
               value="{{ old('date', now()->format('Y-m-d')) }}"
               class="w-full h-11 rounded-lg border px-4 text-sm">

        @error('date')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Item --}}
    <div>
        <label class="block mb-1 text-sm font-medium">Item</label>

        <select name="item_id"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            <option value="">-- Pilih Item --</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}"
                    {{ old('item_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>

        @error('item_id')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Quantity --}}
    <div>
        <label class="block mb-1 text-sm font-medium">Jumlah</label>

        <input type="number"
               name="quantity"
               value="{{ old('quantity') }}"
               min="1"
               placeholder="Masukkan jumlah"
               class="w-full h-11 rounded-lg border px-4 text-sm">

        @error('quantity')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Deskripsi --}}
    <div>
        <label class="block mb-1 text-sm font-medium">Deskripsi (Opsional)</label>

        <textarea name="description"
                  rows="3"
                  placeholder="Contoh: Restock dari supplier A"
                  class="w-full rounded-lg border px-4 py-2 text-sm">{{ old('description') }}</textarea>

        @error('description')
            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit --}}
    <div class="pt-4">
        <button type="submit"
            class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
            Simpan Stock In
        </button>
    </div>

</form>

@endsection
