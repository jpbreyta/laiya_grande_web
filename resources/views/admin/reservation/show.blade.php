@extends('admin.layouts.app')

@section('content')
    <section class="bg-gradient-to-br from-slate-50 via-white to-slate-100 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $room = $reservation->room;
                $roomImages = $room
                    ? (is_array($room->images)
                        ? $room->images
                        : (is_string($room->images)
                            ? json_decode($room->images, true)
                            : []))
                    : [];
                $mainImage = $room ? $room->image ?? null : null;

                $allImages = [];
                if ($mainImage) {
                    $allImages[] = $mainImage;
                }
                if (!empty($roomImages)) {
                    foreach ($roomImages as $image) {
                        if ($image && $image !== $mainImage) {
                            $allImages[] = $image;
                        }
                    }
                }
                $hasImages = !empty($allImages);
            @endphp

            <!-- Hero Header -->
            <div class="relative h-64 md:h-80 rounded-3xl overflow-hidden shadow-2xl mb-8">
                @if ($hasImages)
                    <div id="imageCarousel" class="relative h-full">
                        @foreach ($allImages as $index => $image)
                            <div
                                class="carousel-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                                <img src="{{ asset($image) }}" alt="{{ $room->name ?? 'Room' }} - Image {{ $index + 1 }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @endforeach

                        @if (count($allImages) > 1)
                            <button id="prevBtn"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-slate-800 p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button id="nextBtn"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-slate-800 p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            <div class="absolute bottom-16 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                                @foreach ($allImages as $index => $image)
                                    <button
                                        class="carousel-indicator w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50' }}"
                                        data-index="{{ $index }}"></button>
                                @endforeach
                            </div>

                            <div
                                class="absolute top-4 right-4 bg-black/50 text-white px-3 py-1.5 rounded-full text-xs font-semibold z-10">
                                <span id="currentImage">1</span> / <span id="totalImages">{{ count($allImages) }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-teal-600 via-emerald-500 to-cyan-500"></div>
                @endif

                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent pointer-events-none">
                </div>

                <div class="absolute top-0 left-0 p-6 md:p-8 z-10">
                    <div
                        class="inline-block bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-xl border border-white/20 shadow-lg">
                        <h1
                            class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-teal-100 to-white tracking-tight drop-shadow-lg">
                            Reservation Details
                        </h1>
                    </div>
                </div>

                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 z-10">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-14 w-14 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white font-bold text-lg shadow-lg ring-2 ring-white/20">
                                {{ strtoupper(substr($reservation->firstname, 0, 1) . substr($reservation->lastname, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xl md:text-2xl font-bold text-white drop-shadow-lg">
                                    {{ $reservation->firstname }} {{ $reservation->lastname }}</p>
                                <p class="text-sm text-gray-200">{{ $reservation->room->name ?? 'Room N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span id="statusBadge"
                                class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-lg
                                @if ($reservation->status === 'pending') bg-gradient-to-r from-amber-400 to-yellow-400 text-white border border-amber-300
                                @elseif($reservation->status === 'confirmed') bg-gradient-to-r from-green-400 to-emerald-400 text-white border border-green-300
                                @elseif($reservation->status === 'paid') bg-gradient-to-r from-blue-400 to-indigo-400 text-white border border-blue-300
                                @else bg-gradient-to-r from-red-400 to-rose-400 text-white border border-red-300 @endif">
                                <span class="w-2 h-2 rounded-full mr-2 bg-white"></span>
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="p-6 md:p-8">
                    <!-- Guest & Reservation Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Left Column: Guest Information -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Guest Information
                            </h3>

                            <div
                                class="p-5 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-100 shadow-sm">
                                <div class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1">Guest Name</div>
                                <div class="text-lg font-semibold text-slate-900">{{ $reservation->firstname }}
                                    {{ $reservation->lastname }}</div>
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Email Address
                                </div>
                                <div class="text-sm font-medium text-slate-900">{{ $reservation->email }}</div>
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Phone Number
                                </div>
                                <div class="text-sm font-medium text-slate-900">{{ $reservation->phone_number }}</div>
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Number of Guests
                                </div>
                                <div class="text-sm font-medium text-slate-900">{{ $reservation->number_of_guests }} guests
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Reservation Details -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Reservation Details
                            </h3>

                            <div
                                class="p-5 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100 shadow-sm">
                                <div class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1">Room</div>
                                <div class="text-lg font-semibold text-slate-900">{{ $reservation->room->name ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Check-in Date
                                </div>
                                <div class="text-sm font-medium text-slate-900">
                                    {{ \Carbon\Carbon::parse($reservation->check_in)->format('M d, Y') }}</div>
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Check-out Date
                                </div>
                                <div class="text-sm font-medium text-slate-900">
                                    {{ \Carbon\Carbon::parse($reservation->check_out)->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount & Status Highlight -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="p-6 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl shadow-lg">
                            <div class="text-xs font-bold text-emerald-50 uppercase tracking-wider mb-2">Total Amount</div>
                            <div class="text-3xl font-black text-white">
                                ₱{{ number_format($reservation->total_price, 2) }}
                            </div>
                        </div>

                        <div class="p-6 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                            <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Status</div>
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-sm
                                @if ($reservation->status === 'pending') bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200
                                @elseif($reservation->status === 'confirmed') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200
                                @elseif($reservation->status === 'paid') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200
                                @else bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200 @endif">
                                <span
                                    class="w-2 h-2 rounded-full mr-2 {{ $reservation->status === 'confirmed' ? 'bg-green-500' : ($reservation->status === 'pending' ? 'bg-amber-500' : ($reservation->status === 'paid' ? 'bg-blue-500' : 'bg-red-500')) }}"></span>
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Reservation Code Section -->
                    <div
                        class="p-6 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-200 shadow-sm mb-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <div class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1">Reservation Code
                                </div>
                                <div class="text-2xl font-black text-slate-900 font-mono tracking-wide">
                                    {{ $reservation->reservation_number ?? $reservation->id }}
                                </div>
                            </div>
                            <button id="openPassModal"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-5 py-2.5 text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                                <i class="fas fa-qrcode"></i>
                                View QR & Code
                            </button>
                        </div>
                    </div>

                    <!-- Payment Information Section -->
                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Payment Information</h3>

                        <!-- Payment Summary Card -->
                        <div
                            class="p-6 bg-gradient-to-r from-teal-50 via-emerald-50 to-cyan-50 rounded-2xl border-2 border-teal-200 shadow-lg mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-12 w-12 rounded-xl bg-white/60 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-credit-card text-teal-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-teal-600 uppercase tracking-wider">Payment Method
                                        </div>
                                        <div class="text-sm font-bold text-slate-900">
                                            {{ ucfirst($reservation->payment_method ?? 'N/A') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-12 w-12 rounded-xl bg-white/60 flex items-center justify-center shadow-sm">
                                        <i class="fas fa-calendar-alt text-cyan-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-cyan-600 uppercase tracking-wider">Reservation
                                            Date</div>
                                        <div class="text-sm font-bold text-slate-900">
                                            {{ $reservation->created_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-12 w-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center shadow-md">
                                        <i class="fas fa-money-bill-wave text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Total
                                            Amount</div>
                                        <div class="text-lg font-black text-emerald-700">
                                            ₱{{ number_format($reservation->total_price, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- First Payment Section -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4
                                    class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-receipt text-teal-600"></i>
                                    First Payment
                                </h4>
                                <div class="flex items-center gap-2">
                                    @if ($reservation->first_payment && file_exists(storage_path('app/public/' . $reservation->first_payment)))
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Uploaded
                                        </span>
                                        <button type="button" id="processFirstPaymentOCRBtn"
                                            class="inline-flex items-center gap-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-3 py-1.5 text-xs font-semibold shadow-sm transition-all duration-200">
                                            <i class="fas fa-robot"></i>
                                            Process OCR
                                        </button>
                                    @endif
                                </div>
                            </div>

                            @if ($reservation->first_payment && file_exists(storage_path('app/public/' . $reservation->first_payment)))
                                <div
                                    class="relative rounded-xl overflow-hidden border-2 border-slate-300 shadow-lg bg-white p-4">
                                    <img src="{{ asset('storage/' . $reservation->first_payment) }}"
                                        alt="First Payment Proof" class="w-full h-auto max-h-96 object-contain mx-auto">
                                </div>
                            @elseif($reservation->first_payment)
                                <div
                                    class="p-6 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border-2 border-red-200">
                                    <div class="flex items-center gap-3 text-red-700">
                                        <i class="fas fa-exclamation-triangle text-xl"></i>
                                        <div>
                                            <p class="font-semibold">Payment proof file not found</p>
                                            <p class="text-xs text-red-600 mt-1">Path: {{ $reservation->first_payment }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="p-12 bg-white rounded-xl border-2 border-dashed border-slate-300 text-center">
                                    <i class="fas fa-receipt text-5xl text-slate-300 mb-3"></i>
                                    <p class="text-sm font-medium text-slate-500">No payment proof uploaded</p>
                                    <p class="text-xs text-slate-400 mt-1">Payment proof will appear here once uploaded</p>
                                </div>
                            @endif
                        </div>

                        <!-- Second Payment Section -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4
                                    class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-receipt text-teal-600"></i>
                                    Second Payment
                                </h4>
                                <div class="flex items-center gap-2">
                                    @if ($reservation->second_payment && file_exists(storage_path('app/public/' . $reservation->second_payment)))
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Uploaded
                                        </span>
                                        <button type="button" id="processSecondPaymentOCRBtn"
                                            class="inline-flex items-center gap-2 rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-3 py-1.5 text-xs font-semibold shadow-sm transition-all duration-200">
                                            <i class="fas fa-robot"></i>
                                            Process OCR
                                        </button>
                                    @endif
                                </div>
                            </div>

                            @if ($reservation->second_payment && file_exists(storage_path('app/public/' . $reservation->second_payment)))
                                <div
                                    class="relative rounded-xl overflow-hidden border-2 border-slate-300 shadow-lg bg-white p-4">
                                    <img src="{{ asset('storage/' . $reservation->second_payment) }}"
                                        alt="Second Payment Proof" class="w-full h-auto max-h-96 object-contain mx-auto">
                                </div>
                            @elseif($reservation->second_payment)
                                <div
                                    class="p-6 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border-2 border-red-200">
                                    <div class="flex items-center gap-3 text-red-700">
                                        <i class="fas fa-exclamation-triangle text-xl"></i>
                                        <div>
                                            <p class="font-semibold">Payment proof file not found</p>
                                            <p class="text-xs text-red-600 mt-1">Path: {{ $reservation->second_payment }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="p-12 bg-white rounded-xl border-2 border-dashed border-slate-300 text-center">
                                    <i class="fas fa-receipt text-5xl text-slate-300 mb-3"></i>
                                    <p class="text-sm font-medium text-slate-500">No payment proof uploaded</p>
                                    <p class="text-xs text-slate-400 mt-1">Payment proof will appear here once uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-slate-200" id="actionButtons">
                        @if ($reservation->status === 'pending')
                            <form id="approveForm" action="{{ route('admin.reservation.approve', $reservation->id) }}"
                                method="POST" class="inline">
                                @csrf
                                <button type="button" id="approveBtn"
                                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-6 py-2.5 text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                                    <i class="fas fa-check-circle"></i>
                                    Confirm & Move to Booking
                                </button>
                            </form>

                            <form id="rejectForm" action="{{ route('admin.reservation.cancel', $reservation->id) }}"
                                method="POST" class="inline">
                                @csrf
                                <button type="button" id="rejectBtn"
                                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white px-6 py-2.5 text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                                    <i class="fas fa-times-circle"></i>
                                    Cancel Reservation
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.reservation.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-6 py-2.5 text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-arrow-left"></i>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reservation Pass Modal -->
    <div id="reservationPassOverlay" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>
    <div id="reservationPassModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <div
                class="px-6 py-4 border-b bg-gradient-to-r from-teal-600 to-emerald-600 text-white flex items-center justify-between">
                <h3 class="text-lg font-bold">Reservation Pass</h3>
                <button class="text-white/80 hover:text-white transition" data-close-reservation-pass>&times;</button>
            </div>
            <div id="reservationPassPrintArea" class="p-6 space-y-4">
                <div>
                    <div class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Reservation Code</div>
                    <div id="reservationCodeValue" class="text-2xl font-extrabold text-slate-900 tracking-wide font-mono">
                        {{ $reservation->reservation_number ?? $reservation->id }}
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <div class="bg-white p-4 rounded-xl shadow border">
                        {!! QrCode::size(200)->margin(1)->generate($reservation->reservation_number ?? $reservation->id) !!}
                    </div>
                </div>
                <p class="text-xs text-slate-500 text-center">Present this at the front desk for quick verification.</p>
            </div>
            <div class="px-6 py-4 border-t bg-slate-50 flex items-center justify-end gap-2">
                <button id="copyReservationCodeBtn"
                    class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 transition">Copy
                    Code</button>
                <button id="printReservationPassBtn"
                    class="px-4 py-2 rounded-lg bg-gradient-to-r from-teal-600 to-emerald-600 text-white hover:from-teal-700 hover:to-emerald-700 transition">Print</button>
                <button class="px-4 py-2 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 transition"
                    data-close-reservation-pass>Close</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Reservation Pass Modal
        (function() {
            const openBtn = document.getElementById('openPassModal');
            const modal = document.getElementById('reservationPassModal');
            const overlay = document.getElementById('reservationPassOverlay');
            const closeBtns = document.querySelectorAll('[data-close-reservation-pass]');
            const copyBtn = document.getElementById('copyReservationCodeBtn');
            const printBtn = document.getElementById('printReservationPassBtn');

            const toggle = (show) => {
                if (!modal || !overlay) return;
                if (show) {
                    overlay.classList.remove('hidden');
                    modal.classList.remove('hidden');
                } else {
                    overlay.classList.add('hidden');
                    modal.classList.add('hidden');
                }
            };

            openBtn?.addEventListener('click', () => toggle(true));
            overlay?.addEventListener('click', () => toggle(false));
            closeBtns.forEach(btn => btn.addEventListener('click', () => toggle(false)));

            copyBtn?.addEventListener('click', () => {
                const codeEl = document.getElementById('reservationCodeValue');
                if (!codeEl) return;
                navigator.clipboard.writeText(codeEl.textContent.trim()).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied',
                        text: 'Reservation code copied.',
                        timer: 1200,
                        showConfirmButton: false
                    });
                });
            });

            printBtn?.addEventListener('click', () => {
                const printArea = document.getElementById('reservationPassPrintArea');
                if (!printArea) return;
                const win = window.open('', 'PRINT', 'height=600,width=800');
                win.document.write('<html><head><title>Reservation Pass</title>');
                win.document.write(
                    '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">'
                );
                win.document.write('</head><body>');
                win.document.write(printArea.innerHTML);
                win.document.write('</body></html>');
                win.document.close();
                win.focus();
                win.print();
                win.close();
            });
        })();

        // Image Carousel Functionality
        (function() {
            const slides = document.querySelectorAll('.carousel-slide');
            const indicators = document.querySelectorAll('.carousel-indicator');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const currentImageSpan = document.getElementById('currentImage');
            let currentIndex = 0;
            const totalImages = slides.length;

            if (totalImages <= 1) return;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.remove('active');
                    slide.style.opacity = i === index ? '1' : '0';
                });

                indicators.forEach((indicator, i) => {
                    if (i === index) {
                        indicator.classList.add('bg-white', 'w-8');
                        indicator.classList.remove('bg-white/50');
                    } else {
                        indicator.classList.remove('bg-white', 'w-8');
                        indicator.classList.add('bg-white/50');
                    }
                });

                if (currentImageSpan) {
                    currentImageSpan.textContent = index + 1;
                }

                currentIndex = index;
            }

            function nextSlide() {
                const nextIndex = (currentIndex + 1) % totalImages;
                showSlide(nextIndex);
            }

            function prevSlide() {
                const prevIndex = (currentIndex - 1 + totalImages) % totalImages;
                showSlide(prevIndex);
            }

            if (nextBtn) nextBtn.addEventListener('click', nextSlide);
            if (prevBtn) prevBtn.addEventListener('click', prevSlide);

            indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => showSlide(index));
            });
        })();

        const updateReservationUI = (status, message) => {
            const statusBadge = document.getElementById('statusBadge');
            if (statusBadge) {
                let badgeClasses = '';

                if (status === 'confirmed') {
                    badgeClasses = 'bg-gradient-to-r from-green-400 to-emerald-400 text-white border border-green-300';
                } else if (status === 'pending') {
                    badgeClasses = 'bg-gradient-to-r from-amber-400 to-yellow-400 text-white border border-amber-300';
                } else if (status === 'paid') {
                    badgeClasses = 'bg-gradient-to-r from-blue-400 to-indigo-400 text-white border border-blue-300';
                } else {
                    badgeClasses = 'bg-gradient-to-r from-red-400 to-rose-400 text-white border border-red-300';
                }

                statusBadge.innerHTML =
                    `<span class="w-2 h-2 rounded-full mr-2 bg-white"></span>${status.charAt(0).toUpperCase() + status.slice(1)}`;
                statusBadge.className =
                    `inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-lg ${badgeClasses}`;
            }

            const actionButtons = document.getElementById('actionButtons');
            if (status !== 'pending' && actionButtons) {
                actionButtons.querySelector('#approveBtn')?.remove();
                actionButtons.querySelector('#rejectBtn')?.remove();
            }

            Swal.fire({
                icon: status === 'paid' ? 'success' : (status === 'confirmed' ? 'success' : 'warning'),
                title: status === 'paid' ? 'Reservation Confirmed & Moved!' : (status === 'confirmed' ?
                    'Reservation Confirmed!' : 'Reservation Cancelled!'),
                html: status === 'paid' ? `
                    <p>${message}</p>
                    <hr class="my-3">
                    <p class="text-gray-600 text-sm">
                        <i class="fas fa-arrow-right text-blue-600 mr-1"></i>
                        This reservation is now in the <b>Booking</b> page as <b>Pending</b>.
                    </p>
                ` : message,
                confirmButtonColor: '#16a34a'
            });
        };

        document.getElementById('approveBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Confirm this reservation?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirm it!'
            }).then(result => {
                if (!result.isConfirmed) return;

                const form = document.getElementById('approveForm');
                const formData = new FormData(form);

                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) updateReservationUI('confirmed', data.message ||
                            'The reservation has been successfully confirmed.');
                        else Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Something went wrong!',
                            confirmButtonColor: '#d33'
                        });
                    })
                    .catch(() => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process the request.',
                            confirmButtonColor: '#d33'
                        });
                    });
            });
        });

        document.getElementById('rejectBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Cancel this reservation?',
                text: 'This will mark the reservation as cancelled.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then(result => {
                if (!result.isConfirmed) return;

                const form = document.getElementById('rejectForm');
                const formData = new FormData(form);

                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) updateReservationUI('cancelled', data.message ||
                            'The reservation has been cancelled.');
                        else Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Something went wrong!',
                            confirmButtonColor: '#d33'
                        });
                    })
                    .catch(() => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process the request.',
                            confirmButtonColor: '#d33'
                        });
                    });
            });
        });

        // OCR Processing for First Payment
        document.getElementById('processFirstPaymentOCRBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Process First Payment OCR?',
                text: 'This will extract payment information from the uploaded proof.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, process it!'
            }).then(result => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Processing OCR...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`/admin/reservation/{{ $reservation->id }}/process-first-payment-ocr`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'OCR Processed!',
                                text: data.message,
                                confirmButtonColor: '#16a34a'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'OCR Failed',
                                text: data.message || 'Something went wrong!',
                                confirmButtonColor: '#d33'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process OCR request.',
                            confirmButtonColor: '#d33'
                        });
                    });
            });
        });

        // OCR Processing for Second Payment
        document.getElementById('processSecondPaymentOCRBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Process Second Payment OCR?',
                text: 'This will extract payment information from the uploaded proof.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, process it!'
            }).then(result => {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Processing OCR...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`/admin/reservation/{{ $reservation->id }}/process-second-payment-ocr`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'OCR Processed!',
                                text: data.message,
                                confirmButtonColor: '#16a34a'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'OCR Failed',
                                text: data.message || 'Something went wrong!',
                                confirmButtonColor: '#d33'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process OCR request.',
                            confirmButtonColor: '#d33'
                        });
                    });
            });
        });
    </script>
@endsection
