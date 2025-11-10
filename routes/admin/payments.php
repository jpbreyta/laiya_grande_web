<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PaymentController;

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::resource('payments', PaymentController::class)->names([
        'index' => 'admin.payments.index',
        'show' => 'admin.payments.show',
    ]);

    // Additional routes for payment processing
    Route::post('bookings/{bookingId}/process-payment-proof', [PaymentController::class, 'processPaymentProof'])
        ->name('admin.bookings.process-payment-proof');

    // Test route for OCR functionality
    Route::get('test-payment-ocr', [PaymentController::class, 'testOcrPage'])->name('admin.test-payment-ocr');
    Route::post('test-payment-ocr', [PaymentController::class, 'testProcessPaymentProof'])->name('admin.test-process-payment-proof');
});

// Public test routes (no admin middleware for testing)
Route::get('test-payment-ocr', [PaymentController::class, 'testOcrPage'])->name('test-payment-ocr');
Route::post('test-payment-ocr', [PaymentController::class, 'testProcessPaymentProof'])->name('test-process-payment-proof');
