<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Stok</title>

<style>

body{
    font-family: sans-serif;
    font-size:12px;
}

h1{
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th,td{
    border:1px solid #000;
    padding:6px;
}

th{
    background:#2F75B5;
    color:white;
}

.section{
    margin-top:25px;
}

</style>

</head>

<body>

<h1>LAPORAN STOK BARANG</h1>

<p style="text-align:center">
Periode : {{ $start }} s/d {{ $end }}
</p>

<div class="section">

<h3>Ringkasan</h3>

<table>
<tr>
<th>Keterangan</th>
<th>Jumlah</th>
</tr>

<tr>
<td>Total Transaksi</td>
<td>{{ $transactions->count() }}</td>
</tr>

<tr>
<td>Total Stok Masuk</td>
<td>{{ $totalMasuk }}</td>
</tr>

<tr>
<td>Total Stok Keluar</td>
<td>{{ $totalKeluar }}</td>
</tr>

</table>

</div>

<div class="section">

<h3>Tabel Transaksi</h3>

<table>

<tr>
<th>Tanggal</th>
<th>Item</th>
<th>Jenis</th>
<th>Jumlah</th>
<th>Deskripsi</th>
</tr>

@foreach($transactions as $t)

<tr>
<td>{{ $t['date'] }}</td>
<td>{{ $t['item'] }}</td>
<td>{{ $t['type'] }}</td>
<td>{{ $t['qty'] }}</td>
<td>{{ $t['desc'] }}</td>
</tr>

@endforeach

</table>

</div>

<div class="section">

<h3>Rekap Stok</h3>

<table>

<tr>
<th>Item</th>
<th>Total Masuk</th>
<th>Total Keluar</th>
<th>Sisa</th>
</tr>

@foreach($items as $item)

@php

$totalIn = $item->stockIns->sum('quantity') ?? 0;
$totalOut = $item->stockOuts->sum('quantity') ?? 0;
$sisa = $totalIn - $totalOut;

@endphp

<tr>
<td>{{ $item->name }}</td>
<td>{{ $totalIn }}</td>
<td>{{ $totalOut }}</td>
<td>{{ $sisa }}</td>
</tr>

@endforeach

</table>

</div>

<div class="section">

<h3>Peringatan Stok Rendah</h3>

<table>

<tr>
<th>Item</th>
<th>Sisa</th>
</tr>

@foreach($items as $item)

@php

$totalIn = $item->stockIns->sum('quantity') ?? 0;
$totalOut = $item->stockOuts->sum('quantity') ?? 0;
$sisa = $totalIn - $totalOut;

@endphp

@if($sisa <= 5)

<tr>
<td>{{ $item->name }}</td>
<td>{{ $sisa }}</td>
</tr>

@endif

@endforeach

</table>

</div>

</body>
</html>