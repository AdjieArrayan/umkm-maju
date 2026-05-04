@extends('layouts.app')

@section('content')

<div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">

    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Tambah Stock Keluar
    </h3>

    <div class="pt-2">
        <button type="button"
            onclick="addItemForm()"
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"/>
            </svg>

            Tambah Barang
        </button>
    </div>

</div>

<form action="{{ route('stock-outs.store') }}" method="POST" class="space-y-5">
    @csrf

    <div>
        <label class="block mb-1 text-sm font-medium dark:text-white/90">Tanggal</label>

        <input type="date"
            name="date"
            value="{{ old('date', now()->format('Y-m-d')) }}"
            class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">

        @error('date')
            <div class="mb-4 rounded-lg bg-yellow-100 p-3 text-sm text-yellow-700">
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            </div>
        @enderror
    </div>

    <div id="itemsContainer">
        <div class="item-form border rounded-xl p-5 mb-6">

    <div>
        <label class="block mb-1 text-sm font-medium dark:text-white/90">Item</label>

        <select name="items[0][item_id]"
            class="w-full h-11 rounded-lg border px-4 text-sm
                   bg-white text-gray-900
                   dark:bg-gray-800 dark:text-white dark:border-gray-700">
            <option value="">-- Pilih Item --</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>

        @error('item_id')
            <div class="mb-4 rounded-lg bg-yellow-100 p-3 text-sm text-yellow-700">
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            </div>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium dark:text-white/90">Jumlah</label>

        <input type="number"
            name="items[0][quantity]"
            min="1"
            class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">

             @error('items.0.quantity')
                <div class="mb-4 rounded-lg bg-yellow-100 p-3 text-sm text-yellow-700">
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                </div>
            @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium dark:text-white/90">Deskripsi</label>

        <textarea name="items[0][description]"
            class="w-full rounded-lg border px-4 py-2 text-sm dark:text-white/90"></textarea>

        @error('description')
            <div class="mb-4 rounded-lg bg-yellow-100 p-3 text-sm text-yellow-700">
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            </div>
        @enderror
    </div>

</div>
</div>

        <div class="pt-4">
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
                Simpan Stock Keluar
            </button>
        </div>

    </form>

    <script>
        let index = 1;

        function addItemForm() {
            const container = document.getElementById('itemsContainer');

            const html = `
            <div class="item-form border rounded-xl p-5 mb-6">

                <div>
                    <label class="block mb-1 text-sm font-medium dark:text-white/90">Item</label>

                    <select name="items[${index}][item_id]"
                        class="w-full h-11 rounded-lg border px-4 text-sm
                               bg-white text-gray-900
                               dark:bg-gray-800 dark:text-white dark:border-gray-700">
                        <option value="">-- Pilih Item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium dark:text-white/90">Jumlah</label>

                    <input type="number"
                        name="items[${index}][quantity]"
                        min="1"
                        class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium dark:text-white/90">Deskripsi</label>

                    <textarea name="items[${index}][description]"
                        class="w-full rounded-lg border px-4 py-2 text-sm dark:text-white/90"></textarea>
                </div>

                <button type="button" onclick="this.parentElement.remove()"
                    class="mt-3 text-red-500 text-sm">
                    Hapus
                </button>

            </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            index++;
        }
        </script>

@endsection
