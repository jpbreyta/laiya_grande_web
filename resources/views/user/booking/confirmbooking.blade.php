@extends('user.layouts.app')

@section('content')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        .font-luxury {
            font-family: 'Playfair Display', serif;
        }

        .font-body {
            font-family: 'Poppins', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        .modal-animate-in {
            animation: modalIn 0.3s ease-out forwards;
        }

        .modal-animate-out {
            animation: modalOut 0.2s ease-in forwards;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes modalOut {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }

            to {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }
        }
    </style>

    <section class="min-h-screen bg-slate-50 py-16 font-body">
        <div class="container mx-auto px-4 max-w-7xl">

            <div class="mb-10 text-center">
                <h1 class="text-4xl font-luxury font-bold text-teal-900 mb-2">Review Your Booking</h1>
                <p class="text-slate-500 text-lg font-light">Please verify all details before confirming your reservation.
                </p>
            </div>

            <div class="max-w-2xl mx-auto mb-12">
                <div class="flex items-center justify-center relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="h-1 w-full bg-teal-100 rounded-full"></div>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div
                            class="h-10 w-10 bg-teal-700 rounded-full flex items-center justify-center z-10 shadow-md ring-4 ring-slate-50">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span
                            class="absolute -bottom-8 w-max text-xs font-bold text-teal-800 uppercase tracking-widest">Details</span>
                    </div>
                    <div class="relative flex flex-col items-center ml-32 sm:ml-48">
                        <div
                            class="h-10 w-10 bg-teal-700 rounded-full flex items-center justify-center border-4 border-slate-50 z-10 shadow-md">
                            <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <span
                            class="absolute -bottom-8 w-max text-xs font-bold text-teal-800 uppercase tracking-widest">Confirm</span>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">

                <div class="lg:col-span-8 space-y-8">

                    <div class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                        <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
                            <h5 class="text-xl font-luxury font-bold text-teal-900 flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                    <i class="fas fa-user-check"></i>
                                </span>
                                Guest Information
                            </h5>
                        </div>
                        <div class="p-8">
                            <div class="grid md:grid-cols-2 gap-y-6 gap-x-8">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Full Name
                                    </p>
                                    <p class="font-normal text-teal-900 text-lg">{{ $request->first_name }}
                                        {{ $request->last_name }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email
                                        Address</p>
                                    <p class="font-normal text-teal-900 text-lg">{{ $request->email }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Phone
                                        Number</p>
                                    <p class="font-normal text-teal-900 text-lg">{{ $request->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total
                                        Guests</p>
                                    <p class="font-normal text-teal-900 text-lg">{{ $request->guests }} Guest(s)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                        <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
                            <h5 class="text-xl font-luxury font-bold text-teal-900 flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                    <i class="fas fa-calendar-check"></i>
                                </span>
                                Stay Details
                            </h5>
                        </div>
                        <div class="p-8">
                            <div class="grid md:grid-cols-2 gap-y-6 gap-x-8">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Check-in
                                    </p>
                                    <p class="font-semibold text-teal-900 text-lg font-luxury">
                                        {{ \Carbon\Carbon::parse($request->check_in)->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Check-out
                                    </p>
                                    <p class="font-semibold text-teal-900 text-lg font-luxury">
                                        {{ \Carbon\Carbon::parse($request->check_out)->format('M d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Duration
                                    </p>
                                    <p class="font-normal text-teal-900 text-lg">{{ $nights }} Night(s)</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Payment
                                        Method</p>
                                    <p class="font-normal text-teal-900 text-lg capitalize">
                                        {{ str_replace('_', ' ', $request->payment_method) }}
                                    </p>
                                </div>
                            </div>

                            @if ($request->special_requests)
                                <div class="mt-6 pt-6 border-t border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Special
                                        Requests</p>
                                    <div
                                        class="bg-yellow-50 text-yellow-800 p-4 rounded-xl text-sm font-normal border border-yellow-100 italic">
                                        "{{ $request->special_requests }}"
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white shadow-xl shadow-teal-900/5 rounded-3xl overflow-hidden border border-slate-100">
                        <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
                            <h5 class="text-xl font-luxury font-bold text-teal-900 flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                                Payment Verification
                            </h5>
                        </div>
                        <div class="p-8">
                            <div
                                class="flex flex-col items-center justify-center bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6">
                                <img src="{{ $paymentProofUrl }}" alt="Payment Proof"
                                    class="rounded-lg shadow-sm max-h-80 object-contain mb-4">
                                <p class="text-xs text-slate-500 flex items-center gap-2 font-medium">
                                    <i class="fas fa-search"></i>
                                    Image uploaded for admin verification
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('user.booking.confirm') }}" id="processBookingForm"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="first_name" value="{{ $request->first_name }}">
                        <input type="hidden" name="last_name" value="{{ $request->last_name }}">
                        <input type="hidden" name="email" value="{{ $request->email }}">
                        <input type="hidden" name="phone" value="{{ $request->phone }}">
                        <input type="hidden" name="check_in"
                            value="{{ session('booking_check_in', $request->check_in) }}">
                        <input type="hidden" name="check_out"
                            value="{{ session('booking_check_out', $request->check_out) }}">
                        <input type="hidden" name="guests" value="{{ $request->guests }}">
                        <input type="hidden" name="special_requests" value="{{ $request->special_requests }}">
                        <input type="hidden" name="total_price" value="{{ $total }}">
                        <input type="hidden" name="payment_method" value="{{ $request->payment_method }}">
                        <input type="hidden" name="payment_proof_temp" value="{{ $paymentProofPath }}">

                        <div
                            class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 mb-8 flex gap-4 items-start shadow-sm hover:bg-yellow-100/50 transition-colors">
                            <div class="flex h-6 items-center">
                                <input type="checkbox" id="termsCheckbox" name="agree_terms" required
                                    class="h-5 w-5 rounded border-gray-300 text-teal-600 focus:ring-teal-500 cursor-pointer accent-teal-600">
                            </div>
                            <label for="termsCheckbox" class="text-slate-700 text-sm font-normal leading-relaxed">
                                I verify that the information above is correct and I agree to the
                                <button type="button" onclick="openPolicyModal()"
                                    class="text-teal-700 font-bold hover:underline hover:text-teal-900 transition-colors">Terms
                                    and Conditions</button> and
                                <button type="button" onclick="openPolicyModal()"
                                    class="text-teal-700 font-bold hover:underline hover:text-teal-900 transition-colors">Cancellation
                                    Policy</button> of Laiya Grande Beach Resort.
                            </label>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row gap-4">
                            <button type="button" onclick="history.back()"
                                class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-4 rounded-xl text-center hover:bg-gray-50 transition shadow-sm uppercase tracking-widest text-xs">
                                Back to Edit
                            </button>
                            <button type="button" id="completeBookingBtn" onclick="completeBooking()"
                                class="flex-[2] bg-teal-700 hover:bg-teal-800 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-teal-700/30 flex items-center justify-center gap-2 uppercase tracking-widest text-xs">
                                Confirm & Book Now <i class="fas fa-check-circle"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="lg:col-span-4">
                    <div class="sticky top-24">
                        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-xl shadow-teal-900/10 border border-slate-100">

                            <h5
                                class="text-lg font-luxury font-bold text-teal-900 mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                                <i class="fas fa-receipt text-yellow-500 text-base"></i> Final Summary
                            </h5>

                            <div class="max-h-[300px] overflow-y-auto mb-6 custom-scrollbar pr-2">
                                @if (empty($cart))
                                    <p class="text-slate-500 text-center py-4 text-sm">No items selected</p>
                                @else
                                    @foreach ($cart as $item)
                                        <div
                                            class="flex gap-4 mb-4 relative group border-b border-dashed border-slate-100 pb-4 last:border-0">
                                            <div class="flex-1">
                                                <h6 class="text-teal-900 font-bold text-sm mb-1 leading-tight font-body">
                                                    {{ $item['room_name'] }}
                                                </h6>
                                                <div class="text-xs text-slate-500 mb-2">
                                                    PHP {{ number_format($item['room_price'], 2) }} ×
                                                    {{ $item['quantity'] }} room(s) × {{ $nights ?? 1 }} night(s)
                                                </div>
                                                <div class="flex justify-between items-center mt-1">
                                                    <span
                                                        class="text-[10px] bg-slate-100 px-2 py-1 rounded text-slate-600 font-bold uppercase tracking-wide">Qty:
                                                        {{ $item['quantity'] }}</span>
                                                    <span class="text-teal-700 font-bold text-sm">
                                                        PHP
                                                        {{ number_format($item['room_price'] * $item['quantity'] * ($nights ?? 1), 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            @if (!empty($cart))
                                <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500 font-medium">Subtotal (per night)</span>
                                        <span class="font-bold text-slate-700">PHP
                                            {{ number_format($cartSubtotal ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500 font-medium">Number of Nights</span>
                                        <span class="font-bold text-slate-700">{{ $nights ?? 1 }} night(s)</span>
                                    </div>
                                    <div class="border-t border-slate-200 pt-3 flex justify-between items-end">
                                        <span class="text-sm font-bold text-teal-900 uppercase tracking-wider">Total
                                            Due</span>
                                        <span class="text-2xl font-bold text-teal-700 font-luxury">PHP
                                            {{ number_format($total ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            @endif

                            <div
                                class="mt-6 flex items-center justify-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                <i class="fas fa-lock"></i> Secure SSL Encrypted Transaction
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div id="policyModal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="absolute inset-0 bg-teal-950/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop">
        </div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-teal-100 opacity-0 scale-95"
                    id="modalPanel">

                    <div class="bg-teal-900 px-6 py-5 flex justify-between items-center">
                        <h3 class="text-xl font-luxury font-bold text-white flex items-center gap-3">
                            <i class="fas fa-file-contract text-yellow-400"></i> Resort Policies
                        </h3>
                        <button type="button" onclick="closePolicyModal()"
                            class="text-teal-200 hover:text-white transition-colors focus:outline-none bg-white/10 hover:bg-white/20 rounded-full w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>

                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto custom-scrollbar bg-slate-50 font-body">

                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-4 border-b border-slate-200 pb-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm shadow-sm">1</span>
                                <h4 class="text-lg font-bold text-teal-900 font-luxury">Terms and Conditions</h4>
                            </div>

                            <div class="space-y-4">
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div
                                        class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-teal-300 transition-colors">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-wider">
                                            Overnight Stay</p>
                                        <div class="flex flex-col gap-1 text-sm font-medium text-slate-700">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-sign-in-alt text-teal-600 w-4"></i> Check-in: <span
                                                    class="font-bold text-teal-900">2:00 PM</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-sign-out-alt text-teal-600 w-4"></i> Check-out: <span
                                                    class="font-bold text-teal-900">12:00 NN</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-yellow-400 transition-colors">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-2 tracking-wider">Day
                                            Tour</p>
                                        <div class="flex flex-col gap-1 text-sm font-medium text-slate-700">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-sun text-yellow-500 w-4"></i> Check-in: <span
                                                    class="font-bold text-teal-900">8:00 AM</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-moon text-teal-600 w-4"></i> Check-out: <span
                                                    class="font-bold text-teal-900">5:00 PM</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <ul class="space-y-3 mt-4">
                                    <li class="flex items-start gap-3 bg-white p-3 rounded-lg border border-slate-100">
                                        <div
                                            class="mt-1 w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-teal-600 flex-shrink-0">
                                            <i class="fas fa-plug text-[10px]"></i>
                                        </div>
                                        <span class="text-sm text-slate-600 leading-relaxed">Bringing of <strong>electrical
                                                appliances</strong> (rice cooker, induction, etc.) will be charged <span
                                                class="bg-yellow-100 text-teal-900 px-1 rounded font-bold">PHP 500.00
                                                each</span>.</span>
                                    </li>
                                    <li class="flex items-start gap-3 bg-white p-3 rounded-lg border border-slate-100">
                                        <div
                                            class="mt-1 w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-teal-600 flex-shrink-0">
                                            <i class="fas fa-key text-[10px]"></i>
                                        </div>
                                        <span class="text-sm text-slate-600 leading-relaxed"><strong>Lost Key</strong>
                                            penalty fee of <span
                                                class="bg-yellow-100 text-teal-900 px-1 rounded font-bold">PHP
                                                500.00</span>.</span>
                                    </li>
                                    <li class="flex items-start gap-3 bg-white p-3 rounded-lg border border-slate-100">
                                        <div
                                            class="mt-1 w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-teal-600 flex-shrink-0">
                                            <i class="fas fa-swimmer text-[10px]"></i>
                                        </div>
                                        <span class="text-sm text-slate-600 leading-relaxed">Swimming Time: <strong>6:00 AM
                                                – 6:00 PM</strong>. Swimming under the influence of alcohol is <span
                                                class="text-red-600 font-bold uppercase text-xs tracking-wider">Strictly
                                                Prohibited</span>.</span>
                                    </li>
                                    <li class="flex items-start gap-3 bg-white p-3 rounded-lg border border-slate-100">
                                        <div
                                            class="mt-1 w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-teal-600 flex-shrink-0">
                                            <i class="fas fa-door-closed text-[10px]"></i>
                                        </div>
                                        <span class="text-sm text-slate-600 leading-relaxed">Beachfront gate closes at
                                            <strong>10:00 PM</strong>.</span>
                                    </li>
                                    <li class="flex items-start gap-3 bg-white p-3 rounded-lg border border-slate-100">
                                        <div
                                            class="mt-1 w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-teal-600 flex-shrink-0">
                                            <i class="fas fa-volume-mute text-[10px]"></i>
                                        </div>
                                        <span class="text-sm text-slate-600 leading-relaxed">Loud noise/sounds are strictly
                                            prohibited from <strong>10:00 PM - 8:00 AM</strong>.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-3 mb-4 border-b border-slate-200 pb-2">
                                <span
                                    class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold text-sm shadow-sm">2</span>
                                <h4 class="text-lg font-bold text-teal-900 font-luxury">Cancellation Policy</h4>
                            </div>

                            <div class="bg-white border-l-4 border-yellow-400 rounded-r-xl p-5 shadow-sm space-y-4">
                                <div class="flex gap-4 items-start">
                                    <i class="fas fa-ban text-yellow-500 mt-1"></i>
                                    <p class="text-sm text-slate-700 font-medium leading-relaxed">Confirmed bookings are
                                        <strong class="text-teal-900">NON-REFUNDABLE</strong>.
                                    </p>
                                </div>
                                <div class="flex gap-4 items-start">
                                    <i class="fas fa-cloud-showers-heavy text-yellow-500 mt-1"></i>
                                    <p class="text-sm text-slate-700 font-medium leading-relaxed">Confirmed bookings can
                                        only be <strong>RE-SCHEDULED</strong> or <strong>MODIFIED</strong> if due to a
                                        TYPHOON.</p>
                                </div>
                                <div class="flex gap-4 items-start">
                                    <i class="fas fa-user-slash text-yellow-500 mt-1"></i>
                                    <p class="text-sm text-slate-700 font-medium leading-relaxed">Failure to arrive at the
                                        resort shall be considered a <strong class="text-teal-900">NO SHOW</strong> and
                                        will be charged 100%.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-slate-200">
                        <button type="button" onclick="closePolicyModal()"
                            class="inline-flex w-full justify-center rounded-xl bg-teal-700 px-8 py-3 text-sm font-bold text-white shadow-sm hover:bg-teal-800 sm:ml-3 sm:w-auto transition-colors uppercase tracking-widest">
                            I Understand
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const modal = document.getElementById('policyModal');
        const backdrop = document.getElementById('modalBackdrop');
        const panel = document.getElementById('modalPanel');

        function openPolicyModal() {
            modal.classList.remove('hidden');

            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'scale-95');
                panel.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function closePolicyModal() {
            backdrop.classList.add('opacity-0');
            panel.classList.remove('opacity-100', 'scale-100');
            panel.classList.add('opacity-0', 'scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        backdrop.addEventListener('click', closePolicyModal);

        function completeBooking() {
            const termsCheckbox = document.getElementById('termsCheckbox');
            const submitBtn = document.getElementById('completeBookingBtn');

            if (!termsCheckbox.checked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Agreement Required',
                    text: 'Please review and agree to the Terms and Cancellation Policy.',
                    confirmButtonColor: '#0f766e'
                });
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

            Swal.fire({
                title: 'Processing user.booking...',
                text: 'Please wait while we process your user.booking.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(document.getElementById('processBookingForm'));

            fetch('{{ route('user.booking.confirm') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Booking Complete!',
                            html: `
                    <p class="font-body">Your booking is pending admin approval.<br>
                    You will receive a confirmation email shortly.</p>
                    <hr class="my-3 border-slate-100">
                    <div class="text-left bg-slate-50 p-4 rounded-lg mb-3">
                        <p class="text-sm font-semibold text-teal-900 mb-2">
                            <i class="fas fa-info-circle text-teal-600 mr-1"></i>
                            Booking Details:
                        </p>
                        <p class="text-sm text-gray-700 mb-1">
                            <strong>Booking ID:</strong> ${data.booking_id}
                        </p>
                        <p class="text-sm text-gray-700">
                            <strong>Reservation Number:</strong> ${data.reservation_number}
                        </p>
                    </div>
                    <p class="text-gray-600 text-sm font-body">
                        <i class="fas fa-envelope text-teal-600 mr-1"></i>
                        Booking details and QR code will be sent to <b>{{ $request->email }}</b>
                    </p>
                `,
                            confirmButtonText: 'Return to Home',
                            confirmButtonColor: '#0f766e'
                        }).then(() => {
                            window.location.href = '{{ route('home') }}';
                        });
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Confirm & Book Now <i class="fas fa-check-circle ml-1"></i>';

                        Swal.fire({
                            icon: 'error',
                            title: 'Booking Failed!',
                            text: data.message || 'Something went wrong.',
                            confirmButtonColor: '#0f766e'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Confirm & Book Now <i class="fas fa-check-circle ml-1"></i>';

                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your user.booking.',
                        confirmButtonColor: '#0f766e'
                    });
                });
        }
    </script>
@endsection
