<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

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

    protected $casts = [
        'singleton_key' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(fn (self $setting) => $setting->singleton_key = true);
    }

    public static function instance(): self
    {
        return static::query()->firstOrCreate(['singleton_key' => true]);
    }
}
