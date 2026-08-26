<?php

namespace App\Services\Communication;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class StaffNotificationService
{
    /**
     * Create one database notification for each active operational user.
     */
    public function notify(
        string $type,
        string $title,
        string $message,
        array $data = []
    ): void {
        $recipients = User::query()
            ->where('is_active', true)
            ->whereHas('roles', fn ($query) => $query->whereIn(
                'roles.name',
                ['admin', 'manager', 'receptionist']
            ))
            ->pluck('id');

        if ($recipients->isEmpty()) {
            Log::warning('Staff notification was not created because no active recipient exists.', [
                'type' => $type,
            ]);

            return;
        }

        $now = now();
        $rows = $recipients->map(fn (int $userId): array => [
            'user_id' => $userId,
            'customer_id' => null,
            'is_broadcast' => false,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => json_encode($data, JSON_THROW_ON_ERROR),
            'read_at' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        Notification::query()->insert($rows);
    }
}
