<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookingController as BookingController;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::post('/booking/{id}/approve', [BookingController::class, 'approve'])->name('booking.approve');
    Route::post('/booking/{id}/reject', [BookingController::class, 'reject'])->name('booking.reject');
    Route::post('/booking/{id}/process-ocr', [BookingController::class, 'processOCRForBooking'])->name('booking.process-ocr');
});
