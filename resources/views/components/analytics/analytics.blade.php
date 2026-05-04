@extends('layouts.app')

@section('content')

<x-common.page-breadcrumb pageTitle="Analytics Forecasting" />

<div class="space-y-3">

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                Analitik Barang
            </h3>

            <button onclick="openModal()"
                class="w-8 h-8 flex items-center justify-center rounded-full
                       bg-gray-200 text-gray-600 hover:bg-gray-300
                       dark:bg-gray-700 dark:text-gray-300">
                i
            </button>
        </div>

        {{-- FILTER --}}
        <form method="GET" class="p-4 rounded-xl border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800">
            <label class="text-sm text-gray-600 dark:text-gray-300">
                Pilih Barang
            </label>

            <select name="item_id"
                onchange="this.form.submit()"
                class="mt-2 w-full rounded-lg border border-gray-300 bg-white p-2
                       text-gray-700 focus:ring-2 focus:ring-blue-500
                       dark:border-gray-600 dark:bg-gray-900 dark:text-white">

                @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ $item->id == $selectedItem->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- INFO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">

            {{-- FORECAST --}}
            <div class="relative group p-4 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">Forecast per Hari</p>
                <h2 class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $forecast }}
                </h2>

                <div class="absolute top-3 right-3">
                    <div class="w-5 h-5 flex items-center justify-center text-xs rounded-full bg-gray-200 dark:bg-gray-700">i</div>
                    <div class="absolute right-0 mt-2 w-52 p-2 text-xs rounded-lg bg-black text-white opacity-0 group-hover:opacity-100 transition">
                        Prediksi rata-rata penjualan per hari berdasarkan data sebelumnya.
                    </div>
                </div>
            </div>

            {{-- DAYS LEFT --}}
            <div class="relative group p-4 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">Estimasi Habis</p>
                <h2 class="text-2xl font-bold text-red-500 dark:text-red-400">
                    {{ $daysLeft }} hari
                </h2>

                <div class="absolute top-3 right-3">
                    <div class="w-5 h-5 flex items-center justify-center text-xs rounded-full bg-gray-200 dark:bg-gray-700">i</div>
                    <div class="absolute right-0 mt-2 w-52 p-2 text-xs rounded-lg bg-black text-white opacity-0 group-hover:opacity-100 transition">
                        Perkiraan berapa hari stok akan habis jika penjualan tetap sama.
                    </div>
                </div>
            </div>

            {{-- MAPE --}}
            <div class="relative group p-4 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">Akurasi Forecast (MAPE)</p>

                <h2 class="text-2xl font-bold
                    @if($mape <= 10) text-green-500
                    @elseif($mape <= 20) text-blue-500
                    @elseif($mape <= 50) text-yellow-500
                    @else text-red-500
                    @endif">
                    {{ $mape ? $mape . '%' : '-' }}
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    @if($mape <= 10) Sangat Akurat
                    @elseif($mape <= 20) Akurat
                    @elseif($mape <= 50) Cukup
                    @else Kurang Akurat
                    @endif
                </p>

                <div class="absolute top-3 right-3">
                    <div class="w-5 h-5 flex items-center justify-center text-xs rounded-full bg-gray-200 dark:bg-gray-700">i</div>
                    <div class="absolute right-0 mt-2 w-52 p-2 text-xs rounded-lg bg-black text-white opacity-0 group-hover:opacity-100 transition">
                        Mengukur tingkat error antara prediksi dan data asli (semakin kecil semakin akurat).
                    </div>
                </div>
            </div>

            {{-- MA COMPARISON --}}
            <div class="relative group p-4 rounded-xl border bg-white dark:bg-gray-900">
                <p class="text-sm text-gray-500">Perbandingan Model</p>

                <p class="text-sm mt-2 text-gray-500 dark:text-gray-400">MA 3: <span class="font-semibold">{{ $mape3 }}%</span></p>
                <p class="text-sm text-gray-500 dark:text-gray-400">MA 5: <span class="font-semibold">{{ $mape5 }}%</span></p>

                <p class="text-xs mt-2 text-gray-500 dark:text-gray-400">
                    Model terbaik: <strong>{{ $mape3 < $mape5 ? 'MA 3' : 'MA 5' }}</strong>
                </p>

                <div class="absolute top-3 right-3">
                    <div class="w-5 h-5 flex items-center justify-center text-xs rounded-full bg-gray-200 dark:bg-gray-700">i</div>
                    <div class="absolute right-0 mt-2 w-52 p-2 text-xs rounded-lg bg-black text-white opacity-0 group-hover:opacity-100 transition">
                        Perbandingan akurasi antara MA 3 dan MA 5 untuk menentukan model terbaik.
                    </div>
                </div>
            </div>

            {{-- ROP --}}
            <div class="relative group p-4 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">Reorder Point (ROP)</p>

                <h2 class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $rop ?? '-' }}
                </h2>

                <p class="text-xs text-gray-400 mt-1">Batas minimum sebelum restock</p>

                <p class="text-xs mt-2">
                    @if($rop && $selectedItem->stock <= $rop)
                        <span class="text-red-500 font-semibold">⚠️ Harus Restock</span>
                    @else
                        <span class="text-green-500 font-semibold">Stok Aman</span>
                    @endif
                </p>

                <div class="absolute top-3 right-3">
                    <div class="w-5 h-5 flex items-center justify-center text-xs rounded-full bg-gray-200 dark:bg-gray-700">i</div>
                    <div class="absolute right-0 mt-2 w-52 p-2 text-xs rounded-lg bg-black text-white opacity-0 group-hover:opacity-100 transition">
                        Titik batas stok minimum sebelum harus melakukan pemesanan ulang.
                    </div>
                </div>
            </div>

            {{-- INSIGHT --}}
            <div class="relative group p-4 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">
                <p class="text-sm text-gray-500 dark:text-gray-400">Insight Otomatis</p>

                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    {{ $insight }}
                </p>

                <div class="absolute top-3 right-3">
                    <div class="w-5 h-5 flex items-center justify-center text-xs rounded-full bg-gray-200 dark:bg-gray-700">i</div>
                    <div class="absolute right-0 mt-2 w-52 p-2 text-xs rounded-lg bg-black text-white opacity-0 group-hover:opacity-100 transition">
                        Ringkasan analisis otomatis untuk membantu pengambilan keputusan.
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-xl border bg-white dark:bg-gray-900">
                <p class="text-sm text-gray-500">Prediksi 7 Hari</p>

                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($future as $day)
                        <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded">
                            {{ $day }}
                        </span>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- CHART --}}
        <div class="mt-6 p-4 rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900 relative group">

            <div class="flex justify-between items-center mb-3">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Grafik Penjualan & Forecast
                </h3>

                <!-- ICON INFO -->
                <div class="relative">
                    <!-- BUTTON -->
                    <button id="infoBtn"
                        class="w-5 h-5 flex items-center justify-center text-xs rounded-full
                            bg-gray-200 dark:bg-gray-700 cursor-pointer">
                        i
                    </button>

                    <!-- TOOLTIP -->
                    <div id="infoBox"
                        class="hidden absolute right-0 mt-2 w-64 p-3 text-xs rounded-lg
                            bg-black text-white z-50 leading-relaxed">

                        <p>• Garis biru: data penjualan aktual</p>
                        <p>• Garis merah: hasil forecast hari berikutnya</p>
                        <p>• Garis oranye: Moving Average (MA 3)</p>
                        <p>• Garis hijau: Moving Average (MA 5)</p>
                        <p>• Garis ungu putus-putus: prediksi 7 hari ke depan</p>

                    </div>
                </div>
            </div>

            <div class="relative w-full h-[350px]">
                <canvas id="forecastChart"></canvas>
            </div>
        </div>

    </div>
</div>

    <div id="infoModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 transition-opacity">

        <div class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-6xl p-6 shadow-lg max-h-[80vh] overflow-y-auto">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    Penjelasan Analitik
                </h2>

                <button onclick="closeModal()" class="text-gray-500 hover:text-red-500">
                    ✕
                </button>
            </div>

            <div class="space-y-5 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">

                <div>
                    <strong>Forecast (Peramalan)</strong>
                    <p>
                        Forecast merupakan prediksi jumlah penjualan di masa mendatang berdasarkan data historis.
                        Pada sistem ini digunakan metode <b>Moving Average</b>, yaitu rata-rata dari beberapa data sebelumnya
                        untuk memperkirakan nilai berikutnya.
                    </p>
                </div>

                <div>
                    <strong>Moving Average (MA)</strong>
                    <p>
                        Moving Average adalah metode peramalan sederhana yang menghitung rata-rata dari sejumlah periode terakhir.
                        Sistem ini menggunakan:
                        <br>• MA 3 → rata-rata 3 hari terakhir
                        <br>• MA 5 → rata-rata 5 hari terakhir
                        <br>
                        Semakin kecil periode, semakin responsif terhadap perubahan.
                    </p>
                </div>

                <div>
                    <strong>MAPE (Mean Absolute Percentage Error)</strong>
                    <p>
                        MAPE digunakan untuk mengukur tingkat akurasi hasil forecasting dalam bentuk persentase.
                        Nilai MAPE yang lebih kecil menunjukkan hasil prediksi yang lebih akurat.
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Interpretasi: &lt;10% (sangat baik), 10–20% (baik), &gt;20% (cukup/kurang)
                    </p>
                </div>

                <div>
                    <strong>Estimasi Hari Tersisa</strong>
                    <p>
                        Menunjukkan perkiraan berapa lama stok akan habis berdasarkan rata-rata penjualan harian.
                        Dihitung dari stok saat ini dibagi dengan nilai forecast.
                    </p>
                </div>

                <div>
                    <strong>Reorder Point (ROP)</strong>
                    <p>
                        ROP adalah batas minimum stok sebelum harus melakukan pemesanan ulang.
                        Dihitung dari kebutuhan selama lead time ditambah safety stock.
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        Tujuan: mencegah kehabisan stok saat menunggu barang datang.
                    </p>
                </div>

                <div>
                    <strong>Trend Penjualan</strong>
                    <p>
                        Trend menunjukkan arah pergerakan penjualan dalam beberapa hari terakhir:
                        <br>• Meningkat → permintaan naik
                        <br>• Menurun → permintaan turun
                        <br>• Stabil → relatif konstan
                    </p>
                </div>

                <div>
                    <strong>Insight Otomatis</strong>
                    <p>
                        Insight merupakan ringkasan analisis yang dihasilkan secara otomatis berdasarkan data.
                        Sistem menggabungkan informasi forecast, trend, akurasi, dan kondisi stok
                        untuk memberikan rekomendasi tindakan kepada pengguna.
                    </p>
                </div>

                <div class="p-3 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 text-xs">
                    💡 Fitur analitik ini membantu pengguna tidak hanya melihat data,
                    tetapi juga memahami kondisi stok dan mengambil keputusan secara cepat dan tepat.
                </div>

            </div>

        </div>
    </div>

@endsection

    @push('scripts')
        <script>

            const btn = document.getElementById('infoBtn');
            const box = document.getElementById('infoBox');

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                box.classList.toggle('hidden');
            });

            // klik luar = tutup
            document.addEventListener('click', function () {
                box.classList.add('hidden');
            });

            // Script Chart

            let chartInstance = null;

            function renderChart() {
                const ctx = document.getElementById('forecastChart');

                if (!ctx) return;

                // destroy chart lama biar ga numpuk
                if (chartInstance) {
                    chartInstance.destroy();
                }

                const isDark = document.documentElement.classList.contains('dark');

                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [
                            ...@json($labels),
                            ...@json($futureLabels)
                        ],
                        datasets: [
                            {
                                label: 'Penjualan Aktual',
                                data: @json($values),
                                borderWidth: 2,
                                tension: 0.3,
                                borderColor: isDark ? '#60a5fa' : '#2563eb'
                            },
                            {
                                label: 'Forecast',
                                data: [
                                    ...Array({{ count($values) - 1 }}).fill(null),
                                    {{ $forecast }}
                                ],
                                borderDash: [5,5],
                                borderWidth: 2,
                                borderColor: isDark ? '#f87171' : '#dc2626'
                            },
                            {
                                label: 'Forecast 7 Hari',
                                data: [
                                    ...Array({{ count($values) }}).fill(null),
                                    ...@json($future)
                                ],
                                borderColor: isDark ? '#a78bfa' : '#7c3aed',
                                borderDash: [8,4],
                                borderWidth: 2,
                                tension: 0.3
                            },
                            {
                                label: 'MA 3',
                                data: @json($ma3),
                                borderWidth: 2,
                                borderColor: isDark ? '#fbbf24' : '#f59e0b',
                                tension: 0.3
                            },
                            {
                                label: 'MA 5',
                                data: @json($ma5),
                                borderWidth: 2,
                                borderDash: [6,4], // 🔥 beda style
                                borderColor: isDark ? '#34d399' : '#10b981',
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                labels: {
                                    color: isDark ? '#e5e7eb' : '#374151'
                                }
                            }
                        },

                        scales: {
                            x: {
                                ticks: {
                                    color: isDark ? '#9ca3af' : '#6b7280'
                                },
                                grid: {
                                    color: isDark ? 'rgba(255,255,255,0.05)' : '#e5e7eb'
                                }
                            },
                            y: {
                                ticks: {
                                    color: isDark ? '#9ca3af' : '#6b7280'
                                },
                                grid: {
                                    color: isDark ? 'rgba(255,255,255,0.05)' : '#e5e7eb'
                                }
                            }
                        }
                    }
                });
            }

            // render awal
            renderChart();

            // 🔥 FIX: biar ga kepotong saat sidebar toggle
            window.addEventListener('resize', () => {
                if (chartInstance) {
                    chartInstance.resize();
                }
            });

            function openModal() {
                document.getElementById('infoModal').classList.remove('hidden');
                document.getElementById('infoModal').classList.add('flex');
            }

            function closeModal() {
                document.getElementById('infoModal').classList.add('hidden');
                document.getElementById('infoModal').classList.remove('flex');
            }

        </script>
    @endpush