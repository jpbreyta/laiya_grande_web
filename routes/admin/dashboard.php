<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('admin')->group(function () {
    
    // Main Dashboard Page
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // AJAX Filter Endpoint
    Route::get('/dashboard/filter', [DashboardController::class, 'filter'])->name('admin.dashboard.filter');
    
    // Calendar Events Endpoint
    Route::get('/dashboard/calendar-events', [DashboardController::class, 'calendarEvents'])->name('admin.dashboard.calendar-events');

});