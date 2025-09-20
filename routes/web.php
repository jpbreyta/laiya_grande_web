<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

use App\Http\Controllers\GuestBookingController;

Route::get('/register', [GuestBookingController::class, 'showForm'])->name('guest.showForm');
Route::post('/register', [GuestBookingController::class, 'reserve'])->name('guest.reserve');
