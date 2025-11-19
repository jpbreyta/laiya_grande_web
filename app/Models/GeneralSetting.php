<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'resort_name',
        'tagline',
        'contact_email',
        'contact_phone',
        'contact_address',
        'description',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_tripadvisor',
        'reception_hours_start',
        'reception_hours_end',
        'restaurant_hours_start',
        'restaurant_hours_end',
        'pool_hours_start',
        'pool_hours_end',
        'activities_hours_start',
        'activities_hours_end',
        'currency',
        'date_format',
        'time_format',
        'logo_path',
        'favicon_path',
    ];
}
