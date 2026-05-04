<?php

namespace App\Services;

class ForecastService
{
    public function movingAverage(array $data, int $n = 3)
    {
        $count = count($data);

        if ($count < $n) {
            return null;
        }

        $sum = 0;

        for ($i = $count - $n; $i < $count; $i++) {
            $sum += $data[$i];
        }

        return $sum / $n;
    }

    public function calculateMAPE(array $data, $period = 3)
    {
        $n = count($data);

        if ($n <= $period) {
            return null;
        }

        $errors = [];

        for ($i = $period; $i < $n; $i++) {

            // ambil data sebelumnya
            $slice = array_slice($data, $i - $period, $period);

            $forecast = array_sum($slice) / $period;
            $actual = $data[$i];

            if ($actual == 0) continue; // hindari pembagian nol

            $error = abs($actual - $forecast) / $actual;
            $errors[] = $error;
        }

        if (count($errors) == 0) return null;

        return (array_sum($errors) / count($errors)) * 100;
    }

    public function movingAverageSeries(array $data, $period)
    {
        $result = [];

        for ($i = 0; $i < count($data); $i++) {

            if ($i < $period - 1) {
                $result[] = null;
            } else {
                $slice = array_slice($data, $i - $period + 1, $period);
                $result[] = array_sum($slice) / $period;
            }
        }

        return $result;
    }

    public function calculateROP($forecastPerDay, $leadTime = 3, $safetyStock = 10)
    {
        return ($forecastPerDay * $leadTime) + $safetyStock;
    }

    public function generateInsight($trend, $daysLeft, $rop, $stock, $mape)
    {
        $insight = [];

        // Trend
        if ($trend == 'up') {
            $insight[] = 'Penjualan menunjukkan tren meningkat.';
        } elseif ($trend == 'down') {
            $insight[] = 'Penjualan menunjukkan tren menurun.';
        } else {
            $insight[] = 'Penjualan relatif stabil.';
        }

        // Stok
        if ($daysLeft <= 2) {
            $insight[] = 'Stok diperkirakan akan habis dalam waktu dekat.';
        } elseif ($daysLeft <= 5) {
            $insight[] = 'Stok mulai menipis.';
        } else {
            $insight[] = 'Stok masih dalam kondisi aman.';
        }

        // ROP
        if ($rop && $stock <= $rop) {
            $insight[] = 'Disarankan segera melakukan restock.';
        }

        // Akurasi
        if ($mape !== null) {
            if ($mape <= 10) {
                $insight[] = 'Model prediksi sangat akurat.';
            } elseif ($mape <= 20) {
                $insight[] = 'Model prediksi cukup akurat.';
            } else {
                $insight[] = 'Akurasi prediksi perlu ditingkatkan.';
            }
        }

        return implode(' ', $insight);
    }

    public function forecastNextDays($lastData, $period = 3, $days = 7)
    {
        $data = $lastData;

        $result = [];

        for ($i = 0; $i < $days; $i++) {

            $slice = array_slice($data, -$period);
            $forecast = array_sum($slice) / count($slice);

            $result[] = round($forecast, 2);

            // tambahkan ke data supaya rolling
            $data[] = $forecast;
        }

        return $result;
    }
}