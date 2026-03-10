@extends('layouts.app')

@section('content')

    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Tambah Barang
        </h3>

        <div class="pt-2">
            <button type="button"
                onclick="addItemForm()"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">

                {{-- Icon Plus --}}
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

<form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
    @csrf

    <div id="itemsContainer">

    <div class="item-form border rounded-xl p-5 mb-6">

        {{-- Nama Item --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Nama Item</label>
            <input type="text" name="items[0][name]" value="{{ old('name') }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Kategori</label>
            <select name="items[0][category_id]" class="w-full h-11 rounded-lg border px-4 text-sm">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Unit --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Unit</label>
            <input type="text" name="items[0][unit]" value="{{ old('unit') }}"
                placeholder="Contoh: pcs, box, kg"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('unit') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Harga --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Harga (Rp)</label>
            <input type="number" name="items[0][price]" min="0"
                value="{{ old('price') }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('price') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Stok Awal --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Stok Awal</label>
            <input type="number" name="items[0][stock]" min="0" value="{{ old('stock', 0) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('stock') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Minimum Stock --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Minimum Stok</label>
            <input type="number" name="items[0][minimum_stock]" min="0"
                value="{{ old('minimum_stock', 5) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('minimum_stock') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Gambar --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Gambar Item</label>

            {{-- Preview Box --}}
            <div class="mb-3">
                <img class="imagePreview hidden w-24 h-24 object-cover rounded-lg border">
            </div>

            <input type="file" name="items[0][image]" accept="image/*"
                onchange="previewImage(this)"
                class="w-full text-sm">

            @error('image') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Deskripsi</label>
            <textarea name="items[0][description]" rows="3"
                class="w-full rounded-lg border px-4 py-2 text-sm">{{ old('description') }}</textarea>
        </div>

    </div> {{-- end item-form --}}

    </div> {{-- end itemsContainer --}}

        {{-- Submit --}}
        <div class="pt-4">
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
                Simpan Item
            </button>
        </div>

    </form>

    <script>
        function previewImage(input){

            const reader = new FileReader();

            reader.onload = function(e){

                const container = input.closest('.item-form');
                const preview = container.querySelector('.imagePreview');

                preview.src = e.target.result;
                preview.classList.remove('hidden');

            };

            reader.readAsDataURL(input.files[0]);
        }

        let itemIndex = 1;

        function addItemForm(){

            const container = document.getElementById('itemsContainer');
            const firstForm = container.querySelector('.item-form');

            const newForm = firstForm.cloneNode(true);

            newForm.querySelectorAll('input, textarea, select').forEach(el => {

                if(el.name){

                    const name = el.name.replace('items[0]', 'items['+itemIndex+']');
                    el.name = name;

                }

                if(el.type !== 'file'){
                    el.value = '';
                }

            });

            container.appendChild(newForm);

            itemIndex++;
        }
    </script>

@endsection