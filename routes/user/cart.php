<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CartController;

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/increment', [CartController::class, 'increment'])->name('cart.increment');
Route::post('/cart/decrement', [CartController::class, 'decrement'])->name('cart.decrement');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/details', [CartController::class, 'getCartDetails'])->name('cart.details');
