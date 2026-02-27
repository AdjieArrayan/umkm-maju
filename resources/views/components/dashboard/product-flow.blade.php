@props(['flows'])

@php
    $getStatusClass = fn($status) =>
        $status === 'Masuk'
            ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400'
            : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400';

    $getQuantityClass = fn($status) =>
        $status === 'Masuk'
            ? 'text-success-600'
            : 'text-error-600';
@endphp

<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Alur Barang Terbaru
            </h3>
            <p class="text-sm text-gray-500">
                Aktivitas stok masuk dan keluar terbaru
            </p>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">

            <thead>
                <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3">Barang</th>
                    <th class="pb-3">Kategori</th>
                    <th class="pb-3">Jenis</th>
                    <th class="pb-3 text-right">Jumlah</th>
                    <th class="pb-3 text-right">Tanggal</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($flows as $flow)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">

                        {{-- Nama Barang --}}
                        <td class="py-4 text-sm font-medium text-gray-800 dark:text-white">
                            {{ $flow['item'] }}
                        </td>

                        {{-- Kategori --}}
                        <td class="py-4 text-sm text-gray-500">
                            {{ $flow['category'] }}
                        </td>

                        {{-- Status --}}
                        <td class="py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $getStatusClass($flow['status']) }}">
                                {{ $flow['status'] }}
                            </span>
                        </td>

                        {{-- Quantity --}}
                        <td class="py-4 text-sm font-semibold text-right {{ $getQuantityClass($flow['status']) }}">
                            {{ $flow['status'] === 'Masuk' ? '+' : '-' }}
                            {{ number_format($flow['quantity']) }}
                        </td>

                        {{-- Date --}}
                        <td class="py-4 text-sm text-gray-400 text-right">
                            {{ $flow['date'] }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <span class="text-lg">📦</span>
                                <p>Belum ada aktivitas barang</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
