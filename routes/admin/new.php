<?php

use App\Http\Controllers\Admin\NewController;
use Illuminate\Support\Facades\Route;

Route::get('/new', [NewController::class, 'index'])->name('new.index');
Route::post('/new', [NewController::class, 'store'])->name('new.store');
