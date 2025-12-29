@extends('user.layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #0f766e;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #0d5f5a;
        }
    </style>
@endpush

@section('content')
    <section class="min-h-screen bg-slate-50 py-16 font-sans">
        <div class="container mx-auto px-4 max-w-7xl mt-6">

            <div class="mb-10 text-center">
                <h1 class="text-4xl font-serif font-bold text-teal-900 mb-2">Secure Your Stay</h1>
                <p class="text-slate-500 text-lg">Complete your details to finalize your reservation at Laiya Grande.</p>
            </div>

            <div class="max-w-2xl mx-auto mb-12">
                <div class="flex items-center justify-center relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-1 w-full bg-gray-200 rounded-full"></div>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div
                            class="h-10 w-10 bg-teal-700 rounded-full flex items-center justify-center border-4 border-slate-50 z-10 shadow-md">
                            <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <span
                            class="absolute -bottom-8 w-max text-sm font-bold text-teal-800 uppercase tracking-wider">Guest
                            Details</span>
                    </div>
                    <div class="relative flex flex-col items-center ml-32 sm:ml-48">
                        <div
                            class="h-10 w-10 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center z-10 shadow-sm">
                            <span class="text-gray-400 font-bold text-sm">2</span>
                        </div>
                        <span
                            class="absolute -bottom-8 w-max text-sm font-medium text-gray-400 uppercase tracking-wider">Review</span>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">

                <div class="lg:col-span-8">

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl mb-6">
                            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.booking.show-confirm') }}" id="bookingForm"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div
                            class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                            <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
                                <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
                                    <span
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    Personal Information
                                </h5>
                            </div>

                            <div class="p-8 space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">First
                                            Name *</label>
                                        <input type="text" name="first_name" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all"
                                            placeholder="Enter first name">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Last
                                            Name *</label>
                                        <input type="text" name="last_name" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all"
                                            placeholder="Enter last name">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email
                                            Address *</label>
                                        <div class="flex gap-2">
                                            <div class="relative flex-1">
                                                <input type="email" id="emailInput" name="email" required
                                                    class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all"
                                                    placeholder="name@example.com">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <i class="fas fa-envelope text-gray-400"></i>
                                                </div>
                                            </div>
                                            <button type="button" id="sendOtpBtn" onclick="sendOTP()"
                                                class="px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-semibold text-sm transition whitespace-nowrap">
                                                Send OTP
                                            </button>
                                        </div>
                                        <small id="otpTimer"
                                            class="text-teal-600 text-xs mt-1 hidden md:flex items-center gap-1">
                                            <i class="fas fa-clock"></i> <span id="timerText"></span>
                                        </small>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Enter
                                            OTP Code *</label>
                                        <div class="relative">
                                            <input type="text" id="otpInput" name="otp_code" required maxlength="6"
                                                class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all"
                                                placeholder="Enter 6-digit code" disabled>
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i class="fas fa-key text-gray-400"></i>
                                            </div>
                                        </div>
                                        <small id="otpStatus" class="text-xs mt-1 hidden"></small>
                                    </div>
                                </div>
                                <input type="hidden" id="otpVerified" name="otp_verified" value="0">

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Phone
                                            Number *</label>
                                        <div class="relative">
                                            <input type="tel" id="phoneDisplay"
                                                class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all"
                                                placeholder="+63 912 345 6789">
                                            <input type="hidden" id="phoneInput" name="phone" required>
                                            <div
                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i class="fas fa-phone text-gray-400"></i>
                                            </div>
                                        </div>
                                        <small id="phoneError" class="text-xs mt-1 hidden"></small>
                                    </div>
                                    <div></div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                            <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
                                <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
                                    <span
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    Booking Details
                                </h5>
                            </div>

                            <div class="p-8 space-y-6">
                                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-xl mb-6">
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1">
                                                Check-in</p>
                                            <p class="text-lg font-semibold text-teal-900">
                                                {{ \Carbon\Carbon::parse($checkIn)->format('M d, Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1">
                                                Check-out</p>
                                            <p class="text-lg font-semibold text-teal-900">
                                                {{ \Carbon\Carbon::parse($checkOut)->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-teal-200">
                                        <p class="text-sm text-teal-700 flex items-center gap-2">
                                            <i class="fas fa-moon"></i>
                                            <strong>{{ $nights }} night(s)</strong>
                                        </p>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('user.rooms.index') }}"
                                            class="text-xs text-teal-700 hover:text-teal-900 font-semibold inline-flex items-center gap-1">
                                            <i class="fas fa-edit"></i>
                                            Change dates
                                        </a>
                                    </div>
                                </div>

                                <input type="hidden" name="check_in" value="{{ $checkIn }}">
                                <input type="hidden" name="check_out" value="{{ $checkOut }}">
                                <input type="hidden" name="days_count" id="days_count_hidden"
                                    value="{{ $nights }}">

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Number
                                            of Guests *</label>
                                        <input type="number" name="guests" min="1" value="2" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                                            id="guestsInput">

                                        <small class="text-slate-500 text-xs mt-2 block md:flex items-center gap-1"
                                            id="capacityHint">
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
                                                <i class="fas fa-info-circle"></i> Max capacity for selected rooms:
                                                <strong>{{ $totalCapacity }} guests</strong>
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Special
                                            Requests</label>
                                        <input type="text" name="special_requests"
                                            placeholder="e.g., Ground floor, late arrival"
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                            <div
                                class="px-8 py-6 border-b border-slate-100 bg-teal-50/30 flex justify-between items-center">
                                <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
                                    <span
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                        <i class="fas fa-wallet"></i>
                                    </span>
                                    Payment
                                </h5>

                            </div>

                            <div class="p-8">
                                <div class="grid md:grid-cols-12 gap-8">

                                    <div
                                        class="md:col-span-4 flex flex-col items-center justify-center p-6 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                        <div class="bg-white p-2 rounded-xl shadow-sm mb-3">
                                            <img src="{{ asset('storage/qr_codes/resort_qr.png') }}" alt="Scan to Pay"
                                                class="w-40 h-40 object-contain">
                                        </div>
                                        <p class="text-sm font-semibold text-teal-800">Scan QR to Pay</p>
                                        <p class="text-xs text-slate-500">Supported E-Wallets & Banks</p>
                                    </div>

                                    <div class="md:col-span-8 space-y-6">

                                        <div
                                            class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                                            <div class="flex flex-col">
                                                <span class="text-yellow-800 font-medium">Total Amount Due</span>
                                                <span class="text-xs text-yellow-600" id="nightsInfo">{{ $nights }}
                                                    night(s)</span>
                                            </div>
                                            <span id="amountDueBook" class="text-xl font-bold text-teal-900">
                                                PHP {{ number_format($cartTotal ?? 0, 2) }}
                                            </span>
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mode
                                                of Payment *</label>
                                            <div class="relative">
                                                <select name="payment_method" id="paymentMethodSelect" required
                                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none appearance-none bg-white">
                                                    <option value="" disabled selected>Select a payment method
                                                    </option>
                                                    <option value="gcash">GCash</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                </select>
                                                <div
                                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="bankFieldsBook"
                                            class="hidden space-y-4 p-5 bg-slate-50 rounded-xl border border-slate-200">
                                            <h6
                                                class="text-sm font-bold text-teal-800 border-b border-slate-200 pb-2 mb-2">
                                                Bank Transfer Details</h6>
                                            <div class="grid md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-xs text-slate-500 font-bold mb-1 block">Bank Name
                                                        *</label>
                                                    <input type="text" name="bank_name"
                                                        class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="e.g., BDO">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-slate-500 font-bold mb-1 block">Account Name
                                                        *</label>
                                                    <input type="text" name="bank_account_name"
                                                        class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="Account Holder">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-slate-500 font-bold mb-1 block">Account
                                                        Number *</label>
                                                    <input type="text" name="bank_account_number"
                                                        class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="0000-0000-0000">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-slate-500 font-bold mb-1 block">Reference #
                                                        *</label>
                                                    <input type="text" name="bank_reference"
                                                        class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="Transaction Ref #">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-blue-50 p-4 rounded-xl">
                                            <ul class="list-disc pl-5 text-xs text-blue-800 space-y-1">
                                                <li>Use your email as Reference/Note in your transfer.</li>
                                                <li>Reservations are held for <strong>24 hours</strong> pending payment.
                                                </li>
                                            </ul>
                                        </div>

                                        <div>
                                            <label for="payment_proof"
                                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                                                Upload Payment Proof *
                                            </label>
                                            <input type="file" name="payment_proof" id="payment_proof"
                                                accept="image/*"
                                                class="block w-full text-sm text-slate-500
                                                file:mr-4 file:py-2.5 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-teal-50 file:text-teal-700
                                                hover:file:bg-teal-100 transition-all
                                                border border-gray-300 rounded-xl cursor-pointer">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row gap-4 pt-4">
                            <a href="{{ route('cart.index') }}"
                                class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-4 rounded-xl text-center hover:bg-gray-50 transition shadow-sm">
                                Go Back
                            </a>
                            <button type="submit" id="submitBtn"
                                class="flex-[2] bg-teal-700 hover:bg-teal-800 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-teal-700/30 flex items-center justify-center gap-2">
                                <span class="btn-text flex items-center gap-2">
                                    Continue to Review <i class="fas fa-arrow-right"></i>
                                </span>
                                <svg class="btn-spinner hidden animate-spin h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-4">
                    <div class="sticky top-24">
                        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-xl shadow-teal-900/10 border border-slate-100">

                            <h5
                                class="text-lg font-bold text-teal-900 mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                                <i class="fas fa-receipt text-yellow-500"></i> Booking Summary
                            </h5>

                            <div id="cartItems" class="max-h-[400px] overflow-y-auto mb-6 custom-scrollbar pr-2">
                                @if (empty(session('cart')))
                                    <div class="text-center py-10">
                                        <div
                                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 text-2xl">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <p class="text-slate-500 text-sm">No rooms selected</p>
                                    </div>
                                @else
                                    @php
                                        $daysCount = request('days_count', 0);
                                    @endphp

                                    @foreach (session('cart') as $item)
                                        <div class="flex gap-4 mb-6 relative group cart-item"
                                            data-room-id="{{ $item['room_id'] }}">
                                            <div
                                                class="w-16 h-16 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                                                <i class="fas fa-bed text-xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start mb-1">
                                                    <h6 class="text-teal-900 font-bold text-sm leading-tight pr-2">
                                                        {{ $item['room_name'] }}
                                                    </h6>
                                                    <button type="button"
                                                        onclick="removeFromCart({{ $item['room_id'] }})"
                                                        class="text-red-500 hover:text-white hover:bg-red-500 transition-all rounded-full w-7 h-7 flex items-center justify-center flex-shrink-0 border border-red-300 hover:border-red-500"
                                                        title="Remove room">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </div>
                                                <div class="flex justify-between items-center text-xs text-slate-500 mb-1">
                                                    <span>PHP {{ number_format($item['room_price'], 2) }} / night</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span
                                                        class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600 font-medium">Qty:
                                                        {{ $item['quantity'] }}</span>
                                                    <span class="text-teal-700 font-bold text-sm item-total"
                                                        data-price="{{ $item['room_price'] }}"
                                                        data-qty="{{ $item['quantity'] }}">
                                                        PHP {{ number_format($item['room_price'] * $item['quantity'], 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @if (!empty(session('cart')))
                                <div class="border-t-2 border-dashed border-slate-200 pt-6 space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Subtotal (per night)</span>
                                        <span class="font-semibold text-slate-700" id="subtotalPerNight">PHP
                                            {{ number_format($cartTotal ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Number of Nights</span>
                                        <span class="font-semibold text-slate-700" id="nightsDisplay">{{ $nights }}
                                            night(s)</span>
                                    </div>
                                    <div class="flex justify-between items-end pt-2">
                                        <span class="text-base font-bold text-teal-900">Total</span>
                                        <span class="text-2xl font-bold text-teal-700" id="grandTotal">PHP
                                            {{ number_format($cartTotal ?? 0, 2) }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-400">
                                    <i class="fas fa-lock"></i> Secure SSL Encrypted Transaction
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Data Privacy & Terms Modal -->
    <div id="privacyModal" class="fixed inset-0 backdrop-blur-sm bg-white/10 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
            <div class="bg-teal-700 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-shield-alt"></i>
                    Data Privacy & Terms of Service
                </h2>
            </div>

            <div class="p-6 overflow-y-auto max-h-[calc(90vh-200px)] custom-scrollbar">
                <!-- Data Privacy Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-teal-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-user-shield text-teal-600"></i>
                        Data Privacy Notice
                    </h3>
                    <div class="space-y-4 text-gray-700">
                        <p class="leading-relaxed">
                            At <strong>Laiya Grande Beach Resort</strong>, we are committed to protecting your personal
                            information and your right to privacy. This notice explains how we collect, use, and safeguard
                            your data.
                        </p>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                            <h4 class="font-bold text-blue-900 mb-2">Information We Collect:</h4>
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                <li>Personal details (name, email, phone number)</li>
                                <li>Booking information (check-in/out dates, number of guests)</li>
                                <li>Payment information (for verification purposes only)</li>
                            </ul>
                        </div>

                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <h4 class="font-bold text-green-900 mb-2">How We Use Your Information:</h4>
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                <li>Process and confirm your booking reservations</li>
                                <li>Send booking confirmations and updates via email/SMS</li>
                                <li>Provide customer support and respond to inquiries</li>
                                <li>Improve our services and user experience</li>
                            </ul>
                        </div>

                        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-r-lg">
                            <h4 class="font-bold text-purple-900 mb-2">Data Security:</h4>
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                <li>All data is encrypted using SSL/TLS technology</li>
                                <li>Stored securely on protected servers</li>
                                <li>Access limited to authorized personnel only</li>
                                <li>Never shared with third parties without consent</li>
                            </ul>
                        </div>

                        <p class="text-sm italic text-gray-600">
                            <i class="fas fa-info-circle"></i> Your data will be retained only for the duration necessary
                            to fulfill booking purposes and comply with legal obligations.
                        </p>
                    </div>
                </div>

                <!-- Terms & Conditions Section -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-teal-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-file-contract text-teal-600"></i>
                        Terms & Conditions
                    </h3>
                    <div class="space-y-4 text-gray-700">
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg">
                            <h4 class="font-bold text-yellow-900 mb-2">Booking Policy:</h4>
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                <li>All bookings are subject to availability confirmation</li>
                                <li>Payment proof must be submitted for verification</li>
                                <li>Reservations are held for 24 hours pending payment confirmation</li>
                                <li>Check-in time: 2:00 PM | Check-out time: 12:00 NN</li>
                            </ul>
                        </div>

                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                            <h4 class="font-bold text-red-900 mb-2">Cancellation Policy:</h4>
                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                <li>Confirmed bookings are <strong>NON-REFUNDABLE</strong></li>
                                <li>Modifications allowed only in case of typhoon/force majeure</li>
                                <li>No-shows will be charged 100% of booking amount</li>
                            </ul>
                        </div>

                        <p class="text-sm">
                            By proceeding with your booking, you acknowledge that you have read, understood, and agree to be
                            bound by these terms and conditions.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t flex flex-col sm:flex-row gap-3 justify-end">
                <button type="button" id="declineBtn"
                    class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold rounded-lg transition">
                    <i class="fas fa-times"></i> Decline
                </button>
                <button type="button" id="acceptBtn"
                    class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-check"></i> I Accept
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Data Privacy & Terms Modal
            const PRIVACY_ACCEPTED_KEY = 'privacy_terms_accepted';

            function checkPrivacyAcceptance() {
                const accepted = localStorage.getItem(PRIVACY_ACCEPTED_KEY);
                return accepted === 'true';
            }

            function disableFormInputs() {
                const form = document.getElementById('bookingForm');
                if (form) {
                    const inputs = form.querySelectorAll('input, select, textarea, button[type="submit"]');
                    inputs.forEach(input => {
                        input.disabled = true;
                        input.classList.add('cursor-not-allowed', 'opacity-50');
                    });
                }
            }

            function enableFormInputs() {
                const form = document.getElementById('bookingForm');
                if (form) {
                    const inputs = form.querySelectorAll('input, select, textarea, button[type="submit"]');
                    inputs.forEach(input => {
                        input.disabled = false;
                        input.classList.remove('cursor-not-allowed', 'opacity-50');
                    });
                }
            }

            function showPrivacyModal() {
                const modal = document.getElementById('privacyModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }

            function hidePrivacyModal() {
                const modal = document.getElementById('privacyModal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                if (!checkPrivacyAcceptance()) {
                    disableFormInputs();
                    showPrivacyModal();
                }

                // Accept button
                document.getElementById('acceptBtn').addEventListener('click', function() {
                    localStorage.setItem(PRIVACY_ACCEPTED_KEY, 'true');
                    hidePrivacyModal();
                    enableFormInputs();

                    Swal.fire({
                        title: 'Thank You!',
                        text: 'You can now proceed with your booking',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });

                // Decline button
                document.getElementById('declineBtn').addEventListener('click', function() {
                    Swal.fire({
                        title: 'Terms Declined',
                        text: 'You must accept the terms to proceed with booking',
                        icon: 'warning',
                        confirmButtonColor: '#0f766e',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '{{ route('home') }}';
                    });
                });
            });

            // Check if cart has items before allowing form submission
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                @if (empty(session('cart')))
                    e.preventDefault();
                    alert('Please select rooms first before proceeding to checkout.');
                    window.location.href = '{{ route('cart.index') }}';
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
                const checkIn = document.querySelector('input[name="check_in"]');
                const checkOut = document.querySelector('input[name="check_out"]');

                if (checkIn) checkIn.min = today;
                if (checkOut) checkOut.min = today;

                // Disable submit button if no items in cart
                @if (empty(session('cart')))
                    const submitBtn = document.getElementById('submitBtn');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-ban"></i> Select Rooms First';
                        submitBtn.classList.remove('bg-teal-700', 'hover:bg-teal-800');
                        submitBtn.classList.add('bg-gray-300', 'cursor-not-allowed', 'text-gray-500');
                    }
                @endif

                // Toggle bank transfer fields only when bank transfer is selected
                const paySelect = document.getElementById('paymentMethodSelect');
                const bankWrap = document.getElementById('bankFieldsBook');

                function syncBank() {
                    const isBank = paySelect && paySelect.value === 'bank_transfer';
                    if (bankWrap) {
                        if (isBank) {
                            bankWrap.classList.remove('hidden');
                        } else {
                            bankWrap.classList.add('hidden');
                        }
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
                            capacityHint.classList.add('text-red-600');
                            capacityHint.classList.remove('text-slate-500');
                            capacityHint.innerHTML =
                                `<i class="fas fa-exclamation-triangle"></i> Warning: Guest count (${guestCount}) exceeds room capacity (${maxCapacity})`;
                        } else {
                            capacityHint.classList.remove('text-red-600');
                            capacityHint.classList.add('text-slate-500');
                            capacityHint.innerHTML =
                                `<i class="fas fa-info-circle"></i> Maximum capacity for selected rooms: ${maxCapacity} guests`;
                        }
                    });
                }
            });
        </script>
        <script>
            const checkIn = document.getElementById('check_in');
            const checkOut = document.getElementById('check_out');
            const daysCount = document.getElementById('days_count_hidden');

            function updateDays() {
                const checkInDate = new Date(checkIn.value);
                const checkOutDate = new Date(checkOut.value);

                if (!isNaN(checkInDate) && !isNaN(checkOutDate)) {
                    const diffTime = checkOutDate - checkInDate;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    const nights = diffDays > 0 ? diffDays : 1;

                    daysCount.value = nights;
                    updateTotals(nights);
                } else {
                    updateTotals(1);
                }
            }

            function updateTotals(nights) {
                let subtotal = 0;

                // Update each item total
                document.querySelectorAll('.item-total').forEach(function(el) {
                    const price = parseFloat(el.dataset.price);
                    const qty = parseInt(el.dataset.qty);
                    const itemTotal = price * qty * nights;

                    el.textContent = "PHP " + itemTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    subtotal += price * qty;
                });

                const grandTotal = subtotal * nights;

                // Update subtotal per night
                const subtotalEl = document.getElementById('subtotalPerNight');
                if (subtotalEl) {
                    subtotalEl.textContent = "PHP " + subtotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                // Update nights display
                const nightsDisplayEl = document.getElementById('nightsDisplay');
                if (nightsDisplayEl) {
                    nightsDisplayEl.textContent = nights + " night(s)";
                }

                // Update grand total
                const grandTotalEl = document.getElementById('grandTotal');
                if (grandTotalEl) {
                    grandTotalEl.textContent = "PHP " + grandTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                // Update payment section
                const amountDueEl = document.getElementById('amountDueBook');
                if (amountDueEl) {
                    amountDueEl.textContent = "PHP " + grandTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                const nightsInfoEl = document.getElementById('nightsInfo');
                if (nightsInfoEl) {
                    nightsInfoEl.textContent = nights + " night(s)";
                }
            }

            checkIn.addEventListener('change', updateDays);
            checkOut.addEventListener('change', updateDays);

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateTotals(1);
            });
        </script>

        <script>
            const OTP_STORAGE_KEY = 'otp_timer_data';
            let otpTimer = null;

            // Get OTP timer data from localStorage
            function getOtpTimerData() {
                const data = localStorage.getItem(OTP_STORAGE_KEY);
                return data ? JSON.parse(data) : null;
            }

            // Save OTP timer data to localStorage
            function saveOtpTimerData(sentAt) {
                localStorage.setItem(OTP_STORAGE_KEY, JSON.stringify({
                    sentAt: sentAt,
                    expiresAt: sentAt + 60000 // 1 minute
                }));
            }

            // Clear OTP timer data
            function clearOtpTimerData() {
                localStorage.removeItem(OTP_STORAGE_KEY);
            }

            async function sendOTP() {
                const email = document.getElementById('emailInput').value;
                const sendBtn = document.getElementById('sendOtpBtn');
                const otpInput = document.getElementById('otpInput');

                if (!email || !email.includes('@')) {
                    Swal.fire({
                        title: 'Invalid Email',
                        text: 'Please enter a valid email address',
                        icon: 'warning',
                        confirmButtonColor: '#0f766e'
                    });
                    return;
                }

                sendBtn.disabled = true;
                sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

                try {
                    const response = await fetch('{{ route('user.booking.send-otp') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        otpInput.disabled = false;
                        otpInput.focus();

                        // Save timer data to localStorage
                        const sentAt = Date.now();
                        saveOtpTimerData(sentAt);
                        startOtpTimer();

                        Swal.fire({
                            title: 'OTP Sent!',
                            text: 'Please check your email for the verification code',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        sendBtn.disabled = false;
                        sendBtn.innerHTML = 'Send OTP';

                        Swal.fire({
                            title: 'Failed',
                            text: data.message || 'Failed to send OTP',
                            icon: 'error',
                            confirmButtonColor: '#0f766e'
                        });
                    }
                } catch (error) {
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = 'Send OTP';

                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while sending OTP',
                        icon: 'error',
                        confirmButtonColor: '#0f766e'
                    });
                }
            }

            function startOtpTimer() {
                const sendBtn = document.getElementById('sendOtpBtn');
                const timerData = getOtpTimerData();

                if (!timerData) return;

                if (otpTimer) clearInterval(otpTimer);

                otpTimer = setInterval(() => {
                    const now = Date.now();
                    const remaining = timerData.expiresAt - now;

                    if (remaining <= 0) {
                        clearInterval(otpTimer);
                        clearOtpTimerData();
                        sendBtn.disabled = false;
                        sendBtn.innerHTML = 'Resend OTP';
                        sendBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                        sendBtn.classList.add('bg-teal-600', 'hover:bg-teal-700');
                        return;
                    }

                    const seconds = Math.ceil(remaining / 1000);
                    sendBtn.disabled = true;
                    sendBtn.innerHTML = `Wait ${seconds}s`;
                    sendBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                    sendBtn.classList.remove('bg-teal-600', 'hover:bg-teal-700');
                }, 100);
            }

            // Initialize timer on page load if OTP was sent
            document.addEventListener('DOMContentLoaded', function() {
                const timerData = getOtpTimerData();
                if (timerData) {
                    const now = Date.now();
                    if (now < timerData.expiresAt) {
                        // Timer still active
                        startOtpTimer();
                        document.getElementById('otpInput').disabled = false;
                    } else {
                        // Timer expired
                        clearOtpTimerData();
                    }
                }

                // Check if email was already verified (from localStorage)
                const otpVerified = document.getElementById('otpVerified').value;
                if (otpVerified === '1') {
                    const emailInput = document.getElementById('emailInput');
                    const otpInput = document.getElementById('otpInput');
                    const sendOtpBtn = document.getElementById('sendOtpBtn');

                    // Make fields readonly
                    emailInput.readOnly = true;
                    emailInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    otpInput.readOnly = true;
                    otpInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'border-green-500', 'bg-green-50');

                    // Disable Send OTP button
                    sendOtpBtn.disabled = true;
                    sendOtpBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                    sendOtpBtn.classList.remove('bg-teal-600', 'hover:bg-teal-700');
                    sendOtpBtn.innerHTML = '<i class="fas fa-check"></i> Verified';

                    // Show verified status
                    const otpStatus = document.getElementById('otpStatus');
                    otpStatus.className = 'text-green-600 text-xs mt-1 flex items-center gap-1';
                    otpStatus.innerHTML = '<i class="fas fa-check-circle"></i> Email verified successfully!';
                    otpStatus.classList.remove('hidden');
                }
            });

            // Verify OTP when user types
            document.getElementById('otpInput').addEventListener('input', async function() {
                const otp = this.value;
                const otpStatus = document.getElementById('otpStatus');
                const emailInput = document.getElementById('emailInput');
                const email = emailInput.value;
                const sendOtpBtn = document.getElementById('sendOtpBtn');

                if (otp.length === 6) {
                    try {
                        const response = await fetch('{{ route('user.booking.verify-otp') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                email: email,
                                otp: otp
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            otpStatus.className = 'text-green-600 text-xs mt-1 flex items-center gap-1';
                            otpStatus.innerHTML =
                                '<i class="fas fa-check-circle"></i> Email verified successfully!';
                            otpStatus.classList.remove('hidden');
                            document.getElementById('otpVerified').value = '1';
                            this.classList.add('border-green-500', 'bg-green-50');

                            // Make email and OTP fields readonly after successful verification
                            emailInput.readOnly = true;
                            emailInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                            this.readOnly = true;
                            this.classList.add('bg-gray-100', 'cursor-not-allowed');

                            // Disable Send OTP button
                            sendOtpBtn.disabled = true;
                            sendOtpBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                            sendOtpBtn.classList.remove('bg-teal-600', 'hover:bg-teal-700', 'bg-gray-400');
                            sendOtpBtn.innerHTML = '<i class="fas fa-check"></i> Verified';

                            // Clear timer data on successful verification
                            clearOtpTimerData();
                            if (otpTimer) clearInterval(otpTimer);

                            Swal.fire({
                                title: 'Verified!',
                                text: 'Email verified successfully',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            otpStatus.className = 'text-red-600 text-xs mt-1 flex items-center gap-1';
                            otpStatus.innerHTML = '<i class="fas fa-times-circle"></i> ' + (data.message ||
                                'Invalid OTP');
                            otpStatus.classList.remove('hidden');
                            document.getElementById('otpVerified').value = '0';
                            this.classList.remove('border-green-500', 'bg-green-50');
                        }
                    } catch (error) {
                        otpStatus.className = 'text-red-600 text-xs mt-1 flex items-center gap-1';
                        otpStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error verifying OTP';
                        otpStatus.classList.remove('hidden');
                        document.getElementById('otpVerified').value = '0';
                    }
                }
            });

            // Validate OTP before form submission
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                const otpVerified = document.getElementById('otpVerified').value;

                if (otpVerified !== '1') {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Email Not Verified',
                        text: 'Please verify your email with OTP before proceeding.',
                        icon: 'warning',
                        confirmButtonColor: '#0f766e'
                    });
                    return false;
                }

                // Validate phone number
                const phoneValid = validatePhoneNumber();
                if (!phoneValid) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Invalid Phone Number',
                        text: 'Please enter a valid Philippine mobile number.',
                        icon: 'warning',
                        confirmButtonColor: '#0f766e'
                    });
                    return false;
                }
            });
        </script>

        <script>
            // Philippine phone number validation with +63 display format
            const phoneDisplay = document.getElementById('phoneDisplay');
            const phoneInput = document.getElementById('phoneInput');
            const phoneError = document.getElementById('phoneError');

            function validatePhoneNumber() {
                const phone = phoneInput.value;

                if (!phone) {
                    showPhoneError('Phone number is required');
                    return false;
                }

                // Should be in format 09XXXXXXXXX (11 digits)
                const digitsOnly = phone.replace(/\D/g, '');

                if (digitsOnly.length !== 11 || !digitsOnly.startsWith('09')) {
                    showPhoneError('Invalid phone number format');
                    return false;
                }

                hidePhoneError();
                phoneDisplay.classList.remove('border-red-500');
                phoneDisplay.classList.add('border-green-500');
                return true;
            }

            function showPhoneError(message) {
                phoneError.textContent = message;
                phoneError.className = 'text-red-600 text-xs mt-1 flex items-center gap-1';
                phoneError.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + message;
                phoneError.classList.remove('hidden');
                phoneDisplay.classList.add('border-red-500');
                phoneDisplay.classList.remove('border-green-500');
            }

            function hidePhoneError() {
                phoneError.classList.add('hidden');
                phoneDisplay.classList.remove('border-red-500');
            }

            // Convert display format to save format
            function convertToSaveFormat(displayValue) {
                // Extract only digits from display (+63 912 345 6789)
                let digitsOnly = displayValue.replace(/\D/g, '');

                // Remove +63 prefix if present
                if (digitsOnly.startsWith('63')) {
                    digitsOnly = digitsOnly.slice(2);
                }

                // Add 0 prefix if not present
                if (!digitsOnly.startsWith('0') && digitsOnly.length === 10) {
                    digitsOnly = '0' + digitsOnly;
                }

                return digitsOnly;
            }

            // Convert save format to display format
            function convertToDisplayFormat(saveValue) {
                let digitsOnly = saveValue.replace(/\D/g, '');

                // Remove leading 0 if present
                if (digitsOnly.startsWith('0')) {
                    digitsOnly = digitsOnly.slice(1);
                }

                // Limit to 10 digits (after removing 0)
                if (digitsOnly.length > 10) {
                    digitsOnly = digitsOnly.slice(0, 10);
                }

                // Format as +63 9XX XXX XXXX
                let formatted = '+63';
                if (digitsOnly.length > 0) {
                    formatted += ' ' + digitsOnly.slice(0, 3);
                }
                if (digitsOnly.length > 3) {
                    formatted += ' ' + digitsOnly.slice(3, 6);
                }
                if (digitsOnly.length > 6) {
                    formatted += ' ' + digitsOnly.slice(6, 10);
                }

                return formatted;
            }

            // Real-time input handling
            phoneDisplay.addEventListener('input', function() {
                let value = this.value;

                // Extract only digits
                let digitsOnly = value.replace(/\D/g, '');

                // Handle different input formats
                // If starts with 63, remove it (user typed +63 or 63)
                if (digitsOnly.startsWith('63')) {
                    digitsOnly = digitsOnly.slice(2);
                }

                // Check if user typed with 0 or without 0
                let hasLeadingZero = digitsOnly.startsWith('0');
                let numberPart = digitsOnly;

                if (hasLeadingZero) {
                    // User typed with 0 (e.g., 09123456789)
                    // Limit to 11 digits total
                    if (digitsOnly.length > 11) {
                        digitsOnly = digitsOnly.slice(0, 11);
                    }
                    // Remove the 0 for display
                    numberPart = digitsOnly.slice(1);
                } else {
                    // User typed without 0 (e.g., 9123456789)
                    // Limit to 10 digits
                    if (digitsOnly.length > 10) {
                        digitsOnly = digitsOnly.slice(0, 10);
                    }
                    numberPart = digitsOnly;
                }

                // Format display as +63 9XX XXX XXXX
                let formatted = '+63';
                if (numberPart.length > 0) {
                    formatted += ' ' + numberPart.slice(0, 3);
                }
                if (numberPart.length > 3) {
                    formatted += ' ' + numberPart.slice(3, 6);
                }
                if (numberPart.length > 6) {
                    formatted += ' ' + numberPart.slice(6, 10);
                }

                this.value = formatted;

                // Save as 09XXXXXXXXX format (always with 0)
                if (numberPart.length === 10) {
                    phoneInput.value = '0' + numberPart;
                    validatePhoneNumber();
                } else if (numberPart.length > 0) {
                    phoneInput.value = '0' + numberPart;
                    hidePhoneError();
                } else {
                    phoneInput.value = '';
                    hidePhoneError();
                }
            });

            phoneDisplay.addEventListener('blur', function() {
                if (phoneInput.value) {
                    validatePhoneNumber();
                }
            });

            // Prevent form submission if phone is invalid
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                if (!validatePhoneNumber()) {
                    e.preventDefault();
                    alert('Please enter a valid Philippine mobile number.');
                    return false;
                }
            });
        </script>

        <script>
            // Save and restore form data using localStorage
            const STORAGE_KEY = 'booking_form_data';
            const formFields = [
                'first_name',
                'last_name',
                'email',
                'phone',
                'check_in',
                'check_out',
                'guests',
                'special_requests',
                'payment_method',
                'bank_name',
                'bank_account_name',
                'bank_account_number',
                'bank_reference'
            ];

            // Save form data to localStorage
            function saveFormData() {
                const formData = {};
                formFields.forEach(fieldName => {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        formData[fieldName] = field.value;
                    }
                });

                // Save phone display value separately
                const phoneDisplay = document.getElementById('phoneDisplay');
                if (phoneDisplay) {
                    formData['phone_display'] = phoneDisplay.value;
                }

                localStorage.setItem(STORAGE_KEY, JSON.stringify(formData));
            }

            // Restore form data from localStorage
            function restoreFormData() {
                const savedData = localStorage.getItem(STORAGE_KEY);
                if (!savedData) return;

                try {
                    const formData = JSON.parse(savedData);

                    formFields.forEach(fieldName => {
                        if (formData[fieldName]) {
                            const field = document.querySelector(`[name="${fieldName}"]`);
                            if (field) {
                                field.value = formData[fieldName];

                                // Trigger change event for special handling
                                field.dispatchEvent(new Event('change', {
                                    bubbles: true
                                }));
                            }
                        }
                    });

                    // Restore phone display
                    if (formData['phone_display']) {
                        const phoneDisplay = document.getElementById('phoneDisplay');
                        if (phoneDisplay) {
                            phoneDisplay.value = formData['phone_display'];
                            // Trigger input event to update hidden field
                            phoneDisplay.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                        }
                    }
                } catch (error) {
                    console.error('Error restoring form data:', error);
                }
            }

            // Clear form data from localStorage
            function clearFormData() {
                localStorage.removeItem(STORAGE_KEY);
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Restore saved data
                restoreFormData();

                // Save data on input change
                formFields.forEach(fieldName => {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        field.addEventListener('input', saveFormData);
                        field.addEventListener('change', saveFormData);
                    }
                });

                // Save phone display on input
                const phoneDisplay = document.getElementById('phoneDisplay');
                if (phoneDisplay) {
                    phoneDisplay.addEventListener('input', saveFormData);
                }

                // Clear localStorage when form is successfully submitted
                const bookingForm = document.getElementById('bookingForm');
                if (bookingForm) {
                    bookingForm.addEventListener('submit', function(e) {
                        // Only clear if validation passes
                        const otpVerified = document.getElementById('otpVerified').value;
                        const phoneValid = validatePhoneNumber();

                        if (otpVerified === '1' && phoneValid) {
                            // Form is valid, clear saved data
                            clearFormData();
                        }
                    });
                }
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Remove room from cart with SweetAlert
            async function removeFromCart(roomId) {
                const result = await Swal.fire({
                    title: 'Remove Room?',
                    text: 'Are you sure you want to remove this room from your booking?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                });

                if (!result.isConfirmed) {
                    return;
                }

                // Show loading
                Swal.fire({
                    title: 'Removing...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                try {
                    const response = await fetch(`/cart/remove/${roomId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remove the item from DOM with animation
                        const cartItem = document.querySelector(`.cart-item[data-room-id="${roomId}"]`);
                        if (cartItem) {
                            cartItem.style.transition = 'all 0.3s ease';
                            cartItem.style.opacity = '0';
                            cartItem.style.transform = 'translateX(-20px)';

                            setTimeout(() => {
                                cartItem.remove();

                                // Check if cart is empty
                                const remainingItems = document.querySelectorAll('.cart-item');
                                if (remainingItems.length === 0) {
                                    Swal.fire({
                                        title: 'Cart Empty',
                                        text: 'Your cart is now empty. Redirecting...',
                                        icon: 'info',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    // Recalculate totals
                                    recalculateTotals();
                                    Swal.fire({
                                        title: 'Removed!',
                                        text: 'Room has been removed from your booking.',
                                        icon: 'success',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                }
                            }, 300);
                        }
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to remove room. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#0f766e'
                        });
                    }
                } catch (error) {
                    console.error('Error removing room:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#0f766e'
                    });
                }
            }

            // Recalculate totals after removing an item
            function recalculateTotals() {
                const daysCountInput = document.getElementById('days_count_hidden');
                const nights = parseInt(daysCountInput?.value) || 1;

                let subtotal = 0;
                document.querySelectorAll('.item-total').forEach(function(el) {
                    const price = parseFloat(el.dataset.price);
                    const qty = parseInt(el.dataset.qty);
                    subtotal += price * qty;
                });

                const grandTotal = subtotal * nights;

                // Update subtotal per night
                const subtotalEl = document.getElementById('subtotalPerNight');
                if (subtotalEl) {
                    subtotalEl.textContent = "PHP " + subtotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                // Update grand total
                const grandTotalEl = document.getElementById('grandTotal');
                if (grandTotalEl) {
                    grandTotalEl.textContent = "PHP " + grandTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }

                // Update payment section
                const amountDueEl = document.getElementById('amountDueBook');
                if (amountDueEl) {
                    amountDueEl.textContent = "PHP " + grandTotal.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            }
        </script>
    @endpush
@endsection
