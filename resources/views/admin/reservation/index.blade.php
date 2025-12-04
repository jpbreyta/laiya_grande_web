@extends('admin.layouts.app')

@php
    $pageTitle = 'Reservations Management';
    $currentStatus = request('status', 'all');
@endphp

@section('content')
    <section class="p-4 bg-gray-50 min-h-screen">
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
                        <a href="{{ route('admin.reservation.export-csv', ['status' => $currentStatus]) }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-md">
                            <i class="fas fa-file-csv mr-2"></i>Export CSV
                        </a>
                        <a href="{{ route('admin.reservation.export-pdf', ['status' => $currentStatus]) }}"
                            target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-md">
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
                            <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $currentStatus === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="paid" {{ $currentStatus === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ $currentStatus === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="archived" {{ $currentStatus === 'archived' ? 'selected' : '' }}>Archived</option>
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
                    class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-700">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-700">&times;</button>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
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
                                <th class="py-3 px-4">Dates</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reservations as $reservation)
                                <tr class="hover:bg-gray-50 transition-colors" style="cursor: pointer;"
                                    onclick="window.location.href='{{ route('admin.reservation.show', $reservation->id) }}'">

                                    <td class="py-3 px-4 text-xs font-semibold text-gray-700">
                                        {{ $reservation->id }}
                                    </td>
                                    <td class="py-3 px-4 text-xs font-mono text-gray-500">
                                        {{ $reservation->reservation_number ?? $reservation->id }}
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $reservation->customer->firstname ?? 'Unknown' }}
                                        </div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $reservation->customer->lastname ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="py-3 px-4">
                                        <div class="text-sm text-gray-700">
                                            {{ $reservation->customer->email ?? 'No email' }}
                                        </div>
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        {{ $reservation->room->name ?? 'N/A' }}
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        <div>In: {{ \Carbon\Carbon::parse($reservation->check_in)->format('M d, Y') }}</div>
                                        <div>Out: {{ \Carbon\Carbon::parse($reservation->check_out)->format('M d, Y') }}</div>
                                    </td>

                                    <td class="py-3 px-4 font-bold text-emerald-600">
                                        ₱{{ number_format($reservation->total_price, 2) }}
                                    </td>

                                    <td class="py-3 px-4">
                                        @php
                                            $colors = [
                                                'pending' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                                'confirmed' => 'bg-green-100 text-green-800 border border-green-200',
                                                'paid' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                                'cancelled' => 'bg-red-100 text-red-800 border border-red-200',
                                            ];
                                            $color = $colors[$reservation->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center" onclick="event.stopPropagation();">
                                        <div class="flex items-center justify-center gap-2">
                                            @if ($currentStatus === 'archived')
                                                <button onclick="restoreReservation({{ $reservation->id }})"
                                                    class="text-emerald-600 hover:text-emerald-900 text-xs font-bold uppercase"
                                                    title="Restore">Restore</button>
                                                <button onclick="forceDeleteReservation({{ $reservation->id }})"
                                                    class="text-red-600 hover:text-red-900 text-xs font-bold uppercase ml-2"
                                                    title="Delete Forever">Delete</button>
                                            @else
                                                <a href="{{ route('admin.reservation.show', $reservation->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition"
                                                    title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <button onclick="archiveReservation({{ $reservation->id }})"
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
                                    <td colspan="10" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                            <p>No {{ $currentStatus == 'all' ? '' : $currentStatus }} reservations found.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-4 pb-4">
                    {{ $reservations->appends(['status' => $currentStatus, 'search' => request('search'), 'per_page' => request('per_page', 10)])->links() }}
                </div>
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

        function archiveReservation(reservationId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This reservation will be archived!",
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
                    form.action = `{{ url('admin/reservation') }}/${reservationId}`;

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

        function restoreReservation(reservationId) {
            Swal.fire({
                title: 'Restore Reservation?',
                text: "This reservation will be restored!",
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
                    form.action = `{{ url('admin/reservation') }}/${reservationId}/restore`;

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

        function forceDeleteReservation(reservationId) {
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
                    form.action = `{{ url('admin/reservation') }}/${reservationId}/force-delete`;

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
