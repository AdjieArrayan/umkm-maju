@extends('layouts.app')

@section('content')

    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Edit Barang
        </h3>

    </div>

    <form action="{{ route('items.update', $item->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf
        @method('PUT')


    <div id="itemsContainer">

    <div class="item-form border rounded-xl p-5 mb-6">

        <div>
            <label class="block mb-1 text-sm font-medium dark:text-white/90">Nama Item</label>
            <input type="text" name="name"
                value="{{ old('name', $item->name) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">
            @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium dark:text-white/90">Unit</label>
            <input type="text" name="unit"
                value="{{ old('unit', $item->unit) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">
            @error('unit') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium dark:text-white/90">Harga (Rp)</label>
            <input type="number" name="price" min="0"
                value="{{ old('price', $item->price) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">
            @error('price') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium dark:text-white/90">Stok</label>
            <input type="number" name="stock" min="0"
                value="{{ old('stock', $item->stock) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">
            @error('stock') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium dark:text-white/90">Gambar Item</label>

            <div class="mb-3">
                @if($item->image)
                    <img id="imagePreview"
                         src="{{ asset('storage/'.$item->image) }}"
                         class="w-24 h-24 object-cover rounded-lg border">
                @else
                    <img id="imagePreview"
                         class="hidden w-24 h-24 object-cover rounded-lg border">
                @endif
            </div>

            <input type="file" name="image" accept="image/*"
                onchange="previewImage(event)"
                class="w-full text-sm dark:text-white/90">

            @error('image') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium dark:text-white/90">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full rounded-lg border px-4 py-2 text-sm dark:text-white/90">{{ old('description', $item->description) }}</textarea>
        </div>

    </div>
    </div>

        <div class="pt-4">
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
                Update Item
            </button>
        </div>

    </form>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

@endsection