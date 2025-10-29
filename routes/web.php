<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\BookingController;

// Home routes
Route::get('/', function () {
    return view('user.home.index');
})->name('home');

Route::prefix('user')->group(function () {
    Route::get('/home', function () {
        return view('user.home.index');
    })->name('user.home');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');
});

// Booking routes
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
Route::post('/cart/add', [BookingController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [BookingController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [BookingController::class, 'clearCart'])->name('cart.clear');
Route::post('/booking/confirm', [BookingController::class, 'confirmBooking'])->name('booking.confirm');

// User booking pages
Route::get('/user/booking/book', function () {
    return view('user.booking.book');
})->name('user.booking.book');

Route::get('/user/booking/reserve', function () {
    return view('user.booking.reserve');
})->name('user.booking.reserve');

// Additional booking routes are defined in routes/user/booking/booking.php

// Include user booking routes
require __DIR__.'/user/booking/booking.php';
