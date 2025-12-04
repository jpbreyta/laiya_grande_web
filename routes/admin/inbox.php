<?php
use App\Http\Controllers\Admin\InboxController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/inbox')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [InboxController::class, 'index'])->name('admin.inbox.index');
    Route::get('/all-mail', [InboxController::class, 'allMail'])->name('admin.inbox.all-mail');
    Route::get('/compose', [InboxController::class, 'compose'])->name('admin.inbox.compose');
    Route::get('/sent', [InboxController::class, 'sent'])->name('admin.inbox.sent');
    Route::get('/archived', [InboxController::class, 'archived'])->name('admin.inbox.archived');
    Route::get('/label/{label}', [InboxController::class, 'filterByLabel'])->name('admin.inbox.label');
    Route::post('/bulk-archive', [InboxController::class, 'bulkArchive'])->name('admin.inbox.bulk-archive');
    Route::post('/bulk-delete', [InboxController::class, 'bulkDelete'])->name('admin.inbox.bulk-delete');
    Route::get('/{id}', [InboxController::class, 'show'])->name('admin.inbox.show');
    Route::get('/{id}/compose-reply', [InboxController::class, 'composeReply'])->name('admin.inbox.compose-reply');
    Route::patch('/{id}/replied', [InboxController::class, 'markAsReplied'])->name('admin.inbox.mark-replied');
    Route::patch('/{id}/archive', [InboxController::class, 'archive'])->name('admin.inbox.archive');
    Route::patch('/{id}/unarchive', [InboxController::class, 'unarchive'])->name('admin.inbox.unarchive');
    Route::post('/{id}/reply', [InboxController::class, 'reply'])->name('admin.inbox.reply');
    Route::post('/', [InboxController::class, 'store'])->name('admin.inbox.store');
    Route::delete('/{id}', [InboxController::class, 'destroy'])->name('admin.inbox.destroy');
});
