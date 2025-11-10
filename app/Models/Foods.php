<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'food_category_id', 'no_of_pax'];

    public function category()
    {
        return $this->belongsTo(FoodCategory::class, 'food_category_id');
    }
}
