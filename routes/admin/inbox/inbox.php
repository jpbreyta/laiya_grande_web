<?php
use App\Http\Controllers\Admin\InboxController;
use Illuminate\Support\Facades\Route;


Route::prefix('inbox')->group(function () {
    Route::get('/', [InboxController::class, 'index'])->name('admin.inbox.index');
    Route::get('/{id}', [InboxController::class, 'show'])->name('admin.inbox.show');
    Route::post('/', [InboxController::class, 'store'])->name('admin.inbox.store');
    Route::delete('/{id}', [InboxController::class, 'destroy'])->name('admin.inbox.destroy');
});
