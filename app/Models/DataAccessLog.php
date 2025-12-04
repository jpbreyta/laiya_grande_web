<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DataAccessLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_email',
        'ip_address',
        'model_type',
        'model_id',
        'action',
        'reason',
        'accessed_at',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log data access for audit trail
     */
    public static function logAccess(
        string $modelType,
        int $modelId,
        string $action,
        ?string $reason = null
    ): void {
        self::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => request()->ip(),
            'model_type' => $modelType,
            'model_id' => $modelId,
            'action' => $action,
            'reason' => $reason,
            'accessed_at' => now(),
        ]);
    }
}
