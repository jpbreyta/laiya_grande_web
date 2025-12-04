@extends('admin.layouts.app')

@php
    $pageTitle = 'Guest Logs';
    $currentStatus = request('status', 'all');
@endphp

@section('content')
    <section class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h1>

                <div class="relative">
                    <button id="exportDropdownBtn" type="button"
                        class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow flex items-center gap-2">
                        <i class="fas fa-download"></i> Export <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="exportDropdown"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50 border border-gray-100">
                        <a href="{{ route('admin.guest-stays.export-csv', ['status' => $currentStatus]) }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-md">
                            <i class="fas fa-file-csv mr-2"></i>Export CSV
                        </a>
                        <a href="{{ route('admin.guest-stays.export-pdf', ['status' => $currentStatus]) }}" target="_blank"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-md">
                            <i class="fas fa-file-pdf mr-2"></i>Export PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="mb-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-4">
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
                        <label class="text-sm text-gray-600">Status:</label>
                        <select id="statusFilter"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>All</option>
                            <option value="checked-in" {{ $currentStatus === 'checked-in' ? 'selected' : '' }}>Checked-in</option>
                            <option value="checked-out" {{ $currentStatus === 'checked-out' ? 'selected' : '' }}>Checked-out</option>
                        </select>
                    </div>
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
                                <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs font-bold tracking-wider">
                                    <th class="py-3 px-4">ID</th>
                                    <th class="py-3 px-4">Ref #</th>
                                    <th class="py-3 px-4">First Name</th>
                                    <th class="py-3 px-4">Last Name</th>
                                    <th class="py-3 px-4">Email</th>
                                    <th class="py-3 px-4">Room</th>
                                    <th class="py-3 px-4">Check-in Time</th>
                                    <th class="py-3 px-4">Check-out Time</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($guestStays as $stay)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-xs font-semibold text-gray-700">
                                            {{ $stay->id }}</td>
                                        <td class="py-3 px-4 text-xs font-mono text-gray-500">
                                            {{ $stay->booking->reservation_number ?? 'N/A' }}</td>

                                        <td class="py-3 px-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $stay->booking->customer->firstname ?? 'Unknown' }}</div>
                                        </td>

                                        <td class="py-3 px-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $stay->booking->customer->lastname ?? '-' }}</div>
                                        </td>

                                        <td class="py-3 px-4">
                                            <div class="text-sm text-gray-700">{{ $stay->booking->customer->email ?? 'No email' }}</div>
                                        </td>

                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $stay->room->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">
                                            {{ $stay->check_in_time?->format('M d, Y H:i') ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 px-4 text-sm text-gray-700">
                                            {{ $stay->check_out_time?->format('M d, Y H:i') ?? '-' }}
                                        </td>

                                        <td class="py-3 px-4">
                                            @php
                                                $colors = [
                                                    'checked-in' => 'bg-green-100 text-green-800',
                                                    'checked-out' => 'bg-blue-100 text-blue-800',
                                                ];
                                                $color = $colors[$stay->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                {{ ucfirst($stay->status) }}
                                            </span>
                                        </td>

                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.guest-stays.show', $stay->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition"
                                                    title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if ($stay->status === 'checked-in')
                                                    <button onclick="checkoutGuest({{ $stay->id }})"
                                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition"
                                                        title="Checkout">
                                                        <i class="fas fa-sign-out-alt"></i>
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
                    <div class="mt-4 px-4 pb-4">
                        {{ $guestStays->appends(['status' => $currentStatus, 'search' => request('search'), 'per_page' => request('per_page', 10)])->links() }}
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs font-bold tracking-wider">
                                    <th class="py-3 px-4">ID</th>
                                    <th class="py-3 px-4">Ref #</th>
                                    <th class="py-3 px-4">First Name</th>
                                    <th class="py-3 px-4">Last Name</th>
                                    <th class="py-3 px-4">Email</th>
                                    <th class="py-3 px-4">Room</th>
                                    <th class="py-3 px-4">Check-in Time</th>
                                    <th class="py-3 px-4">Check-out Time</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="10" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                            <p>No {{ $currentStatus == 'all' ? '' : $currentStatus }} guest stays found.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <script>
        const searchInput = document.getElementById('searchInput');
        const entriesSelect = document.getElementById('entriesSelect');
        const statusFilter = document.getElementById('statusFilter');
        const exportDropdownBtn = document.getElementById('exportDropdownBtn');
        const exportDropdown = document.getElementById('exportDropdown');

        exportDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            exportDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!exportDropdown.contains(e.target) && e.target !== exportDropdownBtn) {
                exportDropdown.classList.add('hidden');
            }
        });

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

        statusFilter.addEventListener('change', function() {
            updateUrl();
        });

        function updateUrl() {
            const url = new URL(window.location.href);
            const search = searchInput.value;
            const perPage = entriesSelect.value;
            const status = statusFilter.value;

            if (search) {
                url.searchParams.set('search', search);
            } else {
                url.searchParams.delete('search');
            }

            url.searchParams.set('per_page', perPage);
            url.searchParams.set('status', status);
            url.searchParams.set('page', 1);

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
