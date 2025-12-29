<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\SearchController;

Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index')->name('search.index');
    Route::post('/search/by-code', 'searchByCode')->name('search.byCode');
    Route::get('/search/{id}/{type}', 'show')->name('search.show');
    Route::get('/search/continue/{id}/{type}', 'continuePayment')->name('search.continue');

    // New OTP method selection flow
    Route::get('/search/select-otp-method', 'selectOtpMethod')->name('search.selectOtpMethod');
    Route::post('/search/send-otp-by-method', 'sendOtpByMethod')->name('search.sendOtpByMethod');

    // Legacy otp routes (keeping for backward compatibility)
    Route::post('/search/validate-contact-information', 'validateContactInformation')
        ->name('search.validateContactInformation');
    Route::post('/search/send-otp', 'sendOtp')->name('search.sendOtp');
    
    Route::get('/search/verify-info', function () {
        return view('user.search.verify-info');
    })->name('search.verifyInfo');

    Route::get('/search/verify-otp', function () {
        return view('user.search.verify-otp');
    })->name('search.verifyOtpForm');

    Route::post('/search/verify-otp', 'verifyOtp')->name('search.verifyOtp');

});
