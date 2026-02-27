@extends('layouts.app')

@section('content')
<div class="grid grid-cols-12 gap-6">

    {{-- LEFT SIDE --}}
    <div class="col-span-12 space-y-6 xl:col-span-8">

        {{-- Metrics --}}
        <x-dashboard.product-metrics
            :totalItems="$totalItems"
            :totalStock="$totalStock"
            :lowStock="$lowStockCount"
            :outOfStock="$outOfStockCount"
        />

        {{-- Chart --}}
        <x-dashboard.in-out-stock
            :labels="$chartLabels"
            :stockIn="$stockInChart"
            :stockOut="$stockOutChart"
        />

    </div>

    {{-- RIGHT SIDE --}}
    <div class="col-span-12 xl:col-span-4">
        <x-dashboard.stock-warning
            :lowStock="$lowStockCount"
            :outOfStock="$outOfStockCount"
            :stockHealth="$stockHealth"
            :items="$lowStockItems"
        />
    </div>

    {{-- Bottom Full Width --}}
    <div class="col-span-12">
        <x-dashboard.product-flow
            :flows="$productFlows"
        />
    </div>

</div>

@push('scripts')
<script>
const ctx = document.getElementById('stockChart');

if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Stok Masuk',
                    data: @json($stockInChart),
                    borderWidth: 2
                },
                {
                    label: 'Stok Keluar',
                    data: @json($stockOutChart),
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
}
</script>
@endpush


@endsection
