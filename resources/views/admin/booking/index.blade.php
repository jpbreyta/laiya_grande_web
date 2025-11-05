@extends('admin.layouts.app')

@section('content')
<section class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-gray-800 to-gray-600 mb-2">Bookings Management</h1>
        <p class="text-gray-600 mb-6">Manage and monitor all guest bookings. View details, edit information, and update booking status as needed.</p>

        @if(session('success'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 text-left text-gray-700 uppercase text-xs font-bold tracking-wider">
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

                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                <td class="py-3 px-4 text-gray-700 font-medium">{{ $booking->id }}</td>
                                <td class="py-3 px-4 font-medium text-gray-900">{{ $booking->firstname }} {{ $booking->lastname }}</td>
                                <td class="py-3 px-4 text-gray-700">{{ $booking->room->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 text-gray-700">{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                                <td class="py-3 px-4 text-gray-700">{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                                <td class="py-3 px-4 font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">₱{{ number_format($booking->total_price, 2) }}</td>

                            <td class="py-3 px-4">
                                @php
                                        $statusStyles = [
                                            'pending' => 'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200',
                                            'confirmed' => 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
                                            'cancelled' => 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200'
                                    ];
                                @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $statusStyles[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $booking->status === 'confirmed' ? 'bg-green-500' : ($booking->status === 'pending' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>

                            <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.booking.show', $booking->id) }}"
                                           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                                    View
                                </a>
                                        <a href="{{ route('admin.booking.edit', $booking->id) }}"
                                           class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                                            Edit
                                        </a>
                                    </div>
                            </td>
                        </tr>
                    @empty
                            <!-- Sample Booking Rows for Display -->
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                <td class="py-3 px-4 text-gray-700 font-medium">1</td>
                                <td class="py-3 px-4 font-medium text-gray-900">John Doe</td>
                                <td class="py-3 px-4 text-gray-700">Superior Double Room</td>
                                <td class="py-3 px-4 text-gray-700">Dec 15, 2024</td>
                                <td class="py-3 px-4 text-gray-700">Dec 17, 2024</td>
                                <td class="py-3 px-4 font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">₱14,000.00</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-amber-500"></span>
                                        Pending
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="#"
                                           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                                            View
                                        </a>
                                        <a href="#"
                                           class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                <td class="py-3 px-4 text-gray-700 font-medium">2</td>
                                <td class="py-3 px-4 font-medium text-gray-900">Maria Santos</td>
                                <td class="py-3 px-4 text-gray-700">Deluxe Family Room</td>
                                <td class="py-3 px-4 text-gray-700">Dec 20, 2024</td>
                                <td class="py-3 px-4 text-gray-700">Dec 22, 2024</td>
                                <td class="py-3 px-4 font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">₱24,000.00</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-green-500"></span>
                                        Confirmed
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="#"
                                           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                                            View
                                        </a>
                                        <a href="#"
                                           class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                <td class="py-3 px-4 text-gray-700 font-medium">3</td>
                                <td class="py-3 px-4 font-medium text-gray-900">Robert Garcia</td>
                                <td class="py-3 px-4 text-gray-700">Executive Suite</td>
                                <td class="py-3 px-4 text-gray-700">Dec 25, 2024</td>
                                <td class="py-3 px-4 text-gray-700">Dec 28, 2024</td>
                                <td class="py-3 px-4 font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">₱21,000.00</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-amber-500"></span>
                                        Pending
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="#"
                                           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                                            View
                                        </a>
                                        <a href="#"
                                           class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                <td class="py-3 px-4 text-gray-700 font-medium">4</td>
                                <td class="py-3 px-4 font-medium text-gray-900">Sarah Cruz</td>
                                <td class="py-3 px-4 text-gray-700">Premium Villa Suite</td>
                                <td class="py-3 px-4 text-gray-700">Dec 10, 2024</td>
                                <td class="py-3 px-4 text-gray-700">Dec 12, 2024</td>
                                <td class="py-3 px-4 font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">₱30,000.00</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-green-500"></span>
                                        Confirmed
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="#"
                                           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                                            View
                                        </a>
                                        <a href="#"
                                           class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                <td class="py-3 px-4 text-gray-700 font-medium">5</td>
                                <td class="py-3 px-4 font-medium text-gray-900">Michael Tan</td>
                                <td class="py-3 px-4 text-gray-700">Superior Double Room</td>
                                <td class="py-3 px-4 text-gray-700">Dec 30, 2024</td>
                                <td class="py-3 px-4 text-gray-700">Jan 01, 2025</td>
                                <td class="py-3 px-4 font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">₱14,000.00</td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-red-500"></span>
                                        Cancelled
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="#"
                                           class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transform hover:scale-105 transition-all duration-200">
                                            View
                                        </a>
                                        <a href="#"
                                           class="bg-gradient-to-r from-emerald-500 to-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:from-emerald-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                                            Edit
                                        </a>
                                    </div>
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
