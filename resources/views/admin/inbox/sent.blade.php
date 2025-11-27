@extends('admin.inbox.layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body,
        .poppins {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>

    <div class="poppins min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 px-6 py-10">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 mb-8 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">
                <i class="fas fa-paper-plane text-blue-600 mr-2"></i> Sent Messages
            </h1>
            <div class="flex gap-3 items-center text-gray-600">
                <button onclick="location.reload()" class="p-2 rounded-full hover:bg-gray-100 transition" title="Refresh">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v6h6M20 20v-6h-6M4 10a9 9 0 0114.32-6.36M20 14a9 9 0 01-14.32 6.36" />
                    </svg>
                </button>

                <button onclick="deleteSelected()" class="p-2 rounded-full hover:bg-gray-100 transition"
                    title="Delete Selected">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2m3 0h6" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8">
            @if ($messages->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach ($messages as $message)
                        <li class="mail-item group p-5 flex items-start gap-4 rounded-lg transition hover:bg-gray-50">
                            <input type="checkbox" class="mt-2 message-checkbox rounded border-gray-300 focus:ring-0"
                                value="{{ $message->id }}" />

                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&background=2C5F5F&color=fff"
                                class="w-12 h-12 rounded-full ring-2 ring-gray-200 shadow-sm" alt="avatar">

                            <div class="flex-1 cursor-pointer"
                                onclick="window.location.href='{{ route('admin.inbox.show', $message->id) }}'">
                                <div class="flex items-center gap-2">
                                    <div class="font-medium text-gray-900">To: {{ $message->name }}</div>
                                    <div class="text-xs text-gray-500 italic">&lt;{{ $message->email }}&gt;</div>
                                    <div class="ml-auto text-xs text-gray-400">
                                        {{ $message->replied_at ? $message->replied_at->format('M j, g:i A') : $message->created_at->format('M j') }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-700 mt-1 font-medium">
                                    <span class="text-gray-500">Re:</span> {{ $message->reply_subject ?? $message->subject }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $message->reply_content ? Str::limit($message->reply_content, 100) : 'No reply content saved' }}
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Replied</span>
                                    @if($message->reply_content)
                                        <span class="text-xs text-gray-400">
                                            <i class="fas fa-check-circle text-green-500"></i> Reply saved
                                        </span>
                                    @endif
                                </div>
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
                    <h3 class="mt-4 text-base font-semibold text-gray-800">No sent messages</h3>
                    <p class="mt-1 text-sm text-gray-500">No replies have been sent yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.mail-item').forEach(item => {
            item.addEventListener('click', () => {
                document.querySelectorAll('.mail-item').forEach(i => i.classList.remove('bg-gray-50'));
                item.classList.add('bg-gray-50');
            });
        });

        function deleteSelected() {
            const selected = document.querySelectorAll('.message-checkbox:checked');
            if (selected.length === 0) {
                alert('No messages selected.');
                return;
            }
            const ids = Array.from(selected).map(cb => cb.value);
            if (confirm(`Delete ${ids.length} selected message(s)?`)) {
                alert(`Messages with IDs [${ids.join(', ')}] deleted (placeholder).`);
            }
        }
    </script>
@endsection
