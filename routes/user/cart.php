<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\CartController;

Route::prefix('cart')->name('cart.')->group(function () {
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/increment', [CartController::class, 'increment'])->name('increment');
    Route::post('/decrement', [CartController::class, 'decrement'])->name('decrement');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('clear');
    Route::get('/details', [CartController::class, 'getCartDetails'])->name('details');
});
