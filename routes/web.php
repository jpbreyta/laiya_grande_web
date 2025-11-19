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
require __DIR__ . '/user/search/search.php';
require __DIR__ . '/user/room/room.php';
require __DIR__ . '/user/contact/contact.php';



require __DIR__. '/admin/dashboard.php';
require __DIR__. '/admin/booking.php';
require __DIR__. '/admin/reservation.php';
require __DIR__. '/admin/packages.php';
require __DIR__. '/admin/new.php';
require __DIR__. '/admin/notifications.php';
require __DIR__. '/admin/qr.php';
require __DIR__. '/admin/checkin.php';
require __DIR__. '/admin/foods.php';
require __DIR__. '/admin/inbox.php';
require __DIR__. '/admin/pos.php';
require __DIR__. '/admin/room.php';
require __DIR__. '/admin/payments.php';
require __DIR__. '/admin/settings.php';