@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Checked-in Guests</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            @if ($checkedInBookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Reservation #</th>
                                <th class="py-3 px-6 text-left">Guest Name</th>
                                <th class="py-3 px-6 text-left">Room</th>
                                <th class="py-3 px-6 text-left">Check-in Time</th>
                                <th class="py-3 px-6 text-left">Status</th>
                                <th class="py-3 px-6 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach ($checkedInBookings as $booking)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $booking->reservation_number }}
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $booking->guest_name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $booking->room->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-6 text-left">
                                        {{ \Carbon\Carbon::parse($booking->actual_check_in_time)->format('M d, Y H:i') }}</td>
                                    <td class="py-3 px-6 text-left">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-semibold
                                        @if ($booking->status === 'active') bg-green-100 text-green-800
                                        @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        @if (!$booking->actual_check_out_time)
                                            <button onclick="checkoutGuest({{ $booking->id }})"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                Check Out
                                            </button>
                                        @else
<span class="text-gray-500">Checked out at
   <td>{{ \Carbon\Carbon::parse($booking->actual_check_in_time)->format('M d, Y H:i') }}</td>
</span>

                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $checkedInBookings->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg">No checked-in guests at the moment.</p>
                    <p class="text-gray-400 mt-2">Guests will appear here after they scan their QR codes and check in.</p>
                </div>
            @endif
        </div>
    </div>

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
