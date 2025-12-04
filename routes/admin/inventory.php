<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InventoryController;

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('inventory', [InventoryController::class, 'index'])->name('admin.inventory');
});
