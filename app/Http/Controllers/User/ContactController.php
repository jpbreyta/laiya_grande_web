<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\ContactSubject;
use App\Services\Communication\StaffNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function __construct(
        private readonly StaffNotificationService $notifications
    ) {
    }

    /**
     * Store a public contact message after validating its active subject.
     */
    public function store(ContactMessageRequest $request): RedirectResponse
    {
        $rateKey = 'contact:' . $request->ip() . ':' . mb_strtolower($request->string('email')->toString());

        if (RateLimiter::tooManyAttempts($rateKey, 5)) {
            throw ValidationException::withMessages([
                'message' => 'Too many messages were submitted. Please try again later.',
            ]);
        }

        $subject = $this->resolveSubject($request);
        RateLimiter::hit($rateKey, 3600);

        $contactMessage = ContactMessage::create([
            'customer_id' => null,
            'name' => trim($request->string('name')->toString()),
            'email' => mb_strtolower(trim($request->string('email')->toString())),
            'phone' => $request->filled('phone') ? trim($request->string('phone')->toString()) : null,
            'contact_subject_id' => $subject->id,
            'message' => trim($request->string('message')->toString()),
            'status' => 'unread',
        ]);

        $this->notifications->notify(
            type: 'contact',
            title: 'New Contact Message',
            message: "New message from {$contactMessage->name}: {$subject->classification}",
            data: [
                'contact_id' => $contactMessage->id,
                'subject_id' => $subject->id,
            ]
        );

        return back()->with('success', 'Thank you for your message. We will get back to you soon.');
    }

    private function resolveSubject(ContactMessageRequest $request): ContactSubject
    {
        $subject = ContactSubject::query()
            ->active()
            ->when(
                $request->filled('contact_subject_id'),
                fn ($query) => $query->whereKey($request->integer('contact_subject_id')),
                fn ($query) => $query->where('classification', $request->string('subject')->toString())
            )
            ->first();

        if (! $subject) {
            throw ValidationException::withMessages([
                'subject' => 'Select a valid contact subject.',
            ]);
        }

        return $subject;
    }
}
