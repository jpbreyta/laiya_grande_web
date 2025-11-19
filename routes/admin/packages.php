<?php

use App\Http\Controllers\Admin\PackagesController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/packages', [PackagesController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [PackagesController::class, 'create'])->name('packages.create');
    Route::post('/packages', [PackagesController::class, 'store'])->name('packages.store');
    Route::get('/packages/{id}/edit', [PackagesController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{id}', [PackagesController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{id}', [PackagesController::class, 'destroy'])->name('packages.destroy');
});
