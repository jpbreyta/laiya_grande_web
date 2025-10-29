@extends('admin.layouts.app')

@section('content')
<section class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Bookings Management</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm font-semibold">
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Guest Name</th>
                        <th class="py-3 px-4">Room</th>
                        <th class="py-3 px-4">Check-in</th>
                        <th class="py-3 px-4">Check-out</th>
                        <th class="py-3 px-4">Total Price</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 text-gray-700">{{ $booking->id }}</td>
                            <td class="py-3 px-4">{{ $booking->firstname }} {{ $booking->lastname }}</td>
                            <td class="py-3 px-4">{{ $booking->room->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-green-600 font-semibold">₱{{ number_format($booking->total_price, 2) }}</td>

                            <td class="py-3 px-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.booking.show', $booking->id) }}"
                                   class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
