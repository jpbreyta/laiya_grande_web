<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ContactController;
use App\Http\Controllers\User\CartController;

// ==========================
// USER ROUTES
// ==========================

// Homepage → use HomeController so $rooms & $settings are passed
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('user.home');

// Gallery Page (optional: create a controller if needed)
Route::get('/gallery', function () {
    return view('user.gallery.index');
})->name('gallery');

// Location Page
Route::get('/location', function () {
    return view('user.location.index');
})->name('location');

// Contact Page
Route::get('/contact', function () {
    return view('user.contact.index');
})->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Search Page
Route::get('/search', function () {
    return view('user.search.index');
})->name('user.search');


// ==========================
// ADMIN ROUTES
// ==========================
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');
});

Route::get('/cart/details', [CartController::class, 'getCartDetails'])->name('cart.details');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/increment', [CartController::class, 'increment'])->name('cart.increment');
Route::post('/cart/decrement', [CartController::class, 'decrement'])->name('cart.decrement');