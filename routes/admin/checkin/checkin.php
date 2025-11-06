<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CheckinController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/checkin', [CheckinController::class, 'index'])->name('checkin.index');
    Route::get('/checkin/{id}', [CheckinController::class, 'show'])->name('checkin.show');
    Route::post('/checkin/{id}', [CheckinController::class, 'processCheckin'])->name('checkin.process');
});
