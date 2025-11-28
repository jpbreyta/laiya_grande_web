<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookingController as BookingController;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::get('/booking/export-csv', [BookingController::class, 'exportCsv'])->name('booking.export-csv');
    Route::get('/booking/export-pdf', [BookingController::class, 'exportPdf'])->name('booking.export-pdf');
    Route::post('/booking/import-csv', [BookingController::class, 'importCsv'])->name('booking.import-csv');

    Route::get('/booking/deleted', [BookingController::class, 'index'])->defaults('status', 'archived')->name('booking.deleted');

    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store'); // If you need create
    

    Route::post('/booking/{id}/approve', [BookingController::class, 'approve'])->name('booking.approve');
    Route::post('/booking/{id}/reject', [BookingController::class, 'reject'])->name('booking.reject');
    Route::post('/booking/{id}/process-ocr', [BookingController::class, 'processOCRForBooking'])->name('booking.process-ocr');

    Route::post('/booking/{id}/restore', [BookingController::class, 'restore'])->name('booking.restore');
    Route::delete('/booking/{id}/force-delete', [BookingController::class, 'forceDelete'])->name('booking.force-delete');

    Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/{id}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{id}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy'); // This is the "Archive" action
});