<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QRController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/qr-scanner', [QRController::class, 'scanner'])->name('qr.scanner');
    Route::post('/qr-scan', [QRController::class, 'scan'])->name('qr.scan');
    Route::get('/qr-pdf/{id}', [QRController::class, 'generatePdf'])->name('qr.generate-pdf');
    Route::get('/qr-preview/{id}', [QRController::class, 'previewPdf'])->name('qr.preview-pdf');
    Route::get('/qr-checkin/{id}', [QRController::class, 'checkin'])->name('qr.checkin');
    Route::post('/qr-process-checkin/{id}', [QRController::class, 'processCheckin'])->name('qr.process-checkin');
});
