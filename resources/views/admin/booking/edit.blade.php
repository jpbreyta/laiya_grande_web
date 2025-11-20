@extends('admin.layouts.app')

@php
    $pageTitle = 'Edit Booking';
@endphp

@section('content')
    <section class="bg-gradient-to-br from-slate-50 via-white to-slate-100 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                            Edit Booking
                        </h1>
                    </div>
                </div>

                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 z-10">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-14 w-14 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center text-white font-bold text-lg shadow-lg ring-2 ring-white/20">
                                {{ strtoupper(substr($booking->firstname, 0, 1) . substr($booking->lastname, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xl md:text-2xl font-bold text-white drop-shadow-lg">
                                    {{ $booking->firstname }} {{ $booking->lastname }}
                                </p>
                                <p class="text-sm text-gray-200">{{ $booking->room->name ?? 'Room N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
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

            <!-- Main Content Card -->
            <div class="bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                <div class="p-6 md:p-8">
                    <!-- Guest & Booking Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Guest Information</h3>
                            
                            <div
                                class="p-5 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-100 shadow-sm">
                                <label class="text-xs font-bold text-teal-600 uppercase tracking-wider mb-1 block">Guest
                                    Name</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" name="firstname"
                                        value="{{ old('firstname', $booking->firstname) }}"
                                        class="w-full px-3 py-2 border border-teal-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
                                        placeholder="First Name">
                                    @error('firstname')
                                        <p class="text-red-500 text-xs mt-1 col-span-2">{{ $message }}</p>
                                    @enderror
                                    <input type="text" name="lastname"
                                        value="{{ old('lastname', $booking->lastname) }}"
                                        class="w-full px-3 py-2 border border-teal-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm"
                                        placeholder="Last Name">
                                    @error('lastname')
                                        <p class="text-red-500 text-xs mt-1 col-span-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 block">Email</label>
                                <input type="email" name="email" value="{{ old('email', $booking->email) }}"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <label class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 block">Phone
                                    Number</label>
                                <input type="text" name="phone_number"
                                    value="{{ old('phone_number', $booking->phone_number) }}"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 block">Number
                                    of Guests</label>
                                <input type="number" name="number_of_guests"
                                    value="{{ old('number_of_guests', $booking->number_of_guests) }}" min="1"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                @error('number_of_guests')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Booking Details</h3>
                            
                            <div class="p-5 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100 shadow-sm">
                                <label
                                    class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1 block">Room</label>
                                <select name="room_id"
                                    class="w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                    <option value="">Select Room</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}"
                                            {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 block">Check-in
                                    Date</label>
                                <input type="date" name="check_in"
                                    value="{{ old('check_in', $booking->check_in) }}"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                @error('check_in')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                                <label
                                    class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-1 block">Check-out
                                    Date</label>
                                <input type="date" name="check_out"
                                    value="{{ old('check_out', $booking->check_out) }}"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                @error('check_out')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-5 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200 shadow-sm">
                                <label
                                    class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1 block">Total
                                    Price</label>
                                <input type="number" name="total_price"
                                    value="{{ old('total_price', $booking->total_price) }}" step="0.01"
                                    min="0"
                                    class="w-full px-3 py-2 border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm font-semibold">
                                @error('total_price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Payment
                                Method</label>
                            <div class="text-sm font-medium text-slate-900">
                                {{ ucfirst($booking->payment_method ?? 'N/A') }}</div>
                        </div>

                        <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                            <label
                                class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                                <option value="pending"
                                    {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="confirmed"
                                    {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed
                                </option>
                                <option value="cancelled"
                                    {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Special Request -->
                    <div class="mb-6">
                        <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                            <label class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-2 block">Special
                                Request</label>
                            <textarea name="special_request" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">{{ old('special_request', $booking->special_request) }}</textarea>
                            @error('special_request')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Proof Display -->
                    @if ($booking->payment && file_exists(storage_path('app/public/' . $booking->payment)))
                        <div class="mb-6 p-5 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Payment Proof
                            </h3>
                            <div class="relative inline-block">
                                <img src="{{ asset('storage/' . $booking->payment) }}" alt="Payment Proof"
                                    class="rounded-lg shadow-md border-2 border-slate-200 max-w-md">
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-slate-200">
                        <a href="{{ route('admin.booking.show', $booking->id) }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white px-6 py-2.5 text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-2.5 text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-save"></i>
                            Update Booking
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>

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
    </script>
@endsection
