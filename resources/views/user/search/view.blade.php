@extends('user.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ ucfirst($type) }} Details</h1>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Back Button -->
                <div class="mb-6">
                    <a href="/search"
                        class="inline-block text-sm font-medium text-teal-600 bg-white px-4 py-2 rounded-full shadow hover:bg-teal-50 transition">
                        ← Back to Search
                    </a>
                </div>

                <!-- Details Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">
                                {{ ucfirst($type) }} #{{ $data->reservation_number ?: $data->id }}
                            </h3>
                            <p class="text-gray-600 text-lg">{{ $data->firstname }} {{ $data->lastname }}</p>
                            <p class="text-sm text-gray-500 mt-1">Type: {{ ucfirst($type) }}</p>
                        </div>
                        <span
                            class="px-4 py-2 rounded-full text-sm font-bold
                            {{ (($data->status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800 border border-yellow-300'
                                        : $data->status === 'paid')
                                    ? 'bg-blue-100 text-blue-800 border border-blue-300'
                                    : $data->status === 'confirmed')
                                ? 'bg-green-100 text-green-800 border border-green-300'
                                : 'bg-red-100 text-red-800 border border-red-300' }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Room</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $data->room ? $data->room->name : 'N/A' }}
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Check-in</div>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $data->check_in ? \Carbon\Carbon::parse($data->check_in)->format('M j, Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Check-out</div>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $data->check_out ? \Carbon\Carbon::parse($data->check_out)->format('M j, Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Guests</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $data->number_of_guests }}</div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Amount
                            </div>
                            <div class="text-xl font-bold text-green-600">₱{{ number_format($data->total_price, 2) }}</div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Payment Method
                            </div>
                            <div class="text-lg font-semibold text-gray-900">
                                {{ $data->payment_method ? ucwords(str_replace('_', ' ', $data->payment_method)) : 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @if ($type === 'reservation')
                            @if ($data->status === 'pending')
                                <a href="/user/reservation/{{ $data->id }}/continue"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                    Complete Payment
                                </a>
                            @elseif($data->status === 'paid')
                                <span class="bg-blue-100 text-blue-800 px-6 py-3 rounded-lg font-semibold">
                                    Payment Submitted - Awaiting Confirmation
                                </span>
                            @elseif($data->status === 'confirmed')
                                <span class="bg-green-100 text-green-800 px-6 py-3 rounded-lg font-semibold">
                                    Confirmed - Ready to Check-in
                                </span>
                            @endif

                            <a href="/user/reservation/{{ $data->id }}"
                                class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors font-semibold">
                                View Reservation
                            </a>
                        @else
                            <span class="bg-gray-100 text-gray-800 px-6 py-3 rounded-lg font-semibold">
                                Booking Details
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
