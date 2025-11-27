@extends('admin.inbox.layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    body, .poppins {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<div class="poppins max-w-4xl mx-auto p-8">
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
        <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
            <h1 class="text-2xl font-semibold text-gray-900">Compose New Message</h1>
            <a href="{{ route('admin.inbox.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-100 text-gray-700 font-medium hover:bg-gray-200 transition"
               title="Back to Inbox">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
        </div>

        <form method="POST" action="{{ route('admin.inbox.store') }}" class="space-y-8">
            @csrf

            <div>
                <label for="to_email" class="block text-sm font-medium text-gray-700 mb-2">Recipient Email</label>
                <input type="email" id="to_email" name="to_email"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md bg-white text-gray-800 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm"
                    placeholder="recipient@example.com" required>
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Label</label>
                <select id="subject" name="subject"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md bg-white text-gray-800 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm"
                    required>
                    <option value="">Select a label</option>
                    <option value="Reservation Inquiry">Reservation Inquiry</option>
                    <option value="Booking Assistance">Booking Assistance</option>
                    <option value="General Question">General Question</option>
                    <option value="Feedback">Feedback</option>
                    <option value="Complaint">Complaint</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea id="message" name="message" rows="10" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-md bg-white text-gray-800 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition shadow-sm resize-none"
                    placeholder="Type your message here..."></textarea>
            </div>

            <div class="flex gap-4 pt-6 border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-2.5 rounded-md bg-teal-600 text-white font-medium shadow-sm hover:bg-teal-700 transition">
                    Send Message
                </button>
                <a href="{{ route('admin.inbox.index') }}"
                    class="px-6 py-2.5 rounded-md bg-gray-500 text-white font-medium shadow-sm hover:bg-gray-600 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const textarea = document.getElementById('message');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
    textarea.style.height = textarea.scrollHeight + 'px';
</script>
@endsection
