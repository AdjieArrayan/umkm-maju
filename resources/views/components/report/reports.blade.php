@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Report" />

<div class="space-y-5">

    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
        Unduh Laporan
    </h3>

    @if ($errors->any())
        <div class="mb-4 flex items-start gap-2 rounded-lg border-l-4 border-red-500 bg-red-100 p-3 text-sm text-red-700">

            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg"
                class="mt-0.5 h-5 w-5 flex-shrink-0"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M10.29 3.86l-7.1 12.3A2 2 0 005.08 19h13.84a2 2 0 001.89-2.84l-7.1-12.3a2 2 0 00-3.42 0z"/>
            </svg>

            <div>
                <ul>
                    @foreach ($errors->unique() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
    @endif

    <form method="POST" action="{{ route('reports.pdf') }}" class="space-y-4">
    @csrf

        <div>
            <label class="block mb-1 text-sm font-medium dark:text-white/90">Tanggal Mulai</label>
            <input type="date" name="start_date" class="w-full border rounded-lg px-3 py-2 dark:text-white/90">
        </div>

        <div>
            <label class="text-sm dark:text-white/90">Tanggal Akhir</label>
            <input type="date" name="end_date" class="w-full border rounded-lg px-3 py-2 dark:text-white/90">
        </div>

        <div>
            <label class="text-sm dark:text-white/90">Jenis Laporan</label>
                <select name="type" class="w-full border rounded-lg px-3 py-2 dark:text-white/90">
                    <option value="all">Semua</option>
                    <option value="in">Stok Masuk</option>
                    <option value="out">Stok Keluar</option>
                </select>
        </div>

        <div class="flex gap-3 pt-4">

            <button formaction="{{ route('reports.pdf') }}"
                class="px-5 py-2 bg-red-500 text-white rounded-lg">
                    Export PDF
            </button>

            <button formaction="{{ route('reports.excel') }}"
                class="px-5 py-2 bg-green-500 text-white rounded-lg">
                    Export Excel
            </button>

        </div>

    </form>

</div>

@endsection