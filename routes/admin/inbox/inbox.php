<?php
use App\Http\Controllers\Admin\InboxController;
use Illuminate\Support\Facades\Route;


Route::prefix('inbox')->group(function () {
    Route::get('/', [InboxController::class, 'index'])->name('admin.inbox.index');
    Route::get('/compose', [InboxController::class, 'compose'])->name('admin.inbox.compose');
    Route::get('/sent', [InboxController::class, 'sent'])->name('admin.inbox.sent');
    Route::get('/{id}', [InboxController::class, 'show'])->name('admin.inbox.show');
    Route::get('/{id}/compose-reply', [InboxController::class, 'composeReply'])->name('admin.inbox.compose-reply');
    Route::patch('/{id}/replied', [InboxController::class, 'markAsReplied'])->name('admin.inbox.mark-replied');
    Route::post('/{id}/reply', [InboxController::class, 'reply'])->name('admin.inbox.reply');
    Route::post('/', [InboxController::class, 'store'])->name('admin.inbox.store');
    Route::delete('/{id}', [InboxController::class, 'destroy'])->name('admin.inbox.destroy');
});
