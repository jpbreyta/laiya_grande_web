<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PosBookingController;

Route::get('/rooms', [PosBookingController::class, 'rooms']);
Route::post('/bookings', [PosBookingController::class, 'create']);