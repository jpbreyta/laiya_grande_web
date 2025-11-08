@extends('admin.inbox.layouts.app')

@section('content')
    <!-- Preview pane (static, no <aside>) -->
    <div class="flex-1 p-6 overflow-y-auto bg-gray-50">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Message Details</h2>
                <div class="flex gap-2">
                    @if ($message->status !== 'replied')
                        <a href="{{ route('admin.inbox.compose-reply', $message->id) }}"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            Reply via Email
                        </a>
                        <form method="POST" action="{{ route('admin.inbox.mark-replied', $message->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Mark as Replied
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.inbox.destroy', $message->id) }}" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete this message?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Message Header -->
            <div class="border-b border-gray-200 pb-4 mb-6">
                <div class="flex items-start gap-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&background=2C5F5F&color=fff"
                        class="w-12 h-12 rounded-full" alt="avatar">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-lg font-medium text-gray-900">{{ $message->name }}</h3>
                            <span class="text-sm text-gray-500">{{ $message->email }}</span>
                        </div>
                        <div class="text-sm text-gray-600 mb-2">
                            <strong>Subject:</strong> {{ $message->subject }}
                        </div>
                        <div class="text-sm text-gray-500">
                            <strong>Received:</strong> {{ $message->created_at->format('F j, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="prose max-w-none">
                <p class="whitespace-pre-line">{{ $message->message }}</p>
            </div>

            <!-- Contact Information -->
            @if ($message->phone)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Contact Information</h4>
                    <p class="text-sm text-gray-600"><strong>Phone:</strong> {{ $message->phone }}</p>
                </div>
            @endif

            <!-- Message Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span><strong>Status:</strong>
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium
                            @if ($message->status === 'unread') bg-red-100 text-red-800
                            @elseif($message->status === 'read') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($message->status) }}
                        </span>
                    </span>
                    @if ($message->read_at)
                        <span><strong>Read at:</strong> {{ $message->read_at->format('M j, Y g:i A') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
