<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ReservationController;

/*
|--------------------------------------------------------------------------
| User Reservation Routes (Singular)
|--------------------------------------------------------------------------
*/

// Route to show reservation page (old index - shows all reservations directly)
Route::get('/user/reserve', function () {
    $reservations = \App\Models\Reservation::with('room')->latest()->get();
    return view('user.reserve.index', compact('reservations'));
})->name('user.reserve.index');



// Routes for reservation CRUD (singular)
Route::prefix('user/reservation')->name('user.reservation.')->group(function () {

    // List all reservations
    Route::get('/', [ReservationController::class, 'index'])->name('index');

    // Show reservation creation form
    Route::get('/create', [ReservationController::class, 'create'])->name('create');

    // Store new reservation (AJAX form)
    Route::post('/store', [ReservationController::class, 'store'])->name('store');

    // Show specific reservation details
    Route::get('/{id}', [ReservationController::class, 'show'])->name('show');

    // Edit a reservation
    Route::get('/{id}/edit', [ReservationController::class, 'edit'])->name('edit');

    // Update reservation
    Route::put('/{id}', [ReservationController::class, 'update'])->name('update');

    // Delete reservation
    Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('destroy');

    // Continue payment for reservation
    Route::match(['GET', 'POST'], '/{id}/continue', [ReservationController::class, 'continuePaying'])->name('continue');

    // Update payment for reservation
    Route::post('/{id}/update-payment', [ReservationController::class, 'updatePayment'])->name('updatePayment');
});
