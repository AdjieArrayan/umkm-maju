@extends('layouts.app')

@section('content')


    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Nama Item --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Nama Item</label>
            <input type="text" name="name" value="{{ old('name') }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Kategori</label>
            <select name="category_id" class="w-full h-11 rounded-lg border px-4 text-sm">
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
            <input type="text" name="unit" value="{{ old('unit') }}"
                placeholder="Contoh: pcs, box, kg"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('unit') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Harga --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Harga (Rp)</label>
            <input type="number" name="price" min="0"
                value="{{ old('price') }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('price') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Stok Awal --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Stok Awal</label>
            <input type="number" name="stock" min="0" value="{{ old('stock', 0) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('stock') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Minimum Stock --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Minimum Stok</label>
            <input type="number" name="minimum_stock" min="0"
                value="{{ old('minimum_stock', 5) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('minimum_stock') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Gambar --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Gambar Item</label>

            {{-- Preview Box --}}
            <div class="mb-3">
                <img id="imagePreview"
                     class="hidden w-24 h-24 object-cover rounded-lg border">
            </div>

            <input type="file" name="image" accept="image/*"
                onchange="previewImage(event)"
                class="w-full text-sm">

            @error('image') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full rounded-lg border px-4 py-2 text-sm">{{ old('description') }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="pt-4">
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
                Simpan Item
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