<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InventoryController;

Route::get('inventory', [InventoryController::class, 'index'])->name('inventory');
