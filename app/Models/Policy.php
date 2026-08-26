<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_type',
        'code',
        'title',
        'description',
        'version',
        'is_active',
        'effective_at',
        'sort_order',
    ];

    protected $casts = [
        'version' => 'integer',
        'is_active' => 'boolean',
        'effective_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('policy_type', $type);
    }
}
