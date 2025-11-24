<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        $contactMessage = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread',
        ]);

        // Create notification for admin
        \App\Models\Notification::create([
            'type' => 'contact',
            'title' => 'New Contact Message',
            'message' => "New message from {$request->name}: {$request->subject}",
            'data' => [
                'contact_id' => $contactMessage->id,
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
            ],
            'read' => false,
        ]);

        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
