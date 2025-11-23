<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'otp_code',
        'email_sent',
        'sms_sent',
        'expires_at',
        'used',
    ];
}
