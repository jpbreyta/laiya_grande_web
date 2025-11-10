<?php
use App\Http\Controllers\user\ContactController;
use Illuminate\Support\Facades\Route;

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');