@extends('layouts.app')

@section('content')

    <form action="{{ route('items.update', $item->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Nama Item --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Nama Item</label>
            <input type="text" name="name"
                value="{{ old('name', $item->name) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Unit --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Unit</label>
            <input type="text" name="unit"
                value="{{ old('unit', $item->unit) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('unit') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Harga --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Harga (Rp)</label>
            <input type="number" name="price" min="0"
                value="{{ old('price', $item->price) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('price') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Stok --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Stok</label>
            <input type="number" name="stock" min="0"
                value="{{ old('stock', $item->stock) }}"
                class="w-full h-11 rounded-lg border px-4 text-sm">
            @error('stock') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Gambar --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Gambar Item</label>

            {{-- Preview Gambar Lama --}}
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
                class="w-full text-sm">

            @error('image') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block mb-1 text-sm font-medium">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full rounded-lg border px-4 py-2 text-sm">{{ old('description', $item->description) }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="pt-4">
            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
                Update Item
            </button>
        </div>

    </form>


    {{-- Preview Script --}}
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