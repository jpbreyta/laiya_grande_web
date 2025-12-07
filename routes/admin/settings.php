<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingsController;

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
Route::put('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
Route::get('/settings/communication', [SettingsController::class, 'communication'])->name('settings.communication');
Route::post('/settings/communication', [SettingsController::class, 'updateCommunication'])->name('settings.communication.update');
