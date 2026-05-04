<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ForecastController;


// Authentification
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')
        ->name('logout');

Route::middleware('auth.custom')->group(function () {

// Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

// CRUD Item
    Route::get('/list-items', [ItemController::class, 'index'])
        ->name('items.index');
    Route::get('/add-items', [ItemController::class, 'create'])
        ->name('items.create');
    Route::post('/store-item', [ItemController::class, 'store'])
        ->name('items.store');
    Route::delete('/delete-item/{item}', [ItemController::class, 'destroy'])
        ->name('items.delete');
    Route::get('/edit-item/{item}', [ItemController::class, 'edit'])
        ->name('items.edit');
    Route::put('/update-item/{item}', [ItemController::class, 'update'])
        ->name('items.update');
    Route::post('/items/import', [ItemController::class, 'import'])
        ->name('items.import');
    Route::get('/items/template', [ItemController::class, 'downloadTemplate'])
        ->name('items.template');

// CRUD Kategori
    Route::get('/categories-list', [CategoryController::class, 'index'])
        ->name('categories.index');
    Route::get('/add-categories', [CategoryController::class, 'create'])
        ->name('categories.create');
    Route::post('/store-categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::get('/edit-categories/{category}', [CategoryController::class, 'edit'])
        ->name('categories.edit');
    Route::put('/update/{category}', [CategoryController::class, 'update'])->
        name('categories.update');
    Route::delete('/delete-categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

// CRUD Stock-Ins
    Route::get('/stock-in-list', [StockInController::class, 'index'])
        ->name('stock-ins.index');
    Route::get('/add-stock-in', [StockInController::class, 'create'])
        ->name('stock-ins.create');
    Route::post('/store-stock-in', [StockInController::class, 'store'])
        ->name('stock-ins.store');
    Route::get('/edit-stock-in/{stockIn}', [StockInController::class, 'edit'])
        ->name('stock-ins.edit');
    Route::put('/update-stock-in/{stockIn}', [StockInController::class, 'update'])
        ->name('stock-ins.update');
    Route::delete('/delete-stock-in/{stockIn}', [StockInController::class, 'destroy'])
        ->name('stock-ins.destroy');

// CRUD Stock-Outs
    Route::get('/stock-out-list', [StockOutController::class, 'index'])
        ->name('stock-outs.index');
    Route::get('/add-stock-out', [StockOutController::class, 'create'])
        ->name('stock-outs.create');
    Route::post('/store-stock-out', [StockOutController::class, 'store'])
        ->name('stock-outs.store');
    Route::get('/edit-stock-out/{stockOut}', [StockOutController::class, 'edit'])
        ->name('stock-outs.edit');
    Route::put('/update-stock-out/{stockOut}', [StockOutController::class, 'update'])
        ->name('stock-outs.update');
    Route::delete('/delete-stock-out/{stockOut}', [StockOutController::class, 'destroy'])
        ->name('stock-outs.destroy');

// Report (Excel/Pdf)
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
    Route::post('/reports/excel', [ReportController::class, 'exportExcel'])
        ->name('reports.excel');
    Route::post('/reports/pdf', [ReportController::class, 'exportPDF'])
        ->name('reports.pdf');

// Analytics (Forecast)
    Route::get('/analytics', [ForecastController::class, 'analytics'])
        ->name('analytics.index');
    Route::get('/forecast/item/{id}', [ForecastController::class, 'forecastItem']);
    Route::get('/forecast/all', [ForecastController::class, 'forecastAll']);

    Route::get('/blank', function () {
            return view('pages.blank', ['title' => 'Blank']);
        })->name('blank');
});