<?php
use Illuminate\Support\Facades\Route;
// Import your controller so Laravel can find it
use App\Http\Controllers\Api\PosBookingController;

/*
|--------------------------------------------------------------------------
| POS API Routes
|--------------------------------------------------------------------------
*/

// Matches: GET http://127.0.0.1:8000/api/rooms
Route::get('/rooms', [PosBookingController::class, 'rooms']);

// Matches: POST http://127.0.0.1:8000/api/bookings
Route::post('/bookings', [PosBookingController::class, 'create']);