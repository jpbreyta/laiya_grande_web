<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookingController;

Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::post('/', [BookingController::class, 'store'])->name('store');
    
    Route::get('/deleted', [BookingController::class, 'index'])->defaults('status', 'archived')->name('deleted');
    
    Route::get('/export-csv', [BookingController::class, 'exportCsv'])->name('export-csv');
    Route::get('/export-pdf', [BookingController::class, 'exportPdf'])->name('export-pdf');
    Route::post('/import-csv', [BookingController::class, 'importCsv'])->name('import-csv');

    Route::get('/{id}', [BookingController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [BookingController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BookingController::class, 'update'])->name('update');
    Route::delete('/{id}', [BookingController::class, 'destroy'])->name('destroy');

    Route::post('/{id}/approve', [BookingController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [BookingController::class, 'reject'])->name('reject');
    Route::post('/{id}/process-ocr', [BookingController::class, 'processOCRForBooking'])->name('process-ocr');
    Route::post('/{id}/restore', [BookingController::class, 'restore'])->name('restore');
    Route::delete('/{id}/force-delete', [BookingController::class, 'forceDelete'])->name('force-delete');
});

