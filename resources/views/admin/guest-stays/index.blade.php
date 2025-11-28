@extends('admin.layouts.app')

@php
    $pageTitle = 'Guest Stays Management';
    $currentStatus = request('status', 'all');
@endphp

@section('content')
    <section class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Header & Controls -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h1>

                <div class="flex gap-2">
                    <!-- Export Dropdown -->
                    <div class="relative group">
                        <button
                            class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 flex items-center gap-2">
                            <i class="fas fa-download"></i> Export <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block z-50 border border-gray-100">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel
                                (csv)</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print /
                                PDF</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Tabs -->
            <div class="mb-6 overflow-x-auto">
                <nav class="flex space-x-2 bg-white p-1 rounded-xl shadow-sm border border-gray-200">
                    @php
                        $tabs = [
                            'all' => 'All',
                            'checked-in' => 'Checked-in',
                            'checked-out' => 'Checked-out',
                        ];
                    @endphp
                    @foreach ($tabs as $key => $label)
                        <a href="{{ route('admin.guest-stays.index', ['status' => $key]) }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $currentStatus === $key ? 'bg-teal-600 text-white shadow' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Search and Entries Controls -->
            <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Show</label>
                    <select id="entriesSelect"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                    <label class="text-sm text-gray-600">entries</label>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Search:</label>
                    <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Search..."
                        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>
            </div>

            @if (session('success'))
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                @if ($guestStays->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 text-left text-gray-700 uppercase text-xs font-bold tracking-wider">
                                    <th class="py-3 px-4">Reservation #</th>
                                    <th class="py-3 px-4">Guest Name</th>
                                    <th class="py-3 px-4">Room</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4">Check-in Time</th>
                                    <th class="py-3 px-4">Check-out Time</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($guestStays as $stay)
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                        <td class="py-3 px-4 text-gray-700 font-medium">
                                            {{ $stay->booking->reservation_number ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 font-medium text-gray-900">{{ $stay->guest_name }}</td>
                                        <td class="py-3 px-4 text-gray-700">{{ $stay->room->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4">
                                            @php
                                                $statusStyles = [
                                                    'checked-in' =>
                                                        'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
                                                    'checked-out' =>
                                                        'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200',
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $statusStyles[$stay->status] ?? 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $stay->status === 'checked-in' ? 'bg-green-500' : 'bg-blue-500' }}"></span>
                                                {{ ucfirst($stay->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">{{ $stay->check_in_time?->format('M d, Y H:i') ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 px-4">{{ $stay->check_out_time?->format('M d, Y H:i') ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.guest-stays.show', $stay->id) }}"
                                                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                                                    View
                                                </a>
                                                @if ($stay->status === 'checked-in')
                                                    <button onclick="checkoutGuest({{ $stay->id }})"
                                                        class="bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-red-700 hover:to-red-800 transform hover:scale-105 transition-all duration-200">
                                                        Checkout
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 px-4 pb-4">
                        {{ $guestStays->appends(['status' => $currentStatus, 'search' => request('search'), 'per_page' => request('per_page', 10)])->links() }}
                    </div>
                @else
                    <div class="py-6 px-4 text-center text-gray-500">
                        <p class="text-lg mb-2">No {{ $currentStatus == 'all' ? '' : $currentStatus }} guests found.</p>
                        <p class="text-sm text-gray-400">Guests will appear here after they scan their QR codes and check
                            in.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <script>
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const entriesSelect = document.getElementById('entriesSelect');

        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                updateUrl();
            }, 500);
        });

        entriesSelect.addEventListener('change', function() {
            updateUrl();
        });

        function updateUrl() {
            const url = new URL(window.location.href);
            const search = searchInput.value;
            const perPage = entriesSelect.value;

            if (search) {
                url.searchParams.set('search', search);
            } else {
                url.searchParams.delete('search');
            }

            url.searchParams.set('per_page', perPage);
            url.searchParams.set('page', 1); // Reset to first page

            window.location.href = url.toString();
        }

        function checkoutGuest(guestStayId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This guest will be checked out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, checkout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('admin/guest-stays') }}/${guestStayId}/checkout`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Checked Out!',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: data.message,
                                    icon: 'error',
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Oops!',
                                text: 'An error occurred while processing checkout.',
                                icon: 'error',
                            });
                        });
                }
            });
        }
    </script>
@endsection
