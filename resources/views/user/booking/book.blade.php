@extends('user.layouts.app')

@section('content')
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-serif text-gray-900">Guest Details</h1>
                <p class="text-gray-500 mt-1">Provide your information to continue your booking</p>
            </div>
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Left Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8">
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
                        <p class="text-gray-500 mb-6">Please fill in your personal and booking details</p>

                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('booking.confirmbooking') }}" id="bookingForm"
                            enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <!-- Personal Information -->
                            <div>
                                <h5 class="text-lg font-semibold text-gray-700 mb-4">Personal Information</h5>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">First Name *</label>
                                        <input type="text" name="first_name" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Last Name *</label>
                                        <input type="text" name="last_name" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="font-semibold text-gray-700 block mb-1">Email Address *</label>
                                <input type="email" name="email" required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>

                            <div>
                                <label class="font-semibold text-gray-700 block mb-1">Phone Number *</label>
                                <input type="tel" id="phoneInput" name="phone" required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>

                            <!-- Booking Details -->
                            <div>
                                <h5 class="text-lg font-semibold text-gray-700 mb-4">Booking Details</h5>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Check-in Date *</label>
                                        <input type="date" name="check_in" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Check-out Date *</label>
                                        <input type="date" name="check_out" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Number of Guests *</label>
                                        <input type="number" name="guests" min="1" value="2" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                            id="guestsInput">
                                        <small class="text-gray-500 text-sm mt-1 block" id="capacityHint">
                                            @php
                                                $cart = session('cart', []);
                                                $totalCapacity = 0;
                                                foreach ($cart as $item) {
                                                    $room = \App\Models\Room::find($item['room_id']);
                                                    if ($room) {
                                                        $totalCapacity += $room->capacity * $item['quantity'];
                                                    }
                                                }
                                            @endphp
                                            @if ($totalCapacity > 0)
                                                Maximum capacity for selected rooms: {{ $totalCapacity }} guests
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <label class="font-semibold text-gray-700 block mb-1">Special Requests</label>
                                        <input type="text" name="special_requests"
                                            placeholder="e.g., High floor, late check-in"
                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    </div>
                                </div>

                                <!-- Payment Section (QR + 50% reservation + proof upload) -->
                                @php
                                    $book_cart_total = collect(session('cart') ?? [])->sum(function ($item) {
                                        return ($item['room_price'] ?? 0) * ($item['quantity'] ?? 0);
                                    });
                                @endphp
                                <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5">
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fas fa-receipt text-indigo-600"></i>
                                        <h3 class="text-lg font-semibold text-gray-800">Payment</h3>
                                    </div>
                                    <div class="grid md:grid-cols-3 gap-4 items-start">
                                        <div class="col-span-1">
                                            <div class="rounded-xl border border-gray-200 p-3 text-center bg-gray-50">
                                                <img src="{{ asset('storage/qr_codes/resort_qr.png') }}" alt="Scan to Pay"
                                                    class="mx-auto w-48 h-48 object-contain">
                                                <p class="text-sm text-gray-500 mt-2">Scan QR to pay</p>
                                            </div>
                                        </div>
                                        <div class="col-span-2 space-y-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-600 font-medium">Amount Due (100%)</span>
                                                <span id="amountDueBook" class="text-lg font-bold text-indigo-700">PHP
                                                    {{ number_format(($book_cart_total ?? 0) * 1, 2) }}</span>
                                            </div>
                                            <input type="hidden" name="payment_multiplier" value="1">

                                            <div>
                                                <label class="font-semibold text-gray-700 block mb-1"><i
                                                        class="fas fa-wallet"></i> Mode of Payment *</label>
                                                <select name="payment_method" id="paymentMethodSelect"
                                                    class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                    required>
                                                    <option value="" disabled selected>Select a payment method
                                                    </option>
                                                    <option value="gcash">GCash</option>
                                                    <option value="paymaya">PayMaya</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                </select>
                                            </div>

                                            <div id="bankFieldsBook" class="hidden">
                                                <div class="grid md:grid-cols-2 gap-4 mt-2">
                                                    <div>
                                                        <label class="font-semibold text-gray-700 block mb-1"><i
                                                                class="fas fa-university"></i> Bank Name *</label>
                                                        <input type="text" name="bank_name"
                                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                            placeholder="e.g., BDO / BPI / Metrobank">
                                                    </div>
                                                    <div>
                                                        <label class="font-semibold text-gray-700 block mb-1"><i
                                                                class="fas fa-id-card"></i> Account Name *</label>
                                                        <input type="text" name="bank_account_name"
                                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                            placeholder="Laiya Grande Beach Resort">
                                                    </div>
                                                    <div>
                                                        <label class="font-semibold text-gray-700 block mb-1"><i
                                                                class="fas fa-hashtag"></i> Account Number *</label>
                                                        <input type="text" name="bank_account_number"
                                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                            placeholder="0000-0000-0000">
                                                    </div>
                                                    <div>
                                                        <label class="font-semibold text-gray-700 block mb-1"><i
                                                                class="fas fa-receipt"></i> Reference # *</label>
                                                        <input type="text" name="bank_reference"
                                                            class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                                            placeholder="Transaction reference number">
                                                    </div>
                                                </div>
                                            </div>

                                            <ul class="list-disc pl-5 text-sm text-red-600">
                                                <li>Use your email as Reference/Note in your transfer.</li>
                                                <li>After payment, upload the proof below so we can verify.</li>
                                                <li>Reservations are held for <strong>24 hours</strong> pending payment.
                                                </li>
                                            </ul>

                                            <!-- File Upload -->
                                            <div class="mb-4">
                                                <label for="payment_proof" class="block text-gray-700 font-semibold mb-2">
                                                    Upload Payment Proof *
                                                </label>
                                                <input type="file" name="payment_proof" id="payment_proof"
                                                    accept="image/*" class="border p-2 rounded w-full">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                                <a href="{{ route('booking.index') }}"
                                    class="flex-1 bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl text-center hover:bg-gray-300 transition">
                                    Back
                                </a>
                                <button type="submit" id="submitBtn"
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
                                    Continue to Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sidebar Summary -->
                <div>
                    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-xl sticky top-24">
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
                                    <div class="p-4 border border-gray-200 bg-gray-50 rounded-xl mb-4">
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
            // Check if cart has items before allowing form submission
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                @if (empty(session('cart')))
                    e.preventDefault();
                    alert('Please select rooms first before proceeding to checkout.');
                    window.location.href = '{{ route('booking.index') }}';
                    return false;
                @endif

                // Validate guest count against capacity
                const guestsInput = document.getElementById('guestsInput');
                const maxCapacity = {{ $totalCapacity ?? 0 }};
                if (guestsInput && maxCapacity > 0 && parseInt(guestsInput.value) > maxCapacity) {
                    e.preventDefault();
                    alert(
                        `Number of guests (${guestsInput.value}) exceeds room capacity (${maxCapacity}). Please reduce the number of guests or select rooms with higher capacity.`
                        );
                    guestsInput.focus();
                    return false;
                }
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

                // Toggle bank transfer fields only when bank transfer is selected
                const paySelect = document.getElementById('paymentMethodSelect');
                const bankWrap = document.getElementById('bankFieldsBook');

                function syncBank() {
                    const isBank = paySelect && paySelect.value === 'bank_transfer';
                    if (bankWrap) {
                        bankWrap.classList.toggle('hidden', !isBank);
                        bankWrap.querySelectorAll('input').forEach(inp => inp.toggleAttribute('required', isBank));
                    }
                }
                if (paySelect) {
                    paySelect.addEventListener('change', syncBank);
                    syncBank();
                }

                // Add client-side validation for guest capacity
                const guestsInput = document.getElementById('guestsInput');
                const capacityHint = document.getElementById('capacityHint');
                const maxCapacity = {{ $totalCapacity ?? 0 }};

                if (guestsInput && capacityHint && maxCapacity > 0) {
                    guestsInput.addEventListener('input', function() {
                        const guestCount = parseInt(this.value) || 0;
                        if (guestCount > maxCapacity) {
                            capacityHint.style.color = '#dc2626'; // red
                            capacityHint.innerHTML =
                                `Warning: Guest count (${guestCount}) exceeds room capacity (${maxCapacity})`;
                        } else {
                            capacityHint.style.color = '#6b7280'; // gray
                            capacityHint.innerHTML =
                                `Maximum capacity for selected rooms: ${maxCapacity} guests`;
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
