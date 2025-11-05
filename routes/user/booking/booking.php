<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\BookingController;

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
    Route::post('/process-booking', [BookingController::class, 'processBooking'])->name('booking.process');
});
