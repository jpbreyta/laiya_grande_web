<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ReservationController;

/*
|--------------------------------------------------------------------------
| User Reservation Routes
|--------------------------------------------------------------------------
| These routes handle all user-side reservation features.
| They’re grouped under the "user/reservation" prefix.
|--------------------------------------------------------------------------
*/

Route::prefix('user/reserve')->group(function () {
    Route::view('/', 'user.reserve.index')->name('user.reserve.index');
});

Route::prefix('user/reservations')->name('user.reservations.')->group(function () {

    // List all reservations
    Route::get('/', [ReservationController::class, 'index'])->name('index');

    // Show reservation creation form
    Route::get('/create', [ReservationController::class, 'create'])->name('create');

    // Store new reservation
    Route::post('/', [ReservationController::class, 'store'])->name('store');

    // Show specific reservation details
    Route::get('/{id}', [ReservationController::class, 'show'])->name('show');

    // Edit a reservation
    Route::get('/{id}/edit', [ReservationController::class, 'edit'])->name('edit');

    // Update reservation
    Route::put('/{id}', [ReservationController::class, 'update'])->name('update');

    // Delete reservation
    Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('destroy');
});
