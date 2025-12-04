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

                    <button id="archiveBtn"
                        class="relative group p-2.5 rounded-full bg-white border border-gray-200 shadow-sm 
                        hover:bg-gray-50 hover:scale-105 transition-all duration-200 ease-in-out
                        focus:outline-none focus:ring-2 focus:ring-gray-300 disabled:opacity-50"
                        title="Archive selected">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-gray-500 group-hover:text-gray-600 transition-colors" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span
                            class="absolute -bottom-9 left-1/2 -translate-x-1/2 
                                px-2 py-1 text-xs rounded-md bg-gray-800 text-white opacity-0 
                                group-hover:opacity-100 transition pointer-events-none">
                            Archive selected
                        </span>
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

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-wrap gap-3 mb-6 text-sm font-medium">
                <button data-filter="all"
                    class="filter-btn px-4 py-1.5 rounded-full bg-teal-100 text-teal-700 hover:bg-teal-100 hover:text-teal-700 transition">All</button>
                <button data-filter="read"
                    class="filter-btn px-4 py-1.5 rounded-full bg-gray-100 text-gray-700 hover:bg-teal-100 hover:text-teal-700 transition">Read</button>
                <button data-filter="unread"
                    class="filter-btn px-4 py-1.5 rounded-full bg-gray-100 text-gray-700 hover:bg-teal-100 hover:text-teal-700 transition">Unread</button>
            </div>

            @if ($messages->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach ($messages as $message)
                        <li data-status="{{ $message->status }}"
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
                                    @php
                                        $labelColors = [
                                            'Reservation Inquiry' => 'bg-blue-400',
                                            'Booking Assistance' => 'bg-green-400',
                                            'General Question' => 'bg-purple-400',
                                            'Feedback' => 'bg-yellow-400',
                                            'Complaint' => 'bg-red-400',
                                            'Other' => 'bg-gray-400',
                                        ];
                                        $labelColor = $labelColors[$message->subject] ?? 'bg-gray-400';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-gray-100 text-xs">
                                        <span class="w-2 h-2 rounded-full {{ $labelColor }}"></span>
                                        <span class="text-gray-700">{{ $message->subject }}</span>
                                    </span>
                                    <div class="ml-auto text-xs text-gray-400">{{ $message->created_at->format('M j') }}
                                    </div>
                                </div>
                                <div class="text-xs text-gray-500 mt-2">{{ Str::limit($message->message, 80) }}</div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const deleteBtn = document.getElementById('deleteBtn');
            const archiveBtn = document.getElementById('archiveBtn');
            const checkboxes = document.querySelectorAll('.message-checkbox');
            const filterBtns = document.querySelectorAll('.filter-btn');
            const mailItems = document.querySelectorAll('.mail-item');

            console.log('Filter buttons found:', filterBtns.length);
            console.log('Mail items found:', mailItems.length);

            function updateButtonStates() {
                const anySelected = Array.from(checkboxes).some(cb => cb.checked);
                if (anySelected) {
                    deleteBtn.classList.remove('icon-disabled');
                    archiveBtn.classList.remove('icon-disabled');
                } else {
                    deleteBtn.classList.add('icon-disabled');
                    archiveBtn.classList.add('icon-disabled');
                }
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateButtonStates));
            updateButtonStates();

            // Filter functionality
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filter = this.getAttribute('data-filter');
                    console.log('Filter clicked:', filter);
                    
                    // Update active button style
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-teal-100', 'text-teal-700');
                        b.classList.add('bg-gray-100', 'text-gray-700');
                    });
                    this.classList.remove('bg-gray-100', 'text-gray-700');
                    this.classList.add('bg-teal-100', 'text-teal-700');
                    
                    // Filter messages
                    let visibleCount = 0;
                    mailItems.forEach(item => {
                        const status = item.getAttribute('data-status');
                        
                        if (filter === 'all') {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else if (filter === 'read' && (status === 'read' || status === 'replied')) {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else if (filter === 'unread' && status === 'unread') {
                            item.style.display = 'flex';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    console.log('Visible messages:', visibleCount);
                });
            });
        });

        // Archive selected messages
        archiveBtn.addEventListener('click', () => {
            const selected = Array.from(document.querySelectorAll('.message-checkbox:checked'));
            if (selected.length === 0) return;

            const ids = selected.map(cb => cb.value);
            if (confirm(`Archive ${ids.length} selected message(s)?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.inbox.bulk-archive") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });

        // Delete selected messages
        deleteBtn.addEventListener('click', () => {
            const selected = Array.from(document.querySelectorAll('.message-checkbox:checked'));
            if (selected.length === 0) return;

            const ids = selected.map(cb => cb.value);
            if (confirm(`Delete ${ids.length} selected message(s)?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.inbox.bulk-delete") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    </script>
@endsection
