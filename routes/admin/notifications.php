<?php

use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/notifications')->middleware('admin')->group(function () {
    Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.mark-read');
    Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('admin.notifications.unread-count');
});
