<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSubject extends Model
{
    protected $fillable = [
        'classification'
    ];

    // Relationship with ContactMessage
    public function contactMessages()
    {
        return $this->hasMany(ContactMessage::class);
    }
}
