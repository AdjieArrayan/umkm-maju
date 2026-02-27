@props([
    'totalItems' => 0,
    'totalStock' => 0,
    'lowStock' => 0,
    'outOfStock' => 0,
])

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">

    {{-- Total Jenis Barang --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500">Jumlah Jenis Barang</p>
        <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">
            {{ $totalItems }}
        </h3>
    </div>

    {{-- Total Stok --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <p class="text-sm text-gray-500">Total Stok Saat Ini</p>
        <h3 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white">
            {{ number_format($totalStock) }}
        </h3>
    </div>

    {{-- Stok Menipis --}}
    <div class="rounded-2xl border border-warning-200 bg-warning-50 p-6 dark:border-warning-800 dark:bg-warning-500/10">
        <p class="text-sm text-warning-700">Barang Stok Menipis</p>
        <h3 class="mt-2 text-2xl font-bold text-warning-600">
            {{ $lowStock }}
        </h3>
    </div>

    {{-- Stok Habis --}}
    <div class="rounded-2xl border border-error-200 bg-error-50 p-6 dark:border-error-800 dark:bg-error-500/10">
        <p class="text-sm text-error-700">Barang Stok Habis</p>
        <h3 class="mt-2 text-2xl font-bold text-error-600">
            {{ $outOfStock }}
        </h3>
    </div>

</div>
