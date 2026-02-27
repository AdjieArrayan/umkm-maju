@props([
    'lowStock' => 0,
    'outOfStock' => 0,
    'stockHealth' => 100,
    'items' => []
])

<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
            Peringatan Stok
        </h3>
        <p class="text-sm text-gray-500">
            Monitoring kondisi stok barang
        </p>
    </div>

    {{-- Stock Health Bar --}}
    <div class="mb-6">
        <div class="flex justify-between mb-2">
            <span class="text-sm text-gray-500">Kesehatan Stok</span>
            <span class="text-sm font-semibold text-gray-800 dark:text-white">
                {{ $stockHealth }}%
            </span>
        </div>
        <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
            <div
                class="h-2 rounded-full
                {{ $stockHealth > 70 ? 'bg-success-500' : ($stockHealth > 40 ? 'bg-warning-500' : 'bg-error-500') }}"
                style="width: {{ $stockHealth }}%">
            </div>
        </div>
    </div>

    {{-- Status Message --}}
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        @if($outOfStock > 0)
            ⚠️ Ada barang yang stoknya habis.
        @elseif($lowStock > 0)
            ⚠️ Beberapa barang mendekati batas minimum.
        @else
            ✅ Semua stok dalam kondisi aman.
        @endif
    </div>

    {{-- Warning List --}}
    @if(count($items) > 0)
        <div class="space-y-3 max-h-[220px] overflow-y-auto custom-scrollbar">
            @foreach($items as $item)
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                            {{ $item['name'] }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Sisa stok: {{ $item['stock'] }}
                        </p>
                    </div>
                    <span class="text-xs font-semibold text-warning-600">
                        +{{ $item['recommended'] }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Footer Summary --}}
    <div class="flex justify-between mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-sm">
        <div>
            <p class="text-gray-500">Stok Menipis</p>
            <p class="font-semibold text-warning-600">{{ $lowStock }}</p>
        </div>
        <div>
            <p class="text-gray-500">Stok Habis</p>
            <p class="font-semibold text-error-600">{{ $outOfStock }}</p>
        </div>
    </div>

</div>
