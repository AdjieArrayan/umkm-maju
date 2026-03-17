@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Items" />

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Daftar Item
        </h3>

        <div class="flex gap-2">

            <form method="GET" action="{{ route('items.index') }}">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search item..."
                    class="px-4 py-2 border rounded-lg text-sm dark:bg-gray-800 dark:border-gray-700 dark:text-white/90"
                >
            </form>

                <a href="{{ route('items.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                + Tambah Item
                </a>

                <button onclick="openImportModal()"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                Import Excel
                </button>

                <a href="{{ route('items.template') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Download Template
                </a>

        </div>

    </div>
</div>

    @if(session('success'))
        <div class="mx-6 mb-4 flex items-center gap-3 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-700 border border-green-200">

            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5 13l4 4L19 7" />
            </svg>

            <span>{{ session('success') }}</span>

        </div>
    @endif

    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-y border-gray-100 dark:border-white/[0.05]">
                    <th class="px-6 py-3 text-xs text-left text-gray-500 dark:text-white/90">Image</th>
                    <th class="px-6 py-3 text-xs text-left text-gray-500 dark:text-white/90">Name</th>
                    <th class="px-6 py-3 text-xs text-left text-gray-500 dark:text-white/90">Unit</th>
                    <th class="px-6 py-3 text-xs text-left text-gray-500 dark:text-white/90">Stock</th>
                    <th class="px-6 py-3 text-xs text-left text-gray-500 dark:text-white/90">Price</th>
                    <th class="px-6 py-3 text-xs text-left text-gray-500 dark:text-white/90">Description</th>
                    <th class="px-6 py-3 text-xs text-left text-gray-500 dark:text-white/90">Status</th>
                    <th class="px-6 py-3 text-xs text-center text-gray-500 dark:text-white/90">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-white/[0.05]">

                @forelse($items as $item)
                    <tr>

                        <td class="px-6 py-3">
                            @if($item->image)
                                <img src="{{ asset('storage/'.$item->image) }}"
                                     class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg"></div>
                            @endif
                        </td>

                        <td class="px-6 py-3 font-medium text-gray-800 dark:text-white/90">
                            {{ $item->name }}
                        </td>

                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-white/90">
                            {{ $item->unit ?? '-' }}
                        </td>

                        <td class="px-6 py-3 text-sm dark:text-white/90">
                            {{ $item->stock }}
                        </td>

                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-white/90">
                            Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-3 text-sm text-gray-500 dark:text-white/90 max-w-xs truncate">
                            {{ $item->description ?? '-' }}
                        </td>

                        <td class="px-6 py-3 text-sm">
                            @if($item->stock == 0)
                                <span class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-full">
                                    Habis
                                </span>
                            @elseif($item->stock <= $item->minimum_stock)
                                <span class="px-2 py-1 text-xs font-medium text-yellow-600 bg-yellow-100 rounded-full">
                                    Menipis
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium text-green-600 bg-green-100 rounded-full">
                                    Aman
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-3 text-center">

                            <div class="flex items-center justify-center gap-2">

                                <a href="{{ route('items.edit', $item->id) }}"
                                    class="px-3 py-1 text-xs text-white bg-green-600 rounded-lg hover:bg-green-700">
                                     Edit
                                 </a>

                                <form action="{{ route('items.delete', $item->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                <button type="button"
                                    onclick="openDeleteModal({{ $item->id }})"
                                    class="px-3 py-1 text-xs text-white bg-red-600 rounded-lg hover:bg-red-700">
                                    Hapus
                                </button>

                                </form>

                            </div>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-6 text-center text-gray-500">
                            Belum ada item.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
        <div class="flex justify-center items-center gap-2">

            @if ($items->onFirstPage())
                <span class="px-3 py-2 text-gray-400 border rounded-lg cursor-not-allowed">←</span>
            @else
                <a href="{{ $items->previousPageUrl() }}"
                   class="px-3 py-2 border rounded-lg hover:bg-blue-50 hover:text-blue-600">
                    ←
                </a>
            @endif

            @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                @if ($page == $items->currentPage())
                    <span class="px-3 py-2 bg-blue-500 text-white rounded-lg">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="px-3 py-2 border rounded-lg hover:bg-blue-50 hover:text-blue-600">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if ($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}"
                   class="px-3 py-2 border rounded-lg hover:bg-blue-50 hover:text-blue-600">
                    →
                </a>
            @else
                <span class="px-3 py-2 text-gray-400 border rounded-lg cursor-not-allowed">→</span>
            @endif

        </div>
    </div>

</div>

    <div id="deleteModal"
        class="fixed inset-0 z-50 hidden items-center justify-center">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 border border-blue-200 ring-4 ring-blue-50 transition-all">

            <h3 class="text-lg font-semibold mb-3">Konfirmasi Hapus</h3>

            <p class="text-sm text-gray-600 mb-5">
                Apakah Anda yakin ingin menghapus item ini?
            </p>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex justify-end gap-3">
                    <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700">
                        Ya, Hapus
                    </button>
                </div>
            </form>

        </div>
    </div>

    <div id="importModal"
        class="fixed inset-0 z-50 hidden items-center justify-center">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 border border-green-200">

            <h3 class="text-lg font-semibold mb-4">Import Excel Item</h3>

            <form action="{{ route('items.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="file" name="file" class="w-full mb-4 border rounded-lg p-2"required>
                <div class="flex justify-end gap-3">

                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 text-sm bg-gray-200 rounded-lg">
                        Batal
                    </button>

                    <button type="submit" class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">
                        Import
                    </button>

                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(id) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');

            form.action = `/delete-item/${id}`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function openImportModal() {
            const modal = document.getElementById('importModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImportModal() {
            const modal = document.getElementById('importModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>



@endsection
