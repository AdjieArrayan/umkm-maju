@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Categories" />

<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    <!-- Header -->
    <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Daftar Kategori
        </h3>

        <div class="flex gap-2">

            <!-- Search -->
            <form method="GET" action="{{ route('categories.index') }}">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search category..."
                    class="px-4 py-2 border rounded-lg text-sm dark:bg-gray-800 dark:border-gray-700"
                >
            </form>

            <!-- Tambah Kategori -->
            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                + Tambah Kategori
            </a>

        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden">
        <div class="max-w-full px-5 overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-gray-200 border-y dark:border-gray-700">
                        <th class="px-4 py-3 text-start text-sm text-gray-500">#</th>
                        <th class="px-4 py-3 text-start text-sm text-gray-500">Category Name</th>
                        <th class="px-4 py-3 text-start text-sm text-gray-500">Item</th>
                        <th class="px-4 py-3 text-center text-sm text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-4 py-4 text-sm text-gray-500">
                                {{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}
                            </td>

                            <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                {{ $category->name }}
                            </td>

                            <td class="px-6 py-3">
                                {{ $category->items_count }}
                            </td>

                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('categories.edit', $category->id) }}"
                                        class="px-3 py-1 text-xs bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                        Edit
                                    </a>

                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin mau hapus?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                            onclick="openDeleteModal({{ $category->id }})"
                                            class="px-3 py-1 text-xs text-white bg-red-600 rounded-lg hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-500">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-white/[0.05]">
        <div class="flex justify-center items-center gap-2">

            {{-- Previous --}}
            @if ($categories->onFirstPage())
                <span class="px-3 py-2 text-gray-400 border rounded-lg cursor-not-allowed">←</span>
            @else
                <a href="{{ $categories->previousPageUrl() }}"
                    class="px-3 py-2 border rounded-lg hover:bg-blue-50 hover:text-blue-600">
                    ←
                </a>
            @endif


            {{-- Page Numbers --}}
            @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                @if ($page == $categories->currentPage())
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


            {{-- Next --}}
            @if ($categories->hasMorePages())
                <a href="{{ $categories->nextPageUrl() }}"
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
        class="fixed inset-0 z-50 hidden items-center justify-center ">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6
                    border border-blue-200 ring-4 ring-blue-50 transition-all">

            <h3 class="text-lg font-semibold mb-3">
                Konfirmasi Hapus
            </h3>

            <p class="text-sm text-gray-600 mb-5">
                Apakah Anda yakin ingin menghapus kategori ini?
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

    <script>
        function openDeleteModal(id) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');

            form.action = `/delete-categories/${id}`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
        </script>


@endsection
