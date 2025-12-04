<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PosGuestServiceController;

Route::get('/pos/active-guests', [PosGuestServiceController::class, 'activeGuests']);
Route::get('/pos/items', [PosGuestServiceController::class, 'items']);
Route::post('/pos/charge', [PosGuestServiceController::class, 'charge']);

