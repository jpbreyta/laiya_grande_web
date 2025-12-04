<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $table = 'otps';
    
    protected $fillable = [
        'user_id',
        'type',
        'otp_code',
        'email_sent',
        'sms_sent',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'email_sent' => 'boolean',
        'sms_sent' => 'boolean',
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];
}
