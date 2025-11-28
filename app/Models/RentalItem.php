<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category', 'description', 'price', 'stock_quantity', 'is_available'];
    
    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
        'stock_quantity' => 'integer'
    ];
}