<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PosInventoryController;

// Rental Items
Route::get('/inventory/rental-items', [PosInventoryController::class, 'getRentalItems']);
Route::post('/inventory/rental-items', [PosInventoryController::class, 'createRentalItem']);
Route::put('/inventory/rental-items/{id}', [PosInventoryController::class, 'updateRentalItem']);
Route::delete('/inventory/rental-items/{id}', [PosInventoryController::class, 'deleteRentalItem']);

// Water Sports
Route::get('/inventory/water-sports', [PosInventoryController::class, 'getWaterSports']);
Route::post('/inventory/water-sports', [PosInventoryController::class, 'createWaterSport']);
Route::put('/inventory/water-sports/{id}', [PosInventoryController::class, 'updateWaterSport']);
Route::delete('/inventory/water-sports/{id}', [PosInventoryController::class, 'deleteWaterSport']);
