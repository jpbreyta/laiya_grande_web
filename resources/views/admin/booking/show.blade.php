@extends('admin.layouts.app')

@section('content')
    <section class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-6xl mx-auto">
            @php
                $room = $booking->room;
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

            <div class="relative h-80 md:h-96 rounded-t-2xl overflow-hidden shadow-xl mb-6">
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
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button id="nextBtn"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            <div class="absolute bottom-20 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                                @foreach ($allImages as $index => $image)
                                    <button
                                        class="carousel-indicator w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50' }}"
                                        data-index="{{ $index }}"></button>
                                @endforeach
                            </div>

                            <div
                                class="absolute top-20 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm font-medium z-10">
                                <span id="currentImage">1</span> / <span id="totalImages">{{ count($allImages) }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600"></div>
                @endif

                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent pointer-events-none">
                </div>

                <div class="absolute top-0 left-0 p-6 md:p-8 z-10">
                    <div
                        class="inline-block bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-xl border border-white/20 shadow-lg">
                        <h1
                            class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-white tracking-tight drop-shadow-lg">
                            Booking Details
                        </h1>
                    </div>
                </div>

                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 z-10">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ strtoupper(substr($booking->firstname, 0, 1) . substr($booking->lastname, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xl md:text-2xl font-bold text-white">{{ $booking->firstname }}
                                    {{ $booking->lastname }}</p>
                                <p class="text-sm text-gray-200">{{ $booking->room->name ?? 'Room N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span id="statusBadge"
                                class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-lg
                            @if ($booking->status === 'pending') bg-gradient-to-r from-amber-400 to-yellow-400 text-white border border-amber-300
                            @elseif($booking->status === 'confirmed') bg-gradient-to-r from-green-400 to-emerald-400 text-white border border-green-300
                            @else bg-gradient-to-r from-red-400 to-rose-400 text-white border border-red-300 @endif">
                                <span class="w-2 h-2 rounded-full mr-2 bg-white"></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <!-- Booking Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Guest Name</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $booking->firstname }}
                                    {{ $booking->lastname }}</div>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Email</div>
                                <div class="text-sm font-medium text-gray-900">{{ $booking->email }}</div>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Phone Number
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $booking->phone_number }}</div>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Number of Guests
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $booking->number_of_guests }}</div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
                                <div class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1">Room</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $booking->room->name ?? 'N/A' }}</div>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Check-in Date
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</div>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Check-out Date
                                </div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</div>
                            </div>

                            <div
                                class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                                
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Status</div>
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-sm
                            @if ($booking->status === 'pending') bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200
                            @elseif($booking->status === 'confirmed') bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200
                            @else bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200 @endif">
                                <span
                                    class="w-2 h-2 rounded-full mr-2 {{ $booking->status === 'confirmed' ? 'bg-green-500' : ($booking->status === 'pending' ? 'bg-amber-500' : 'bg-red-500') }}"></span>
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
 
                    @if ($booking->payment && file_exists(storage_path('app/public/' . $booking->payment)))
                        <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200"
                            id="paymentProofSection">
                            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3">Payment Proof</h3>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $booking->payment) }}" alt="Payment Proof"
                                    class="rounded-lg shadow-md border-2 border-gray-200 max-w-md">
                            </div>
                        </div>
                    @elseif($booking->payment)
                        <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 rounded-lg border border-red-200 text-red-700"
                            id="paymentProofSection">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                                Payment proof uploaded but file not found!
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200" id="actionButtons">
                        @if ($booking->status === 'pending')
                            <form id="approveForm" action="{{ route('admin.booking.approve', $booking->id) }}"
                                method="POST" class="inline">
                                @csrf
                                <button type="button" id="approveBtn"
                                    class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                    Confirm Booking
                                </button>
                            </form>

                            <form id="rejectForm" action="{{ route('admin.booking.reject', $booking->id) }}"
                                method="POST" class="inline">
                                @csrf
                                <button type="button" id="rejectBtn"
                                    class="bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                    Cancel Booking
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.booking.index') }}"
                            class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

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

        const updateBookingUI = (status, message) => {
            const statusBadge = document.getElementById('statusBadge');
            if (statusBadge) {
                let badgeClasses = '';

                if (status === 'confirmed') {
                    badgeClasses = 'bg-gradient-to-r from-green-400 to-emerald-400 text-white border border-green-300';
                } else if (status === 'pending') {
                    badgeClasses = 'bg-gradient-to-r from-amber-400 to-yellow-400 text-white border border-amber-300';
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
                icon: status === 'confirmed' ? 'success' : 'warning',
                title: status === 'confirmed' ? 'Booking Confirmed!' : 'Booking Cancelled!',
                text: message,
                confirmButtonColor: '#16a34a'
            });
        };

        document.getElementById('approveBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Confirm this booking?',
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
                        if (data.success) updateBookingUI('confirmed', data.message ||
                            'The booking has been successfully confirmed.');
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
                title: 'Cancel this booking?',
                text: 'This will mark the booking as cancelled.',
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
                        if (data.success) updateBookingUI('cancelled', data.message ||
                            'The booking has been cancelled.');
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
    </script>
@endsection
