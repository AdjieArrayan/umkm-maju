@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Stock Ins" />

<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    <!-- Header -->
    <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

        <h2 class="text-xl font-semibold">Stok Masuk</h2>

        <div class="flex items-center gap-3">

            {{-- SEARCH --}}
            <form method="GET" action="{{ route('stock-ins.index') }}" class="flex items-center gap-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari item / deskripsi..."
                       class="px-3 py-2 text-sm border rounded-lg focus:ring-2 focus:ring-brand-500">

                {{-- supaya filter tidak hilang saat search --}}
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif

                <button class="px-3 py-2 text-sm text-white bg-brand-600 rounded-lg">
                    Cari
                </button>
            </form>

            {{-- FILTER DROPDOWN --}}
            <div class="relative group">
                <button type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">

                    Filter
                </button>

                <div class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">

                    <a href="{{ route('stock-ins.index') }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100">
                        Semua
                    </a>

                    <a href="{{ route('stock-ins.index', ['filter' => 'today', 'search' => request('search')]) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100">
                        Hari Ini
                    </a>

                    <a href="{{ route('stock-ins.index', ['filter' => '7days', 'search' => request('search')]) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100">
                        7 Hari Terakhir
                    </a>

                    <a href="{{ route('stock-ins.index', ['filter' => '1month', 'search' => request('search')]) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100">
                        1 Bulan Terakhir
                    </a>

                    <a href="{{ route('stock-ins.index', ['filter' => '1year', 'search' => request('search')]) }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-100">
                        1 Tahun Terakhir
                    </a>

                </div>

                <a href="{{ route('stock-ins.create') }}"
                    class="px-4 py-2 text-sm text-white bg-brand-600 rounded-lg hover:bg-brand-700">
                    + Tambah Stok
                </a>


            </div>

        </div>
    </div>


    <div class="overflow-hidden">
        <div class="max-w-full px-5 overflow-x-auto">

        <table class="min-w-full">
            <thead>
                <tr class="border-gray-200 border-y dark:border-gray-700">
                    <th class="px-4 py-3 text-center text-sm text-gray-500">Tanggal</th>
                    <th class="px-4 py-3 text-center text-sm text-gray-500">Item</th>
                    <th class="px-4 py-3 text-center text-sm text-gray-500">Jumlah</th>
                    <th class="px-4 py-3 text-center text-sm text-gray-500">Deskripsi</th>
                    <th class="px-4 py-3 text-center text-sm text-gray-500">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($stockIns as $stock)
                    <tr class="border-t">
                        <td class="px-4 py-4 text-sm text-gray-500 text-center">
                            {{ \Carbon\Carbon::parse($stock->date)->format('d M Y') }}
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-500 text-center">
                            {{ $stock->item?->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 font-medium text-green-600 text-center">
                            +{{ $stock->quantity }}
                        </td>

                        <td class="px-4 py-4 text-sm text-gray-500 text-center">
                            {{ $stock->description ?? '-' }}
                        </td>

                        <td class="px-4 py-4 text-center">
                            <div class="flex justify-center gap-2">


                                {{-- EDIT --}}
                                <a href="{{ route('stock-ins.edit', $stock) }}"
                                    class="px-3 py-1 text-xs bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                    Edit
                                </a>

                                {{-- DELETE --}}
                                <button type="button"
                                    onclick="openDeleteModal({{ $stock->id }})"
                                    class="px-3 py-1 text-xs text-white bg-red-600 rounded-lg hover:bg-red-700">
                                    Hapus
                                </button>


                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            Belum ada data stock masuk.
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
        @if ($stockIns->onFirstPage())
            <span class="px-3 py-2 text-gray-400 border rounded-lg cursor-not-allowed">←</span>
        @else
            <a href="{{ $stockIns->previousPageUrl() }}"
                class="px-3 py-2 border rounded-lg hover:bg-blue-50 hover:text-blue-600">
                ←
            </a>
        @endif


        {{-- Page Numbers --}}
        @foreach ($stockIns->getUrlRange(1, $stockIns->lastPage()) as $page => $url)
            @if ($page == $stockIns->currentPage())
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
        @if ($stockIns->hasMorePages())
            <a href="{{ $stockIns->nextPageUrl() }}"
                class="px-3 py-2 border rounded-lg hover:bg-blue-50 hover:text-blue-600">
                →
            </a>
        @else
            <span class="px-3 py-2 text-gray-400 border rounded-lg cursor-not-allowed">→</span>
        @endif

    </div>
</div>

</div>

        <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center ">

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

                form.action = `/delete-stock-in/${id}`;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeDeleteModal() {S
                const modal = document.getElementById('deleteModal');

                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        </script>


@endsection
