<?php

use App\Http\Controllers\Admin\PointOfSaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PointOfSaleController::class, 'index'])->name('pos.index');
Route::post('/add-to-cart', [PointOfSaleController::class, 'addToCart'])->name('pos.addToCart');
Route::post('/update-cart', [PointOfSaleController::class, 'updateCart'])->name('pos.updateCart');
Route::post('/remove-from-cart', [PointOfSaleController::class, 'removeFromCart'])->name('pos.removeFromCart');
Route::post('/checkout', [PointOfSaleController::class, 'checkout'])->name('pos.checkout');
Route::get('/transactions', [PointOfSaleController::class, 'transactions'])->name('pos.transactions');
Route::get('/transactions/{id}', [PointOfSaleController::class, 'showTransaction'])->name('pos.show');
Route::post('/process', [PointOfSaleController::class, 'processTransaction'])->name('pos.process');
