@props([
    'stockIn' => [],
    'stockOut' => [],
    'labels' => [],
])

<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Grafik Stok Masuk & Keluar
            </h3>
            <p class="text-sm text-gray-500">
                Pergerakan stok berdasarkan periode
            </p>
        </div>
    </div>

    <div class="h-[350px]">
        <canvas id="stockChart"></canvas>
    </div>

</div>
