<?php

use App\Http\Controllers\Admin\PointOfSaleController;
use Illuminate\Support\Facades\Route;

Route::prefix('pos')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [PointOfSaleController::class, 'index'])->name('admin.pos.index');
    Route::post('/add-to-cart', [PointOfSaleController::class, 'addToCart'])->name('admin.pos.addToCart');
    Route::post('/update-cart', [PointOfSaleController::class, 'updateCart'])->name('admin.pos.updateCart');
    Route::post('/remove-from-cart', [PointOfSaleController::class, 'removeFromCart'])->name('admin.pos.removeFromCart');
    Route::post('/checkout', [PointOfSaleController::class, 'checkout'])->name('admin.pos.checkout');
    Route::get('/transactions', [PointOfSaleController::class, 'transactions'])->name('admin.pos.transactions');
    Route::get('/transactions/{id}', [PointOfSaleController::class, 'showTransaction'])->name('admin.pos.show');
    Route::post('/process', [PointOfSaleController::class, 'processTransaction'])->name('admin.pos.process');
});
