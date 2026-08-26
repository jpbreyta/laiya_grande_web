<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Query helper only. There is no `inboxes` table.
 */
final class Inbox
{
    public static function notifications(?int $userId = null): Builder
    {
        $userId ??= Auth::id();

        $query = Notification::query()->latest();

        return $userId === null ? $query->where('is_broadcast', true) : $query->forUser($userId);
    }

    public static function unreadNotificationCount(?int $userId = null): int
    {
        return static::notifications($userId)->unread()->count();
    }

    public static function contactMessages(): Builder
    {
        return ContactMessage::query()->notArchived()->latest();
    }

    public static function unreadContactMessageCount(): int
    {
        return static::contactMessages()->unread()->count();
    }
}
