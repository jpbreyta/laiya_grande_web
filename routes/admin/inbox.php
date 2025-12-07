<?php

use App\Http\Controllers\Admin\InboxController;
use Illuminate\Support\Facades\Route;

Route::get('/inbox', [InboxController::class, 'index'])->name('inbox.index');
Route::get('/inbox/all-mail', [InboxController::class, 'allMail'])->name('inbox.all-mail');
Route::get('/inbox/compose', [InboxController::class, 'compose'])->name('inbox.compose');
Route::get('/inbox/sent', [InboxController::class, 'sent'])->name('inbox.sent');
Route::get('/inbox/archived', [InboxController::class, 'archived'])->name('inbox.archived');
Route::get('/inbox/label/{label}', [InboxController::class, 'filterByLabel'])->name('inbox.label');
Route::post('/inbox/bulk-archive', [InboxController::class, 'bulkArchive'])->name('inbox.bulk-archive');
Route::post('/inbox/bulk-delete', [InboxController::class, 'bulkDelete'])->name('inbox.bulk-delete');
Route::get('/inbox/{id}', [InboxController::class, 'show'])->name('inbox.show');
Route::get('/inbox/{id}/compose-reply', [InboxController::class, 'composeReply'])->name('inbox.compose-reply');
Route::patch('/inbox/{id}/replied', [InboxController::class, 'markAsReplied'])->name('inbox.mark-replied');
Route::patch('/inbox/{id}/archive', [InboxController::class, 'archive'])->name('inbox.archive');
Route::patch('/inbox/{id}/unarchive', [InboxController::class, 'unarchive'])->name('inbox.unarchive');
Route::post('/inbox/{id}/reply', [InboxController::class, 'reply'])->name('inbox.reply');
Route::post('/inbox', [InboxController::class, 'store'])->name('inbox.store');
Route::delete('/inbox/{id}', [InboxController::class, 'destroy'])->name('inbox.destroy');
