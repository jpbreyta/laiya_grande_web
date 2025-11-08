@extends('admin.inbox.layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    body, .poppins {
        font-family: 'Poppins', sans-serif !important;
    }
</style>

<div class="min-h-screen w-full p-10 overflow-y-auto bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 poppins">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-10 transition hover:shadow-xl">
        
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">📩 Message Details</h2>
            <div class="flex gap-3">
                @if ($message->status !== 'replied')
                    <a href="{{ route('admin.inbox.compose-reply', $message->id) }}"
                        class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-teal-400 to-teal-500 text-white font-medium shadow-sm hover:from-teal-500 hover:to-teal-600 transition">
                        Reply via Email
                    </a>
                    <form method="POST" action="{{ route('admin.inbox.mark-replied', $message->id) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-indigo-400 to-indigo-500 text-white font-medium shadow-sm hover:from-indigo-500 hover:to-indigo-600 transition">
                            Mark as Replied
                        </button>
                    </form>
                @endif
                <form method="POST" action="{{ route('admin.inbox.destroy', $message->id) }}" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this message?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-pink-400 to-pink-500 text-white font-medium shadow-sm hover:from-pink-500 hover:to-pink-600 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="border-b border-gray-200 pb-6 mb-8">
            <div class="flex items-start gap-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&background=2C5F5F&color=fff"
                    class="w-16 h-16 rounded-full ring-2 ring-gray-200 shadow-md" alt="avatar">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $message->name }}</h3>
                        <span class="text-sm text-gray-500 italic">{{ $message->email }}</span>
                    </div>
                    <div class="text-sm text-gray-700 mb-2">
                        <strong class="font-medium">Subject:</strong> {{ $message->subject }}
                    </div>
                    <div class="text-xs text-gray-500">
                        <strong>Received:</strong> {{ $message->created_at->format('F j, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="prose max-w-none text-gray-800 leading-relaxed">
            <p class="whitespace-pre-line">{{ $message->message }}</p>
        </div>

        @if ($message->phone)
           <div class="mt-6 flex items-center gap-3 p-4 bg-white rounded-lg border border-gray-100 shadow-sm">
                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-teal-400 to-teal-500 text-white shadow">
                    📞
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-800">Contact Information</h4>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Phone:</span> {{ $message->phone }}
                    </p>
                </div>
            </div>
        @endif

        <div class="mt-10 pt-6 border-t border-gray-200 flex items-center gap-6 text-sm text-gray-600">
            <span>
                <strong>Status:</strong>
                <span class="px-2 py-1 rounded-full text-xs font-medium
                    @if ($message->status === 'unread') bg-red-100 text-red-700
                    @elseif($message->status === 'read') bg-yellow-100 text-yellow-700
                    @else bg-green-100 text-green-700 @endif">
                    {{ ucfirst($message->status) }}
                </span>
            </span>
            @if ($message->read_at)
                <span><strong>Read at:</strong> {{ $message->read_at->format('M j, Y g:i A') }}</span>
            @endif
        </div>
    </div>
</div>
@endsection
