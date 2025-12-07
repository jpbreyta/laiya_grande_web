<?php
use App\Http\Controllers\User\RoomController;
use Illuminate\Support\Facades\Route;

Route::prefix('rooms')->name('user.rooms.')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('index'); // user.rooms.index
    Route::get('/{id}', [RoomController::class, 'show'])->name('show'); // user.rooms.show
});

