@props([
    'items' => []
])

<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
            Peringatan Stok
        </h3>
        <p class="text-sm text-gray-500">
            Monitoring kondisi stok berdasarkan prediksi
        </p>
    </div>

    {{-- Message --}}
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        @if(count($items) > 0)
            ⚠️ Ada barang yang diprediksi akan segera habis.
        @else
            ✅ Semua stok dalam kondisi aman berdasarkan prediksi.
        @endif
    </div>

    {{-- List Item --}}
    @if(count($items) > 0)
        <div class="space-y-3 max-h-[220px] overflow-y-auto custom-scrollbar">
            @foreach($items as $item)
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-800">

                    {{-- Info --}}
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">
                            {{ $item['name'] }}
                        </p>

                        <p class="text-xs text-gray-500">
                            Sisa stok: {{ $item['stock'] }} • {{ $item['days_left'] }} hari lagi
                        </p>
                    </div>

                    {{-- Rekomendasi --}}
                    <span class="text-xs font-semibold
                        {{ $item['days_left'] <= 2 ? 'text-error-600' : 'text-warning-600' }}">
                        +{{ $item['recommended'] }}
                    </span>

                </div>
            @endforeach
        </div>
    @endif

</div>