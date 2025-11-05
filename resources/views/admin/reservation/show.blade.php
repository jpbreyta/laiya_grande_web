@extends('admin.layouts.app')

@section('content')
<section class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Hero Header with Room Picture Carousel and Guest Name -->
        @php
            $room = $reservation->room;
            $roomImages = $room ? (is_array($room->images) ? $room->images : (is_string($room->images) ? json_decode($room->images, true) : [])) : [];
            $mainImage = $room ? ($room->image ?? null) : null;
            
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
        
        <div class="relative h-80 md:h-96 rounded-t-2xl overflow-hidden shadow-xl mb-6">
            @if($hasImages)
                <!-- Image Carousel -->
                <div id="imageCarousel" class="relative h-full">
                    @foreach($allImages as $index => $image)
                        <div class="carousel-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 transition-opacity duration-500 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                            <img src="{{ asset($image) }}" 
                                 alt="{{ $room->name ?? 'Room' }} - Image {{ $index + 1 }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    @endforeach
                    
                    <!-- Navigation Buttons -->
                    @if(count($allImages) > 1)
                        <button id="prevBtn" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button id="nextBtn" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        
                        <!-- Image Indicators -->
                        <div class="absolute bottom-20 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                            @foreach($allImages as $index => $image)
                                <button class="carousel-indicator w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50' }}" 
                                        data-index="{{ $index }}"></button>
                            @endforeach
                        </div>
                        
                        <!-- Image Counter -->
                        <div class="absolute top-20 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm font-medium z-10">
                            <span id="currentImage">1</span> / <span id="totalImages">{{ count($allImages) }}</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600"></div>
            @endif
            
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent pointer-events-none"></div>
            
            <!-- Reservation Details Title - Top Left -->
            <div class="absolute top-0 left-0 p-6 md:p-8 z-10">
                <div class="inline-block bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-xl border border-white/20 shadow-lg">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-white tracking-tight drop-shadow-lg">
                        Reservation Details
                    </h1>
                </div>
            </div>
            
            <!-- Guest Name and Status Badge - Bottom -->
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 z-10">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            {{ strtoupper(substr($reservation->firstname, 0, 1) . substr($reservation->lastname, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xl md:text-2xl font-bold text-white">{{ $reservation->firstname }} {{ $reservation->lastname }}</p>
                            <p class="text-sm text-gray-200">{{ $reservation->room->name ?? 'Room N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="statusBadge" class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-lg
                            @if($reservation->status === 'pending') bg-gradient-to-r from-amber-400 to-yellow-400 text-white border border-amber-300
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

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6">
                <!-- Reservation Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                            <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Guest Name</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $reservation->firstname }} {{ $reservation->lastname }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Email</div>
                            <div class="text-sm font-medium text-gray-900">{{ $reservation->email }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Phone Number</div>
                            <div class="text-sm font-medium text-gray-900">{{ $reservation->phone_number }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Number of Guests</div>
                            <div class="text-sm font-medium text-gray-900">{{ $reservation->number_of_guests }}</div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
                            <div class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1">Room</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $reservation->room->name ?? 'N/A' }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Check-in Date</div>
                            <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($reservation->check_in)->format('M d, Y') }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Check-out Date</div>
                            <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($reservation->check_out)->format('M d, Y') }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                            <div class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Total Price</div>
                            <div class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">₱{{ number_format($reservation->total_price, 2) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Status and Payment Method -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                        <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Payment Method</div>
                        <div class="text-sm font-medium text-gray-900">{{ ucfirst($reservation->payment_method ?? 'N/A') }}</div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                        <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Status</div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-sm
                            @if($reservation->status === 'pending') bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200
                            @elseif($reservation->status === 'confirmed') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200
                            @elseif($reservation->status === 'paid') bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200
                            @else bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200 @endif">
                            <span class="w-2 h-2 rounded-full mr-2 {{ $reservation->status === 'confirmed' ? 'bg-green-500' : ($reservation->status === 'pending' ? 'bg-amber-500' : ($reservation->status === 'paid' ? 'bg-blue-500' : 'bg-red-500')) }}"></span>
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>
            </div>

            <!-- Reservation Code + Action -->
            <div class="p-4 rounded-lg border flex items-center justify-between gap-4">
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider mb-1">Reservation Code</div>
                    <div class="text-xl font-bold text-black bg-clip-text">{{ $reservation->reservation_number ?? $reservation->id }}</div>
                </div>
                <button id="openPassModal"
                        class="shrink-0 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                    View QR & Code
                </button>
            </div>

                <!-- Payment Proof -->
            @if($reservation->payment && file_exists(storage_path('app/public/' . $reservation->payment)))
                    <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200" id="paymentProofSection">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Payment Proof</h3>
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/' . $reservation->payment) }}" alt="Payment Proof" class="rounded-lg shadow-md border-2 border-gray-200 max-w-md">
                        </div>
                </div>
            @elseif($reservation->payment)
                    <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-lg border border-red-200 text-red-700" id="paymentProofSection">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                    Payment proof uploaded but file not found!
                        </div>
                </div>
            @endif

                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200" id="actionButtons">
                @if($reservation->status === 'pending')
                    <form id="approveForm" action="{{ route('admin.reservation.approve', $reservation->id) }}" method="POST" class="inline">
                        @csrf
                            <button type="button" id="approveBtn" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                Confirm Reservation
                            </button>
                    </form>

                    <form id="rejectForm" action="{{ route('admin.reservation.cancel', $reservation->id) }}" method="POST" class="inline">
                        @csrf
                            <button type="button" id="rejectBtn" class="bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                Cancel Reservation
                            </button>
                    </form>
                @endif

                    <a href="{{ route('admin.reservation.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
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
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-between">
            <h3 class="text-lg font-bold">Reservation Pass</h3>
            <button class="text-white/80 hover:text-white" data-close-reservation-pass>&times;</button>
        </div>
        <div id="reservationPassPrintArea" class="p-6 space-y-4">
            <div>
                <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Reservation Code</div>
                <div id="reservationCodeValue" class="text-2xl font-extrabold text-gray-900 tracking-wide">
                    {{ $reservation->reservation_number ?? $reservation->id }}
                </div>
            </div>
            <div class="flex items-center justify-center">
                <div class="bg-white p-4 rounded-xl shadow border">
                    {!! QrCode::size(200)->margin(1)->generate(($reservation->reservation_number ?? $reservation->id)) !!}
                </div>
            </div>
            <p class="text-xs text-gray-500 text-center">Present this at the front desk for quick verification.</p>
        </div>
        <div class="px-6 py-4 border-t bg-gray-50 flex items-center justify-end gap-2">
            <button id="copyReservationCodeBtn" class="px-4 py-2 rounded-lg border text-gray-700 hover:bg-gray-100">Copy Code</button>
            <button id="printReservationPassBtn" class="px-4 py-2 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700">Print</button>
            <button class="px-4 py-2 rounded-lg bg-white border hover:bg-gray-50" data-close-reservation-pass>Close</button>
        </div>
    </div>
    </div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Reservation Pass Modal
(function () {
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
            Swal.fire({ icon: 'success', title: 'Copied', text: 'Reservation code copied.', timer: 1200, showConfirmButton: false });
        });
    });

    printBtn?.addEventListener('click', () => {
        const printArea = document.getElementById('reservationPassPrintArea');
        if (!printArea) return;
        const win = window.open('', 'PRINT', 'height=600,width=800');
        win.document.write('<html><head><title>Reservation Pass</title>');
        win.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">');
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

    if (totalImages <= 1) return; // No carousel needed if only one image

    function showSlide(index) {
        // Hide all slides
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            slide.style.opacity = i === index ? '1' : '0';
        });

        // Update indicators
        indicators.forEach((indicator, i) => {
            if (i === index) {
                indicator.classList.add('bg-white', 'w-8');
                indicator.classList.remove('bg-white/50');
            } else {
                indicator.classList.remove('bg-white', 'w-8');
                indicator.classList.add('bg-white/50');
            }
        });

        // Update counter
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

    // Event listeners
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);

    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => showSlide(index));
    });

    // Auto-play (optional - uncomment if you want auto-rotation)
    // setInterval(nextSlide, 5000);
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

        statusBadge.innerHTML = `<span class="w-2 h-2 rounded-full mr-2 bg-white"></span>${status.charAt(0).toUpperCase() + status.slice(1)}`;
        statusBadge.className = `inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-lg ${badgeClasses}`;
    }

    // Remove action buttons
    const actionButtons = document.getElementById('actionButtons');
    if (status !== 'pending' && actionButtons) {
        actionButtons.querySelector('#approveBtn')?.remove();
        actionButtons.querySelector('#rejectBtn')?.remove();
    }

    Swal.fire({
        icon: status === 'confirmed' ? 'success' : 'warning',
        title: status === 'confirmed' ? 'Reservation Confirmed!' : 'Reservation Cancelled!',
        text: message,
        confirmButtonColor: '#16a34a'
    });
};

document.getElementById('approveBtn')?.addEventListener('click', function () {
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

        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

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
            if (data.success) updateReservationUI('confirmed', data.message || 'The reservation has been successfully confirmed.');
            else Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong!', confirmButtonColor: '#d33' });
        })
        .catch(() => {
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to process the request.', confirmButtonColor: '#d33' });
        });
    });
});

document.getElementById('rejectBtn')?.addEventListener('click', function () {
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

        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

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
            if (data.success) updateReservationUI('cancelled', data.message || 'The reservation has been cancelled.');
            else Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong!', confirmButtonColor: '#d33' });
        })
        .catch(() => {
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to process the request.', confirmButtonColor: '#d33' });
        });
    });
});
</script>
@endsection
