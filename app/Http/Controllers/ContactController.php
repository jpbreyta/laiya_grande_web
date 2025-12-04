<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // Find or create the contact subject
        $contactSubject = \App\Models\ContactSubject::firstOrCreate(
            ['classification' => $validated['subject']]
        );

        // Replace subject with contact_subject_id
        unset($validated['subject']);
        $validated['contact_subject_id'] = $contactSubject->id;

        $contactMessage = ContactMessage::create(array_merge($validated, ['status' => 'unread']));

        // Create notification for admin
        \App\Models\Notification::create([
            'type' => 'contact',
            'title' => 'New Contact Message',
            'message' => "New message from {$request->name}: {$contactSubject->classification}",
            'data' => [
                'contact_id' => $contactMessage->id,
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $contactSubject->classification,
            ],
            'read' => false,
        ]);

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
