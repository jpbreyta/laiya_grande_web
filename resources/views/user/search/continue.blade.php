@extends('user.layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-3xl font-bold mb-6 text-gray-800">Continue Payment</h1>

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Step 1: Enter Reservation Code -->
                @if (!isset($reservation))
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold mb-3 text-blue-800">Find Your Reservation</h3>
                        <p class="text-blue-700 mb-4">Enter your reservation code to continue with payment.</p>

                        <form id="searchForm" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Reservation Code</label>
                                <input type="text" id="reservation_code" name="reservation_code"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Enter your reservation code (e.g., RSV-20241110-123456)" required>
                            </div>
                            <button type="submit" id="searchBtn"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-search mr-2"></i>Find Reservation
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Step 2: Reservation Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold mb-3">Reservation Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <strong>Reservation Code:</strong>
                                {{ $reservation->reservation_number ?: $reservation->id }}
                            </div>
                            <div>
                                <strong>Guest:</strong> {{ $reservation->firstname }} {{ $reservation->lastname }}
                            </div>
                            <div>
                                <strong>Email:</strong> {{ $reservation->email }}
                            </div>
                            <div>
                                <strong>Phone:</strong> {{ $reservation->phone_number }}
                            </div>
                            <div>
                                <strong>Room:</strong> {{ $reservation->room->name ?? 'N/A' }}
                            </div>
                            <div>
                                <strong>Check-in:</strong>
                                {{ \Carbon\Carbon::parse($reservation->check_in)->format('M d, Y') }}
                            </div>
                            <div>
                                <strong>Check-out:</strong>
                                {{ \Carbon\Carbon::parse($reservation->check_out)->format('M d, Y') }}
                            </div>
                            <div>
                                <strong>Guests:</strong> {{ $reservation->number_of_guests }}
                            </div>
                            <div>
                                <strong>Status:</strong>
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold
                                @if ($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reservation->status === 'confirmed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                            <div>
                                <strong>Total Price:</strong> ₱{{ number_format($reservation->total_price, 2) }}
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Identity Verification -->
                    @if (!session('reservation_verified_' . $reservation->id))
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-3 text-yellow-800">Verify Your Identity</h3>
                            <p class="text-yellow-700 mb-4">Please confirm your email and phone number to proceed with
                                payment.</p>

                            <form method="POST" action="{{ route('user.reservation.continue', $reservation->id) }}"
                                class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                            required>
                                    </div>
                                </div>
                                <div class="flex space-x-4">
                                    <button type="submit"
                                        class="bg-yellow-600 text-white px-6 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                        Verify & Continue
                                    </button>
                                    <a href="{{ route('search.index') }}"
                                        class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                                        Back to Search
                                    </a>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Step 4: Payment Form -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold mb-3 text-green-800">Complete Your Payment</h3>
                            <p class="text-green-700 mb-4">Your identity has been verified. Please complete your payment
                                below.</p>

                            <form method="POST" action="{{ route('user.reservation.updatePayment', $reservation->id) }}"
                                enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <!-- Payment Method -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                    <select name="payment_method"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                        required>
                                        <option value="">Select Payment Method</option>
                                        <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>
                                            GCash</option>
                                        <option value="paymaya" {{ old('payment_method') == 'paymaya' ? 'selected' : '' }}>
                                            PayMaya</option>
                                        <option value="bank_transfer"
                                            {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer
                                        </option>
                                    </select>
                                </div>

                                <!-- QR Code Display -->
                                <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                    <h4 class="font-semibold mb-2">Scan QR Code to Pay</h4>
                                    <img src="{{ asset('storage/qr_codes/resort_qr.png') }}" alt="Payment QR Code"
                                        class="mx-auto w-48 h-48 object-contain border">
                                    <p class="text-sm text-gray-600 mt-2">Amount Due:
                                        ₱{{ number_format($reservation->total_price, 2) }}</p>
                                </div>

                                <!-- Payment Proof Upload -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Payment Proof</label>
                                    <input type="file" name="payment" accept="image/*,application/pdf"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                        required>
                                    <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF. Max size: 5MB</p>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end space-x-4">
                                    <a href="{{ route('search.index') }}"
                                        class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                                        Cancel
                                    </a>
                                    <button type="submit"
                                        class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        Complete Payment
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if (!isset($reservation))
        <script>
            document.getElementById('searchForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const searchBtn = document.getElementById('searchBtn');
                const originalText = searchBtn.innerHTML;
                searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Searching...';
                searchBtn.disabled = true;

                const formData = new FormData(this);

                fetch('/search/by-code', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.type === 'reservation') {
                                window.location.href = `/search/continue/${data.data.id}/reservation`;
                            } else {
                                // Handle booking - redirect to booking continue if needed
                                alert('This is a confirmed booking. Please contact support for payment issues.');
                            }
                        } else {
                            alert(data.message || 'No reservation found with that code.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while searching. Please try again.');
                    })
                    .finally(() => {
                        searchBtn.innerHTML = originalText;
                        searchBtn.disabled = false;
                    });
            });
        </script>
    @endif
@endsection
