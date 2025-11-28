<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\RatingController;

Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
Route::get('/ratings/room/{roomId}', [RatingController::class, 'getRoomRatings'])->name('ratings.room');
