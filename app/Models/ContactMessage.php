<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    // Scope for read messages
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    // Scope for replied messages
    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    // Mark as replied
    public function markAsReplied()
    {
        $this->update(['status' => 'replied']);
    }
}
