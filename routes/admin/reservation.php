<?php

use App\Http\Controllers\Admin\ReservationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    
    // Import/Export Routes
    Route::post('/reservation/import-csv', [ReservationController::class, 'importCsv'])->name('reservation.import-csv');
    Route::get('/reservation/export-csv', [ReservationController::class, 'exportCsv'])->name('reservation.export-csv');
    Route::get('/reservation/export-pdf', [ReservationController::class, 'exportPdf'])->name('reservation.export-pdf');

    // Archive / Restore Routes
    Route::post('/reservation/{id}/restore', [ReservationController::class, 'restore'])->name('reservation.restore');
    Route::delete('/reservation/{id}/force-delete', [ReservationController::class, 'forceDelete'])->name('reservation.force-delete');

    // Standard CRUD
    Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
    Route::get('/reservation/{id}', [ReservationController::class, 'show'])->name('reservation.show');
    Route::get('/reservation/{id}/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
    Route::put('/reservation/{id}', [ReservationController::class, 'update'])->name('reservation.update');
    Route::delete('/reservation/{id}', [ReservationController::class, 'destroy'])->name('reservation.destroy');

    // Action Routes
    Route::post('/reservation/{id}/approve', [ReservationController::class, 'approve'])->name('reservation.approve');
    Route::post('/reservation/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel');
    
    // OCR Routes
    Route::post('/reservation/{id}/process-first-payment-ocr', [ReservationController::class, 'processFirstPaymentOCR'])->name('reservation.processFirstPaymentOCR');
    Route::post('/reservation/{id}/process-second-payment-ocr', [ReservationController::class, 'processSecondPaymentOCR'])->name('reservation.processSecondPaymentOCR');
});