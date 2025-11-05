<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.home.index');
})->name('home');

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
