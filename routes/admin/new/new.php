<?php

use App\Http\Controllers\Admin\NewController;
use Illuminate\Support\Facades\Route;

Route::get('/new', [NewController::class, 'index'])->name('admin.new.index');