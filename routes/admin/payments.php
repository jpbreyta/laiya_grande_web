<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PaymentController;

Route::resource('payments', PaymentController::class)->names([
    'index' => 'payments.index',
    'show' => 'payments.show',
]);

Route::post('bookings/{bookingId}/process-payment-proof', [PaymentController::class, 'processPaymentProof'])
    ->name('bookings.process-payment-proof');

Route::get('test-payment-ocr', [PaymentController::class, 'testOcrPage'])->name('test-payment-ocr');
Route::post('test-payment-ocr', [PaymentController::class, 'testProcessPaymentProof'])->name('test-process-payment-proof');
