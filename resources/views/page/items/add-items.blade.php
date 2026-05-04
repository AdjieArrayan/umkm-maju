@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb/>

<div class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
    <div class="mx-auto w-full max-w-6xl px-4">
        <x-items.add-items-form
            :categories="$categories"
        />
    </div>
</div>
@endsection
