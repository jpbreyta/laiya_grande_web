<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user\ContactController;

Route::get('/', function () {
    return view('user.home.index');
})->name('home');

Route::get('/gallery', function () {
    return view('user.gallery.index');
})->name('gallery');

Route::get('/location', function () {
    return view('user.location.index');
})->name('location');

Route::get('/contact', function () {
    return view('user.contact.index');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/search', function () {
    return view('user.search.index');
})->name('user.search');

Route::prefix('user')->group(function () {
    Route::get('/home', function () {
        return view('user.home.index');
    })->name('user.home');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');
});
