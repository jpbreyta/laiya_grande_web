<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

require __DIR__ . '/user/home/home.php';
require __DIR__ . '/user/booking/booking.php';
require __DIR__ . '/user/reservation/reservation.php';
require __DIR__ . '/admin/dashboard/dashboard.php';
require __DIR__ . '/admin/booking/booking.php';
require __DIR__.'/admin/reservation/reservation.php';
require __DIR__.'/admin/packages/packages.php';
