<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GuestStayController;

Route::get('/guest-stays', function () { return view('admin.guest-stays.index'); })->name('guest-stays.index');