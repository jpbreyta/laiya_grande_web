@extends('admin.inbox.layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    body, .poppins {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<div class="poppins max-w-4xl mx-auto p-8">
    <div class="bg-white rounded-2xl shadow-md border border-gray-200 p-8">
        <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
            <h1 class="text-2xl font-semibold text-gray-900">Compose Reply</h1>
            <a href="{{ route('admin.inbox.show', $message->id) }}"
               class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M15 19l-7-7 7-7" />
                </svg>
                Back to Message
            </a>
        </div>

        <div class="bg-gray-50 rounded-xl p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Replying to:</h3>
            <div class="space-y-2 text-sm text-gray-700">
                <p><strong>From:</strong> {{ $message->name }} ({{ $message->email }})</p>
                <p><strong>Subject:</strong> {{ $message->subject }}</p>
                <p><strong>Received:</strong> {{ $message->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.inbox.reply', $message->id) }}" class="space-y-8">
            @csrf

            <div>
                <label for="to_email" class="block text-sm font-medium text-gray-700 mb-2">To:</label>
                <input type="email" id="to_email" value="{{ $message->email }}" readonly
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 shadow-sm">
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject:</label>
                <input type="text" id="subject" name="subject" value="Re: {{ $message->subject }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm"
                       required>
            </div>

            <div>
                <label for="reply_content" class="block text-sm font-medium text-gray-700 mb-2">Message:</label>
                <textarea id="reply_content" name="reply_content" rows="12" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm resize-none"
                          placeholder="Type your reply message here...">Dear {{ $message->name }},

Thank you for your message. {{ $message->message }}

Best regards,
Laiya Grande Beach Resort Team</textarea>
            </div>

            <div class="flex gap-4 pt-6 border-t border-gray-100">
                <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-teal-600 to-teal-700 text-white font-medium shadow-sm hover:from-teal-700 hover:to-teal-800 transition">
                    Send Reply
                </button>
                <a href="{{ route('admin.inbox.show', $message->id) }}"
                   class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-gray-400 to-gray-500 text-white font-medium shadow-sm hover:from-gray-500 hover:to-gray-600 transition">
                    Cancel
                </a>
            </div>
        </form>

        <div class="mt-10 pt-6 border-t border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Original Message:</h3>
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="prose max-w-none">
                    <p class="whitespace-pre-line text-gray-700">{{ $message->message }}</p>
                </div>
                @if ($message->phone)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600"><strong>Phone:</strong> {{ $message->phone }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const textarea = document.getElementById('reply_content');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
    textarea.style.height = textarea.scrollHeight + 'px';
</script>
@endsection
