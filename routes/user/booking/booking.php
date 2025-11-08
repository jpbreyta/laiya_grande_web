<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\BookingController;
Route::prefix('user/booking')->group(function () {
    Route::view('/book', 'user.booking.book')->name('user.booking.book');
});

Route::controller(BookingController::class)->group(function () {
    Route::get('/booking', 'index')->name('booking.index');
    Route::post('/cart/add', 'addToCart')->name('cart.add');
    Route::post('/cart/remove', 'removeFromCart')->name('cart.remove');
    Route::post('/cart/clear', 'clearCart')->name('cart.clear');
    Route::post('/booking/confirm', 'confirmBooking')->name('booking.confirm');
    Route::get('/booking/{id}', [BookingController::class, 'view'])->name('user.booking.view');
});

// Booking step routes
Route::prefix('booking')->group(function () {
    Route::get('/step1', function () {
        return view('user.booking.step1');
    })->name('booking.step1');

    Route::post('/reserve-step1', function () {
        return view('user.booking.reserve');
    })->name('reserving.step1');

    // Confirm booking routes (replaced booking-step2)
    Route::post('/confirmbooking', [BookingController::class, 'showConfirmBooking'])->name('booking.confirmbooking');
    Route::post('/booking/confirm-final', [BookingController::class, 'confirmBooking'])->name('booking.confirm.final');

});
