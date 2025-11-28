@extends('admin.layouts.app')

@php
    $pageTitle = 'Bookings Management';
    $currentStatus = request('status', 'all');
@endphp

@section('content')
    <section class="p-4 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <!-- Header & Controls -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h1>

                <div class="flex gap-2">
                    <!-- Import Button -->
                    <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow flex items-center gap-2">
                        <i class="fas fa-file-import"></i> Import CSV
                    </button>

                    <!-- Export Dropdown -->
                    <div class="relative group">
                        <button
                            class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 flex items-center gap-2">
                            <i class="fas fa-download"></i> Export <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block z-50 border border-gray-100">
                            <a href="{{ route('admin.booking.export-csv', ['status' => $currentStatus]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV</a>
                            <a href="{{ route('admin.booking.export-csv', ['status' => $currentStatus]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel (csv)</a>
                            <a href="{{ route('admin.booking.export-pdf', ['status' => $currentStatus]) }}" target="_blank"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print / PDF</a>
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
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'cancelled' => 'Cancelled',
                            'rejected' => 'Rejected',
                            'archived' => 'Archived',
                        ];
                    @endphp
                    @foreach ($tabs as $key => $label)
                        <a href="{{ route('admin.booking.index', ['status' => $key]) }}"
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
                    class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-700">&times;</button>
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs font-bold tracking-wider">
                                <th class="py-3 px-4">Ref #</th>
                                <th class="py-3 px-4">Guest Name</th>
                                <th class="py-3 px-4">Room</th>
                                <th class="py-3 px-4">Dates</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-xs font-mono text-gray-500">
                                        {{ $booking->reservation_number }}</td>

                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $booking->customer->firstname ?? 'Unknown' }}
                                            {{ $booking->customer->lastname ?? '' }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->customer->email ?? 'No email' }}
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $booking->room->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        <div>In: {{ $booking->check_in->format('M d') }}</div>
                                        <div>Out: {{ $booking->check_out->format('M d') }}</div>
                                    </td>
                                    <td class="py-3 px-4 font-bold text-emerald-600">
                                        ₱{{ number_format($booking->total_price, 2) }}</td>

                                    <td class="py-3 px-4">
                                        @php
                                            $colors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'rejected' => 'bg-gray-100 text-gray-800',
                                            ];
                                            $color = $colors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if ($currentStatus === 'archived')
                                                <button onclick="restoreBooking({{ $booking->id }})"
                                                    class="text-emerald-600 hover:text-emerald-900 text-xs font-bold uppercase"
                                                    title="Restore">Restore</button>
                                                <button onclick="forceDeleteBooking({{ $booking->id }})"
                                                    class="text-red-600 hover:text-red-900 text-xs font-bold uppercase ml-2"
                                                    title="Delete Forever">Delete</button>
                                            @else
                                                <a href="{{ route('admin.booking.show', $booking->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition"
                                                    title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.booking.edit', $booking->id) }}"
                                                    class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-lg transition"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button onclick="archiveBooking({{ $booking->id }})"
                                                    class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition"
                                                    title="Archive">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                            <p>No {{ $currentStatus == 'all' ? '' : $currentStatus }} bookings found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-4 pb-4">
                    {{ $bookings->appends(['status' => $currentStatus, 'search' => request('search'), 'per_page' => request('per_page', 10)])->links() }}
                </div>
            </div>
        </div>
    </section>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 flex">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Import Bookings</h3>
            <form action="{{ route('admin.booking.import-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload CSV File</label>
                    <input type="file" name="csv_file" accept=".csv" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-lg cursor-pointer">
                    <p class="text-xs text-gray-500 mt-2">Format: Firstname, Lastname, Email, Phone, RoomID, CheckIn,
                        CheckOut, Guests, Total, Status</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Upload &
                        Import</button>
                </div>
            </form>
        </div>
    </div>

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
            url.searchParams.set('page', 1);

            window.location.href = url.toString();
        }

        function archiveBooking(bookingId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This booking will be archived!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, archive it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/booking') }}/${bookingId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function restoreBooking(bookingId) {
            Swal.fire({
                title: 'Restore Booking?',
                text: "This booking will be restored!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, restore it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/booking') }}/${bookingId}/restore`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function forceDeleteBooking(bookingId) {
            Swal.fire({
                title: 'Permanently Delete?',
                text: "This action cannot be undone!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete permanently!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/booking') }}/${bookingId}/force-delete`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
