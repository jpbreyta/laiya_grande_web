@extends('admin.inbox.layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body,
        .poppins {
            font-family: 'Poppins', sans-serif !important;
        }

        .icon-disabled {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>

    <div class="poppins min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 px-6 py-10">

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <div class="flex items-start justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">
                        <i class="fas fa-inbox text-blue-600 mr-2"></i> Admin Inbox
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Manage and organize your messages</p>
                </div>

                <div class="flex gap-2 items-center">

                    <button onclick="location.reload()" class="p-2 rounded-full hover:bg-gray-100 transition"
                        title="Refresh">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v6h6M20 20v-6h-6M4 10a9 9 0 0114.32-6.36M20 14a9 9 0 01-14.32 6.36" />
                        </svg>
                    </button>


                    <button id="deleteBtn"
                        class="relative group p-2.5 rounded-full bg-white border border-gray-200 shadow-sm 
                        hover:bg-red-50 hover:scale-105 transition-all duration-200 ease-in-out
                        focus:outline-none focus:ring-2 focus:ring-red-300 disabled:opacity-50"
                        title="Delete selected">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-red-500 group-hover:text-red-600 transition-colors" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                                    01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6
                                    m2 0a2 2 0 00-2-2H9a2 2 0
                                    00-2 2m3 0h6" />
                        </svg>

                        <span
                            class="absolute -bottom-9 left-1/2 -translate-x-1/2 
                                px-2 py-1 text-xs rounded-md bg-gray-800 text-white opacity-0 
                                group-hover:opacity-100 transition pointer-events-none">
                            Delete selected
                        </span>
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 mb-6 text-sm font-medium">
                <button
                    class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-700 hover:bg-teal-100 hover:text-teal-700 transition">All</button>
                <button
                    class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-700 hover:bg-teal-100 hover:text-teal-700 transition">Read</button>
                <button
                    class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-700 hover:bg-teal-100 hover:text-teal-700 transition">Unread</button>
            </div>

            @if ($messages->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach ($messages as $message)
                        <li
                            class="mail-item group p-5 flex items-start gap-4 rounded-lg transition {{ $message->status === 'unread' ? 'bg-blue-50' : 'hover:bg-gray-50' }}">
                            <input type="checkbox" class="mt-2 rounded border-gray-300 focus:ring-0 message-checkbox"
                                value="{{ $message->id }}" />

                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&background=2C5F5F&color=fff"
                                class="w-12 h-12 rounded-full ring-2 ring-gray-200 shadow-sm" alt="avatar">

                            <div class="flex-1 cursor-pointer"
                                onclick="window.location.href='{{ route('admin.inbox.show', $message->id) }}'">
                                <div class="flex items-center gap-2">
                                    <div class="font-medium text-gray-900">{{ $message->name }}</div>
                                    <div class="text-xs text-gray-500 italic">&lt;{{ $message->email }}&gt;</div>
                                    <div class="ml-auto text-xs text-gray-400">{{ $message->created_at->format('M j') }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-700 mt-1 font-medium">{{ Str::limit($message->subject, 50) }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($message->message, 80) }}</div>
                                @if ($message->status === 'unread')
                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full ml-2 mt-2"></span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-8">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="mx-auto h-14 w-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2" />
                    </svg>
                    <h3 class="mt-4 text-base font-semibold text-gray-800">No messages</h3>
                    <p class="mt-1 text-sm text-gray-500">No contact messages have been received yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const deleteBtn = document.getElementById('deleteBtn');
        const checkboxes = document.querySelectorAll('.message-checkbox');

        function updateDeleteState() {
            const anySelected = Array.from(checkboxes).some(cb => cb.checked);
            if (anySelected) {
                deleteBtn.classList.remove('icon-disabled');
                deleteBtn.title = 'Delete selected';
            } else {
                deleteBtn.classList.add('icon-disabled');
                deleteBtn.title = 'Select messages to delete';
            }
        }

        checkboxes.forEach(cb => cb.addEventListener('change', updateDeleteState));
        updateDeleteState();

        deleteBtn.addEventListener('click', () => {
            const selected = Array.from(document.querySelectorAll('.message-checkbox:checked'));
            if (selected.length === 0) return;

            const ids = selected.map(cb => cb.value);
            if (confirm(`Delete ${ids.length} selected message(s)?`)) {
                alert(`Selected IDs: [${ids.join(', ')}]. Hook this up to your bulk delete route when available.`);
            }
        });
    </script>
@endsection
