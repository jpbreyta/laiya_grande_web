<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

/**
 * Dashboard query helper only. There is no `dashboards` table.
 */
final class Dashboard
{
    public static function metrics(?int $userId = null): array
    {
        $userId ??= Auth::id();

        return [
            'pending_bookings' => Booking::query()->where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::query()->where('status', 'confirmed')->count(),
            'checked_in_guests' => GuestStay::query()->checkedIn()->count(),
            'available_rooms' => Room::query()->available()->count(),
            'pending_payments' => Payment::query()->pending()->count(),
            'verified_revenue' => Payment::query()->verified()->sum('amount_paid'),
            'pos_revenue' => PosTransactionPayment::query()->where('status', 'completed')->sum('amount'),
            'unread_notifications' => Inbox::unreadNotificationCount($userId),
            'unread_contact_messages' => Inbox::unreadContactMessageCount(),
        ];
    }
}
