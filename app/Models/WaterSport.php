<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_details',
        'min_participants',
        'duration_minutes',
    ];
}

