<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/dashboard')->middleware('admin')->group(function () {
    Route::view('/', 'admin.dashboard.index')->name('admin.dashboard.index');
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});
