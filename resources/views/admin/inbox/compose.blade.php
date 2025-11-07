@extends('admin.inbox.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Compose New Message</h1>
                <a href="{{ route('admin.inbox.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Back to Inbox
                </a>
            </div>

            <!-- Compose Form -->
            <form method="POST" action="{{ route('admin.inbox.store') }}">
                @csrf

                <div class="space-y-6">
                    <!-- To Field -->
                    <div>
                        <label for="to_email" class="block text-sm font-medium text-gray-700 mb-2">To:</label>
                        <input type="email" id="to_email" name="to_email"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="recipient@example.com" required>
                    </div>

                    <!-- Subject Field -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject:</label>
                        <input type="text" id="subject" name="subject"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter subject" required>
                    </div>

                    <!-- Message Content -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message:</label>
                        <textarea id="message" name="message" rows="12" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Type your message here..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 pt-4 border-t border-gray-200">
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-medium">
                            Send Message
                        </button>
                        <a href="{{ route('admin.inbox.index') }}"
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 font-medium">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Auto-resize textarea
        const textarea = document.getElementById('message');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });

        // Set initial height
        textarea.style.height = textarea.scrollHeight + 'px';
    </script>
@endsection
