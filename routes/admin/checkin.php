<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GuestStayController;

Route::get('/', [GuestStayController::class, 'index'])->name('index');
Route::get('/export-csv', [GuestStayController::class, 'exportCsv'])->name('export-csv');
Route::get('/export-pdf', [GuestStayController::class, 'exportPdf'])->name('export-pdf');
Route::get('/{id}', [GuestStayController::class, 'show'])->name('show');
Route::post('/{id}/checkout', [GuestStayController::class, 'checkout'])->name('checkout');

Route::post('/admin/guest-stay/checkin', [GuestStayController::class, 'storeOrCheckin'])->name('gueststay.checkin');
