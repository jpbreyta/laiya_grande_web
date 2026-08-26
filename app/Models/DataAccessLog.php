<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class DataAccessLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'customer_id',
        'actor_email',
        'ip_address',
        'user_agent',
        'model_type',
        'model_id',
        'action',
        'reason',
        'metadata',
        'accessed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'accessed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public static function logAccess(
        string $modelType,
        int $modelId,
        string $action,
        ?string $reason = null,
        ?int $customerId = null,
        array $metadata = []
    ): self {
        $request = app()->bound('request') ? request() : null;

        return static::create([
            'user_id' => Auth::id(),
            'customer_id' => $customerId,
            'actor_email' => Auth::user()?->email,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'model_type' => $modelType,
            'model_id' => $modelId,
            'action' => $action,
            'reason' => $reason,
            'metadata' => $metadata ?: null,
            'accessed_at' => now(),
        ]);
    }
}
