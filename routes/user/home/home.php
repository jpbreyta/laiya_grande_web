<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.home.index');
})->name('home');

Route::get('/gallery', function () {
    return view('user.gallery.index');
})->name('gallery');

Route::get('/location', function () {
    return view('user.location.index');
})->name('location');

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
