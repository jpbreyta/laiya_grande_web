@extends('admin.inbox.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Compose Reply</h1>
                <a href="{{ route('admin.inbox.show', $message->id) }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Back to Message
                </a>
            </div>

            <!-- Original Message Summary -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Replying to:</h3>
                <div class="space-y-2 text-sm">
                    <p><strong>From:</strong> {{ $message->name }} ({{ $message->email }})</p>
                    <p><strong>Subject:</strong> {{ $message->subject }}</p>
                    <p><strong>Received:</strong> {{ $message->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                <div class="mt-3 p-3 bg-white rounded border-l-4 border-blue-500">
                    <p class="text-gray-700 whitespace-pre-line">{{ Str::limit($message->message, 200) }}</p>
                    @if (strlen($message->message) > 200)
                        <p class="text-xs text-gray-500 mt-1">... (see full message below)</p>
                    @endif
                </div>
            </div>

            <!-- Reply Form -->
            <form method="POST" action="{{ route('admin.inbox.reply', $message->id) }}">
                @csrf

                <div class="space-y-6">
                    <!-- To Field (Read-only) -->
                    <div>
                        <label for="to_email" class="block text-sm font-medium text-gray-700 mb-2">To:</label>
                        <input type="email" id="to_email" value="{{ $message->email }}" readonly
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                    </div>

                    <!-- Subject Field -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject:</label>
                        <input type="text" id="subject" name="subject" value="Re: {{ $message->subject }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <!-- Reply Content -->
                    <div>
                        <label for="reply_content" class="block text-sm font-medium text-gray-700 mb-2">Message:</label>
                        <textarea id="reply_content" name="reply_content" rows="12" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Type your reply message here...">Dear {{ $message->name }},

Thank you for your message. {{ $message->message }}

Best regards,
Laiya Grande Beach Resort Team</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-medium">
                            Send Reply
                        </button>
                        <a href="{{ route('admin.inbox.show', $message->id) }}"
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-medium">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>

            <!-- Full Original Message -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Original Message:</h3>
                <div class="bg-gray-50 rounded-lg p-4">
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
        // Auto-resize textarea
        const textarea = document.getElementById('reply_content');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Set initial height
        textarea.style.height = textarea.scrollHeight + 'px';
    </script>
@endsection
