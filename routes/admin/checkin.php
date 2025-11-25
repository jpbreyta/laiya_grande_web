<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GuestStayController;

Route::prefix('admin/guest-stays')->name('admin.guest-stays.')->group(function () {
    Route::get('/', [GuestStayController::class, 'index'])->name('index');
    Route::get('/{id}', [GuestStayController::class, 'show'])->name('show');
    Route::post('/{id}/checkout', [GuestStayController::class, 'checkout'])->name('checkout');

    Route::post('/admin/guest-stay/checkin', [GuestStayController::class, 'storeOrCheckin'])->name('admin.gueststay.checkin');
});

