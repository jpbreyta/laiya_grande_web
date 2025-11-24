<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InboxController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);
        $unreadCount = ContactMessage::unread()->count();
        return view('admin.inbox.index', compact('messages', 'unreadCount'));

    }

    public function sent()
    {
        $messages = ContactMessage::replied()->latest()->paginate(10);
        $unreadCount = ContactMessage::unread()->count();
        return view('admin.inbox.sent', compact('messages', 'unreadCount'));
    }

    public function compose()
    {
        return view('admin.inbox.compose');
    }

    public function store(Request $request)
    {
        $request->validate([
            'to_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // Send email
        Mail::raw($request->message, function ($mail) use ($request) {
            $mail->to($request->to_email)
                ->subject($request->subject)
                ->from(config('mail.from.address'), config('mail.from.name'));
        });

        return redirect()->route('admin.inbox.sent')->with('success', 'Message sent successfully.');
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);

        // Mark as read if not already read
        if ($message->status === 'unread') {
            $message->markAsRead();
        }

        return view('admin.inbox.show', compact('message'));
    }

    public function markAsReplied($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->markAsReplied();

        return redirect()->back()->with('success', 'Message marked as replied.');
    }

    public function composeReply($id)
    {
        $message = ContactMessage::findOrFail($id);
        return view('admin.inbox.compose-reply', compact('message'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'reply_content' => 'required|string|min:10',
        ]);

        $message = ContactMessage::findOrFail($id);

        // Send email reply
        Mail::to($message->email)->send(new \App\Mail\ContactReply($message, $request->subject, $request->reply_content));

        // Mark as replied
        $message->markAsReplied();

        return redirect()->route('admin.inbox.show', $message->id)->with('success', 'Reply sent successfully and message marked as replied.');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.inbox.index')->with('success', 'Message deleted successfully.');
    }
}
