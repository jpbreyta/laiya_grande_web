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
        $messages = ContactMessage::notArchived()->latest()->paginate(10);
        $unreadCount = ContactMessage::unread()->count();
        return view('admin.inbox.index', compact('messages', 'unreadCount'));

    }

    public function allMail()
    {
        $messages = ContactMessage::latest()->paginate(10);
        $unreadCount = ContactMessage::unread()->count();
        return view('admin.inbox.all-mail', compact('messages', 'unreadCount'));
    }

    public function filterByLabel($label)
    {
        $messages = ContactMessage::notArchived()
            ->whereHas('contactSubject', function($query) use ($label) {
                $query->where('classification', $label);
            })
            ->latest()
            ->paginate(10);
        $unreadCount = ContactMessage::unread()->count();
        $currentLabel = $label;
        
        return view('admin.inbox.label-filter', compact('messages', 'unreadCount', 'currentLabel'));
    }

    public function sent()
    {
        $messages = ContactMessage::replied()->latest()->paginate(10);
        $unreadCount = ContactMessage::unread()->count();
        return view('admin.inbox.sent', compact('messages', 'unreadCount'));
    }

    public function compose()
    {
        $unreadCount = ContactMessage::unread()->count();
        return view('admin.inbox.compose', compact('unreadCount'));
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
        $unreadCount = ContactMessage::unread()->count();

        // Mark as read if not already read
        if ($message->status === 'unread') {
            $message->markAsRead();
        }

        return view('admin.inbox.show', compact('message', 'unreadCount'));
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
        $unreadCount = ContactMessage::unread()->count();
        return view('admin.inbox.compose-reply', compact('message', 'unreadCount'));
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

        // Save reply content and mark as replied
        $message->update([
            'status' => 'replied',
            'reply_subject' => $request->subject,
            'reply_content' => $request->reply_content,
            'replied_at' => now()
        ]);

        return redirect()->route('admin.inbox.show', $message->id)->with('success', 'Reply sent successfully and message marked as replied.');
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.inbox.index')->with('success', 'Message deleted successfully.');
    }

    public function archived()
    {
        $messages = ContactMessage::archived()->latest()->paginate(10);
        $unreadCount = ContactMessage::unread()->count();
        return view('admin.inbox.archived', compact('messages', 'unreadCount'));
    }

    public function archive($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->archive();

        return redirect()->back()->with('success', 'Message archived successfully.');
    }

    public function unarchive($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->unarchive();

        return redirect()->back()->with('success', 'Message restored from archive.');
    }

    public function bulkArchive(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id'
        ]);

        ContactMessage::whereIn('id', $request->ids)->update(['archived_at' => now()]);

        return redirect()->back()->with('success', count($request->ids) . ' message(s) archived successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_messages,id'
        ]);

        ContactMessage::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' message(s) deleted successfully.');
    }
}
