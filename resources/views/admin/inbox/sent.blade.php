@extends('admin.inbox.layouts.app')

@section('content')
    <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold text-gray-800">Sent Messages</h1>
    </div>
    <!-- Sent Sub-header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <section class="mt-2 border-r border-gray-200 overflow-y-auto">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center gap-3 text-sm">
                    <input type="checkbox" class="rounded" />
                    <button class="px-3 py-1 rounded hover:bg-gray-200 text-gray-700">Refresh</button>
                    <button class="px-3 py-1 rounded hover:bg-gray-200 text-gray-700">More</button>
                </div>
            </div>

            @if ($messages->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach ($messages as $message)
                        <li class="mail-item p-4 flex items-start gap-3 hover:bg-gray-50 cursor-pointer"
                            onclick="window.location.href='{{ route('admin.inbox.show', $message->id) }}'">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&background=2C5F5F&color=fff"
                                class="w-10 h-10 rounded-full" alt="avatar">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <div class="font-medium text-gray-900">{{ $message->name }}</div>
                                    <div class="text-xs text-gray-500 ml-2">
                                        <{{ $message->email }}>
                                    </div>
                                    <div class="ml-auto text-xs text-gray-400">{{ $message->created_at->format('M j') }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-700 mt-1">{{ Str::limit($message->subject, 50) }}</div>
                                <div class="text-xs text-gray-400 mt-2">{{ Str::limit($message->message, 80) }}</div>
                                <span
                                    class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full ml-2">Replied</span>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No sent messages</h3>
                    <p class="mt-1 text-sm text-gray-500">No replies have been sent yet.</p>
                </div>
            @endif
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        // Expand/collapse preview on small screens
        (function() {
            list.forEach(item => {
                item.addEventListener('click', () => {
                    document.querySelectorAll('.mail-item').forEach(i => i.classList.remove(
                        'bg-gray-50'));
                    item.classList.add('bg-gray-50');
                })
            })
        })();
    </script>
@endsection
