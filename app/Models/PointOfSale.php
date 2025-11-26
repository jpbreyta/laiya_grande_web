<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointOfSale extends Model
{
    use HasFactory;
    
    protected $table = 'point_of_sale'; // Explicitly define table name

    protected $fillable = [
        'guest_stay_id', 
        'item_name', 
        'item_type', 
        'quantity', 
        'price', 
        'total_amount', 
        'discount'
    ];

    // Relationship to the Guest (GuestStay)
    public function guestStay()
    {
        return $this->belongsTo(GuestStay::class, 'guest_stay_id');
    }
}