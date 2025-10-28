@extends('user.layouts.app')

@section('content')
    <section class="py-10 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Left Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-lg rounded-2xl p-8">
                        <!-- Progress Indicator -->
                        <div class="flex items-center mb-8">
                            <div
                                class="w-10 h-10 bg-blue-600 text-white font-bold rounded-full flex items-center justify-center">
                                1</div>
                            <div class="flex-1 h-0.5 bg-blue-600 mx-4"></div>
                            <div
                                class="w-10 h-10 bg-gray-200 text-gray-600 font-bold rounded-full flex items-center justify-center">
                                2</div>
                        </div>

                        <h2 class="text-2xl font-bold mb-2 text-gray-800">Step 1: Your Information</h2>
                        <p class="text-gray-500 mb-8">Please fill in your personal and booking details</p>

                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('booking.confirmbooking') }}" id="bookingForm"
                            class="space-y-6">
                            @csrf
                            <!-- Personal Information -->
                            <div>
                                <h5 class="text-lg font-semibold text-gray-700 mb-4">Personal Information</h5>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">First Name *</label>
                                        <input type="text" name="first_name" required
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Last Name *</label>
                                        <input type="text" name="last_name" required
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="font-semibold text-gray-700 block mb-1">Email Address *</label>
                                <input type="email" name="email" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>

                            <!-- OTP Section -->
                            <div>
                                <label class="font-semibold text-gray-700 block mb-1">Phone Number *</label>
                                <div class="flex gap-2">
                                    <input type="tel" id="phoneInput" name="phone" required
                                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <button type="button" onclick="sendOTP()"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded-lg transition">
                                        Send OTP
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="font-semibold text-gray-700 block mb-1">Enter OTP *</label>
                                <input type="text" id="otpInput" name="otp" maxlength="6"
                                    placeholder="Enter 6-digit code" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <div id="otpMessage" class="mt-2 text-sm hidden"></div>
                            </div>

                            <!-- Booking Details -->
                            <div>
                                <h5 class="text-lg font-semibold text-gray-700 mb-4">Booking Details</h5>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Check-in Date *</label>
                                        <input type="date" name="check_in" required
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Check-out Date *</label>
                                        <input type="date" name="check_out" required
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Number of Guests *</label>
                                        <input type="number" name="guests" min="1" value="2" required
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Special Requests</label>
                                        <input type="text" name="special_requests"
                                            placeholder="e.g., High floor, late check-in"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                                <a href="{{ route('booking.index') }}"
                                    class="flex-1 bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg text-center hover:bg-gray-300 transition">
                                    Back
                                </a>
                                <button type="submit" id="submitBtn"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                                    Continue to Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar Summary -->
                <div>
                    <div class="bg-white p-8 rounded-2xl shadow-lg sticky top-24">
                        <h5 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-bed"></i> Booking Summary
                        </h5>

                        <div id="cartItems" class="max-h-[400px] overflow-y-auto mb-6">
                            @if (empty(session('cart')))
                                <p class="text-gray-500 text-center py-8">
                                    <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                    No items selected
                                </p>
                            @else
                                @foreach (session('cart') as $item)
                                    <div class="p-4 border border-gray-200 bg-gray-50 rounded-lg mb-4">
                                        <h6 class="text-gray-800 font-semibold mb-1 text-sm">
                                            {{ $item['room_name'] }}</h6>
                                        <div class="flex justify-between items-center text-sm mb-1">
                                            <span class="text-gray-500">Qty: {{ $item['quantity'] }}</span>
                                            <span class="text-indigo-600 font-semibold">PHP
                                                {{ number_format($item['room_price'] * $item['quantity'], 2) }}</span>
                                        </div>
                                        <p class="text-gray-500 text-xs">PHP
                                            {{ number_format($item['room_price'], 2) }}/night
                                        </p>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        @if (!empty(session('cart')))
                            @php
                                $cart_total = collect(session('cart'))->sum(function ($item) {
                                    return $item['room_price'] * $item['quantity'];
                                });
                                $tax = $cart_total * 0.12;
                                $total = $cart_total + $tax;
                            @endphp
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-500">Subtotal</span>
                                    <span class="font-semibold">PHP {{ number_format($cart_total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm mb-4">
                                    <span class="text-gray-500">Taxes & Fees (12%)</span>
                                    <span class="font-semibold">PHP {{ number_format($tax, 2) }}</span>
                                </div>
                                <div
                                    class="flex justify-between font-bold text-lg text-indigo-600 pt-3 border-t border-gray-200">
                                    <span>Total</span>
                                    <span>PHP {{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            function sendOTP() {
                const phone = document.getElementById('phoneInput').value;
                const msg = document.getElementById('otpMessage');
                if (!phone) {
                    alert('Please enter a phone number');
                    return;
                }
                msg.textContent = 'OTP sent to ' + phone;
                msg.classList.remove('hidden', 'text-red-500');
                msg.classList.add('text-green-600');
            }

            document.getElementById('otpInput').addEventListener('change', function() {
                const otp = this.value;
                const msg = document.getElementById('otpMessage');
                if (otp.length === 6) {
                    if (otp === '123456') {
                        msg.textContent = 'OTP Verified ✓';
                        msg.classList.remove('text-red-500');
                        msg.classList.add('text-green-600');
                    } else {
                        msg.textContent = 'Invalid OTP';
                        msg.classList.remove('text-green-600');
                        msg.classList.add('text-red-500');
                    }
                    msg.classList.remove('hidden');
                }
            });

            // Check if cart has items before allowing form submission
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                @if (empty(session('cart')))
                    e.preventDefault();
                    alert('Please select rooms first before proceeding to checkout.');
                    window.location.href = '{{ route('booking.index') }}';
                    return false;
                @endif
            });

            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                document.querySelector('input[name="check_in"]').min = today;
                document.querySelector('input[name="check_out"]').min = today;

                // Disable submit button if no items in cart
                @if (empty(session('cart')))
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Select Rooms First';
                    submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                @endif
            });
        </script>
    @endpush
@endsection
