<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosTransaction extends Model
{
    protected $fillable = ['items', 'subtotal', 'tax', 'total', 'payment_method', 'status'];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];
}
