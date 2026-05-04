@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Halaman Forecasting" />

<div class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
    <div class="mx-auto w-full max-w-6xl px-4">
        <x-analytics.analytics
            :items="$items"
            :selectedItem="$selectedItem"
            :labels="$labels"
            :values="$values"
            :forecast="$forecast"
            :daysLeft="$daysLeft"
            :mape="$mape"
            :ma3="$ma3"
            :ma5="$ma5"
            :mape3="$mape3"
            :mape5="$mape5"
            :rop="$rop"
            :insight="$insight"
            :future="$future"
            :futureLabels="$futureLabels"
        />
    </div>
</div>
@endsection
