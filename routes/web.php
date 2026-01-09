<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManufacturingOrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::resource('invoices', InvoiceController::class);
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('manufacturing', ManufacturingOrderController::class);
    Route::resource('inventories', InventoryController::class);
    Route::resource('pos', PosController::class);
    
    // Custom routes for ERP integration
    Route::post('pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::post('manufacturing/{manufacturing}/status', [ManufacturingOrderController::class, 'updateStatus'])
        ->name('manufacturing.updateStatus');
    Route::get('inventories/report', [InventoryController::class, 'report'])
        ->name('inventories.report');
    Route::get('pos/search', [PosController::class, 'searchProducts'])
        ->name('pos.search');
    Route::get('pos/product/{id}', [PosController::class, 'getProduct'])
        ->name('pos.product');
});
