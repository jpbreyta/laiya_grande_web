<?php

use App\Http\Controllers\Admin\NewController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin')->group(function () {
    Route::get('/new', [NewController::class, 'index'])->name('admin.new.index');
    Route::post('/new', [NewController::class, 'store'])->name('admin.new.store');
});
