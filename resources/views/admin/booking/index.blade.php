@extends('admin.layouts.app')

@php
    $pageTitle = 'Bookings Management';
@endphp

@section('content')
    <section class="p-2 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
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
                'button' => [
                    'type' => 'deleted',
                    'text' => 'Deleted Bookings',
                    'route' => 'admin.booking.deleted',
                    'color' => 'bg-gradient-to-r from-red-500 to to-red-700',
                ],
                
            ])


            @if (session('success'))
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-emerald-500 to-emerald-700 text-left text-white  uppercase text-xs font-bold tracking-wider">
                                <th class="py-3 px-4">
                                    <input type="checkbox"
                                        class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </th>
                                <th class="py-3 px-4">Guest Name</th>
                                <th class="py-3 px-4">Booking ID</th>
                                <th class="py-3 px-4">Room</th>
                                <th class="py-3 px-4">Check-in</th>
                                <th class="py-3 px-4">Check-out</th>
                                <th class="py-3 px-4">Total Price</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bookings as $booking)
                                <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200"
                                    style="cursor: pointer;"
                                    onclick="window.location.href='{{ route('admin.booking.show', $booking->id) }}'">
                                    <td class="py-3 px-4">
                                        <input type="checkbox"
                                            class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded"
                                            onclick="event.stopPropagation();">
                                    </td>
                                    <td class="py-3 px-4 font-medium text-gray-900">{{ $booking->firstname }}
                                        {{ $booking->lastname }}</td>
                                    <td class="py-3 px-4 text-gray-700 font-medium">{{ $booking->reservation_number }}</td>
                                    <td class="py-3 px-4 text-gray-700">{{ $booking->room->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4 text-gray-700">
                                        {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                                    <td class="py-3 px-4 text-gray-700">
                                        {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                                    <td
                                        class="py-3 px-4 font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">
                                        ₱{{ number_format($booking->total_price, 2) }}</td>

                                    <td class="py-3 px-4">
                                        @php
                                            $statusStyles = [
                                                'pending' =>
                                                    'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200',
                                                'confirmed' =>
                                                    'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
                                                'cancelled' =>
                                                    'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $booking->status === 'confirmed' ? 'bg-green-500' : ($booking->status === 'pending' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.booking.edit', $booking->id) }}"
                                                class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200"
                                                onclick="event.stopPropagation();">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-6 px-4 text-center text-gray-500">
                                        No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
