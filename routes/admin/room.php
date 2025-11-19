<?php

use App\Http\Controllers\Admin\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/room', [RoomController::class, 'index'])->name('admin.room.index');
Route::get('/room/create', [RoomController::class, 'create'])->name('admin.room.create');
Route::post('/room', [RoomController::class, 'store'])->name('admin.room.store');
Route::get('/room/{room}', [RoomController::class, 'show'])->name('admin.room.show');
Route::get('/room/{room}/edit', [RoomController::class, 'edit'])->name('admin.room.edit');
Route::put('/room/{room}', [RoomController::class, 'update'])->name('admin.room.update');
Route::delete('/room/{room}', [RoomController::class, 'destroy'])->name('admin.room.destroy');
