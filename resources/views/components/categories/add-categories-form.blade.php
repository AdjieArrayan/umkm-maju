@extends('layouts.app')

@section('content')

<div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">

    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Tambah Kategori
    </h3>

</div>

    <form action="{{ route('categories.store') }}" method="POST" class="space-y-5">
        @csrf

        <div id="itemsContainer">

            <div class="item-form border rounded-xl p-5 mb-6">

                <div>
                    <label class="block mb-1 text-sm font-medium dark:text-white/90">Nama Kategori</label>

                    <input type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Elektronik"
                        class="w-full h-11 rounded-lg border px-4 text-sm dark:text-white/90">

                    @error('name')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

        </div>

        <div class="pt-4">

            <button type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-brand-600 rounded-lg hover:bg-brand-700">
                Simpan Kategori
            </button>

        </div>

    </form>

@endsection
