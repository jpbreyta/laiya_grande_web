<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\BookingController;

Route::prefix('user/booking')->name('user.booking.')->group(function () {
    Route::get('/book', [BookingController::class, 'book'])->name('book');
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::post('/show-confirm', [BookingController::class, 'showConfirmBooking'])->name('user.booking.confirmbooking');
    Route::get('/{id}', [BookingController::class, 'view'])->name('view');
    Route::post('/send-otp', [BookingController::class, 'sendOTP'])->name('send-otp');
    Route::post('/verify-otp', [BookingController::class, 'verifyOTP'])->name('verify-otp');
    Route::post('/show-confirm', [BookingController::class, 'showConfirmBooking'])->name('show-confirm');
    Route::post('/confirm-final', [BookingController::class, 'confirm-final'])->name('confirm-final');
});

Route::prefix('booking')->name('booking.step.')->group(fn () => [
    Route::get('/step1', fn () => view('user.booking.step1'))->name('1'),
    Route::post('/reserve-step1', fn () => view('user.booking.reserve'))->name('reserve1'),
]);
