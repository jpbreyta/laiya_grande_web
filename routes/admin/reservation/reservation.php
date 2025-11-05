<?php
use App\Http\Controllers\Admin\ReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
    Route::get('/reservation/{id}', [ReservationController::class, 'show'])->name('reservation.show');
    Route::patch('/reservation/{id}/approve', [ReservationController::class, 'approve'])->name('reservation.approve');
    Route::patch('/reservation/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
});
