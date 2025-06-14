<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Product\ProductIndex;
use App\Http\Controllers\AuthController;
use App\Livewire\Cashier\CashierIndex;
use App\Livewire\Dashboard\DashboardIndex;
use App\Livewire\Report\SalesReport;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardIndex::class)->name('dashboard');

    Route::get('/products', ProductIndex::class)->name('products.index');

    Route::get('/cashier', CashierIndex::class)->name('cashier.index');
    Route::get('/report', SalesReport::class)->name('report.index');
});
