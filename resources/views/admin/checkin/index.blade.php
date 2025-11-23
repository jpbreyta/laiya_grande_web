@extends('admin.layouts.app')

@php
    $pageTitle = 'Checked-in Guests Management';
@endphp

@section('content')
    <section class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">

            @include('admin.components.table-controls', [
                'more' => [
                    ['label' => 'Export as CSV', 'route' => '#'],
                    ['label' => 'Export as Excel', 'route' => '#'],
                    ['label' => 'Export as PDF', 'route' => '#'],
                ],
                'title' => $pageTitle,
                'search' => true,
                'entries' => true,
            ])

            @if (session('success'))
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                @if ($checkedInBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 text-left text-gray-700 uppercase text-xs font-bold tracking-wider">
                                    <th class="py-3 px-4">Reservation #</th>
                                    <th class="py-3 px-4">Guest Name</th>
                                    <th class="py-3 px-4">Room</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($checkedInBookings as $booking)
                                    <tr
                                        class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                        <td class="py-3 px-4 text-gray-700 font-medium">{{ $booking->reservation_number }}
                                        </td>
                                        <td class="py-3 px-4 font-medium text-gray-900">{{ $booking->firstname }}
                                            {{ $booking->lastname }}</td>
                                        <td class="py-3 px-4 text-gray-700">{{ $booking->room->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4">
                                            @php
                                                $statusStyles = [
                                                    'active' =>
                                                        'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
                                                    'completed' =>
                                                        'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200',
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $booking->status === 'active' ? 'bg-green-500' : ($booking->status === 'completed' ? 'bg-blue-500' : 'bg-gray-500') }}"></span>
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.checkin.show', $booking->id) }}"
                                                    class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 px-4 pb-4">
                        {{ $checkedInBookings->links() }}
                    </div>
                @else
                    <div class="py-6 px-4 text-center text-gray-500">
                        <p class="text-lg mb-2">No checked-in guests at the moment.</p>
                        <p class="text-sm text-gray-400">Guests will appear here after they scan their QR codes and check
                            in.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <script>
        function checkoutGuest(bookingId) {
            if (confirm('Are you sure you want to check out this guest?')) {
                fetch(`{{ url('admin/checkin') }}/${bookingId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            action: 'checkout'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while processing checkout.');
                    });
            }
        }
    </script>
@endsection
