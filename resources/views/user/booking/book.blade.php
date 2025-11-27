@extends('user.layouts.app')

@section('content')
    <section class="min-h-screen bg-slate-50 py-16 font-sans">
        <div class="container mx-auto px-4 max-w-7xl">
            
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
                        <div class="h-10 w-10 bg-teal-700 rounded-full flex items-center justify-center border-4 border-slate-50 z-10 shadow-md">
                            <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <span class="absolute -bottom-8 w-max text-sm font-bold text-teal-800 uppercase tracking-wider">Guest Details</span>
                    </div>
                    <div class="relative flex flex-col items-center ml-32 sm:ml-48">
                        <div class="h-10 w-10 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center z-10 shadow-sm">
                            <span class="text-gray-400 font-bold text-sm">2</span>
                        </div>
                        <span class="absolute -bottom-8 w-max text-sm font-medium text-gray-400 uppercase tracking-wider">Review</span>
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

                    <form method="POST" action="{{ route('booking.confirmbooking') }}" id="bookingForm" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <div class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                            <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
                                <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    Personal Information
                                </h5>
                            </div>
                            
                            <div class="p-8 space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">First Name *</label>
                                        <input type="text" name="first_name" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all" placeholder="Enter first name">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Last Name *</label>
                                        <input type="text" name="last_name" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all" placeholder="Enter last name">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email Address *</label>
                                        <div class="relative">
                                            <input type="email" name="email" required
                                                class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all" placeholder="name@example.com">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i class="fas fa-envelope text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Phone Number *</label>
                                        <div class="relative">
                                            <input type="tel" id="phoneInput" name="phone" required
                                                class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all" placeholder="0912 345 6789">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i class="fas fa-phone text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                            <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
                                <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    Booking Details
                                </h5>
                            </div>
                            
                            <div class="p-8 space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Check-in Date *</label>
                                        <input type="date" name="check_in" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Check-out Date *</label>
                                        <input type="date" name="check_out" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Number of Guests *</label>
                                        <input type="number" name="guests" min="1" value="2" required
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                                            id="guestsInput">
                                        
                                        <small class="text-slate-500 text-xs mt-2 block flex items-center gap-1" id="capacityHint">
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
                                                <i class="fas fa-info-circle"></i> Max capacity for selected rooms: <strong>{{ $totalCapacity }} guests</strong>
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Special Requests</label>
                                        <input type="text" name="special_requests"
                                            placeholder="e.g., Ground floor, late arrival"
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                            <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30 flex justify-between items-center">
                                <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
                                    <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                        <i class="fas fa-wallet"></i>
                                    </span>
                                    Payment
                                </h5>
                                @php
                                    $book_cart_total = collect(session('cart') ?? [])->sum(function ($item) {
                                        return ($item['room_price'] ?? 0) * ($item['quantity'] ?? 0);
                                    });
                                @endphp
                            </div>
                            
                            <div class="p-8">
                                <div class="grid md:grid-cols-12 gap-8">
                                    
                                    <div class="md:col-span-4 flex flex-col items-center justify-center p-6 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                                        <div class="bg-white p-2 rounded-xl shadow-sm mb-3">
                                            <img src="{{ asset('storage/qr_codes/resort_qr.png') }}" alt="Scan to Pay"
                                                class="w-40 h-40 object-contain">
                                        </div>
                                        <p class="text-sm font-semibold text-teal-800">Scan QR to Pay</p>
                                        <p class="text-xs text-slate-500">Supported E-Wallets & Banks</p>
                                    </div>

                                    <div class="md:col-span-8 space-y-6">
                                        
                                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                                            <span class="text-yellow-800 font-medium">Total Amount Due</span>
                                            <span id="amountDueBook" class="text-xl font-bold text-teal-900">
                                                PHP {{ number_format(($book_cart_total ?? 0) * 1, 2) }}
                                            </span>
                                            <input type="hidden" name="payment_multiplier" value="1">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mode of Payment *</label>
                                            <div class="relative">
                                                <select name="payment_method" id="paymentMethodSelect" required
                                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none appearance-none bg-white">
                                                    <option value="" disabled selected>Select a payment method</option>
                                                    <option value="gcash">GCash</option>
                                                    <option value="paymaya">PayMaya</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="bankFieldsBook" class="hidden space-y-4 p-5 bg-slate-50 rounded-xl border border-slate-200">
                                            <h6 class="text-sm font-bold text-teal-800 border-b border-slate-200 pb-2 mb-2">Bank Transfer Details</h6>
                                            <div class="grid md:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="text-xs text-slate-500 font-bold mb-1 block">Bank Name *</label>
                                                    <input type="text" name="bank_name"
                                                        class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="e.g., BDO">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-slate-500 font-bold mb-1 block">Account Name *</label>
                                                    <input type="text" name="bank_account_name"
                                                        class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="Account Holder">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-slate-500 font-bold mb-1 block">Account Number *</label>
                                                    <input type="text" name="bank_account_number"
                                                        class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="0000-0000-0000">
                                                </div>
                                                <div>
                                                    <label class="text-xs text-slate-500 font-bold mb-1 block">Reference # *</label>
                                                    <input type="text" name="bank_reference"
                                                        class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="Transaction Ref #">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-blue-50 p-4 rounded-xl">
                                            <ul class="list-disc pl-5 text-xs text-blue-800 space-y-1">
                                                <li>Use your email as Reference/Note in your transfer.</li>
                                                <li>Reservations are held for <strong>24 hours</strong> pending payment.</li>
                                            </ul>
                                        </div>

                                        <div>
                                            <label for="payment_proof" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
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
                            <a href="{{ route('booking.index') }}"
                                class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-4 rounded-xl text-center hover:bg-gray-50 transition shadow-sm">
                                Go Back
                            </a>
                            <button type="submit" id="submitBtn"
                                class="flex-[2] bg-teal-700 hover:bg-teal-800 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-teal-700/30 flex items-center justify-center gap-2">
                                Continue to Review <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-4">
                    <div class="sticky top-24">
                        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-xl shadow-teal-900/10 border border-slate-100">
                            
                            <h5 class="text-lg font-bold text-teal-900 mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                                <i class="fas fa-receipt text-yellow-500"></i> Booking Receipt
                            </h5>

                            <div id="cartItems" class="max-h-[400px] overflow-y-auto mb-6 custom-scrollbar pr-2">
                                @if (empty(session('cart')))
                                    <div class="text-center py-10">
                                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 text-2xl">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <p class="text-slate-500 text-sm">Your cart is empty</p>
                                    </div>
                                @else
                                    @foreach (session('cart') as $item)
                                        <div class="flex gap-4 mb-6 relative group">
                                            <div class="w-16 h-16 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                                                <i class="fas fa-bed text-xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h6 class="text-teal-900 font-bold text-sm mb-1 leading-tight">
                                                    {{ $item['room_name'] }}
                                                </h6>
                                                <div class="flex justify-between items-center text-xs text-slate-500 mb-1">
                                                    <span>{{ number_format($item['room_price'], 2) }} / night</span>
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <span class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600 font-medium">Qty: {{ $item['quantity'] }}</span>
                                                    <span class="text-teal-700 font-bold text-sm">
                                                        PHP {{ number_format($item['room_price'] * $item['quantity'], 2) }}
                                                    </span>
                                                </div>
                                            </div>
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
                                <div class="border-t-2 border-dashed border-slate-200 pt-6 space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Subtotal</span>
                                        <span class="font-semibold text-slate-700">PHP {{ number_format($cart_total, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Taxes & Fees (12%)</span>
                                        <span class="font-semibold text-slate-700">PHP {{ number_format($tax, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-end pt-2">
                                        <span class="text-base font-bold text-teal-900">Total</span>
                                        <span class="text-2xl font-bold text-teal-700">PHP {{ number_format($total, 2) }}</span>
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
                const checkIn = document.querySelector('input[name="check_in"]');
                const checkOut = document.querySelector('input[name="check_out"]');
                
                if(checkIn) checkIn.min = today;
                if(checkOut) checkOut.min = today;

                // Disable submit button if no items in cart
                @if (empty(session('cart')))
                    const submitBtn = document.getElementById('submitBtn');
                    if(submitBtn) {
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
                        if(isBank) {
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
    @endpush
@endsection