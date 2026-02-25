@extends('user.layouts.app')

@section('content')

    <!-- Date Selection Modal -->
    <div id="dateModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="display: none;">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeDateModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all">
            <div class="bg-gradient-to-r from-teal-700 to-teal-600 px-6 py-5 rounded-t-2xl">
                <h3 class="text-2xl font-heading font-bold text-white flex items-center gap-3">
                    <i class="fas fa-calendar-check"></i>
                    Select Your Dates
                </h3>
                <p class="text-teal-100 text-sm mt-1">Choose your check-in and check-out dates to view available rooms</p>
            </div>
            <form id="dateSelectionForm" class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-sign-in-alt text-teal-600 mr-2"></i>Check-in Date *
                    </label>
                    <input type="date" id="modal_check_in" required
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        <i class="fas fa-sign-out-alt text-teal-600 mr-2"></i>Check-out Date *
                    </label>
                    <input type="date" id="modal_check_out" required
                        class="w-full px-4 py-3 border-2 border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all">
                </div>
                <div id="nightsDisplay" class="hidden bg-teal-50 border-l-4 border-teal-500 p-4 rounded-r-xl">
                    <p class="text-teal-800 font-semibold flex items-center gap-2">
                        <i class="fas fa-moon"></i>
                        <span id="nightsCount">0</span> night(s) selected
                    </p>
                </div>
                <div id="dateError" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                    <p class="text-sm text-red-700 font-medium"></p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeDateModal()"
                        class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-teal-700 to-teal-600 hover:from-teal-800 hover:to-teal-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-teal-700/30">
                        <i class="fas fa-search mr-2"></i>Check Availability
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap');

        .font-heading {
            font-family: 'Playfair Display', serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar for Cart Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* SweetAlert Customization to match Teal Theme */
        div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm {
            background-color: #0f766e !important;
            /* Teal-700 */
            box-shadow: none !important;
        }
    </style>

    <div class="bg-slate-50 min-h-screen font-body text-slate-600">

        <div class="relative bg-teal-900 py-16 sm:py-20 isolate overflow-hidden">
            <div class="absolute inset-0 -z-10">
                <img src="{{ asset('images/real.jpg') }}" alt="" class="h-full w-full object-cover opacity-30">
                <div class="absolute inset-0 bg-gradient-to-t from-teal-900 via-teal-900/60 to-transparent"></div>
            </div>

            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center relative z-10">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-xs font-medium tracking-widest text-white uppercase mb-4">
                    Start Your Journey
                </span>
                <h1 class="text-4xl sm:text-5xl font-heading font-bold text-white tracking-tight mb-4">
                    Select Your Accommodation
                </h1>
                <p class="text-teal-100 text-lg max-w-2xl mx-auto font-light leading-relaxed">
                    Choose from our collection of luxury rooms and villas.
                </p>

                @if (isset($checkIn) && isset($checkOut))
                    <div
                        class="mt-6 inline-flex items-center gap-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-6 py-3">
                        <div class="flex items-center gap-3 text-sm text-white">
                            <div class="text-left">
                                <p class="text-xs text-teal-200 font-semibold uppercase">Check-in</p>
                                <p class="font-bold">{{ \Carbon\Carbon::parse($checkIn)->format('M d, Y') }}</p>
                            </div>
                            <i class="fas fa-arrow-right text-teal-300"></i>
                            <div class="text-left">
                                <p class="text-xs text-teal-200 font-semibold uppercase">Check-out</p>
                                <p class="font-bold">{{ \Carbon\Carbon::parse($checkOut)->format('M d, Y') }}</p>
                            </div>
                            <div class="border-l border-white/30 pl-3 ml-2">
                                <p class="text-xs text-teal-200 font-semibold uppercase">Nights</p>
                                <p class="font-bold">{{ $nights }}</p>
                            </div>
                        </div>
                        <button onclick="openDateModal()"
                            class="text-xs text-white hover:text-teal-200 font-semibold flex items-center gap-1 bg-white/10 hover:bg-white/20 px-3 py-2 rounded-lg transition-all">
                            <i class="fas fa-edit"></i>
                            Change
                        </button>
                    </div>
                @else
                    <div class="mt-6">
                        <button onclick="openDateModal()"
                            class="inline-flex items-center gap-2 bg-white hover:bg-teal-50 text-teal-900 font-bold px-6 py-3 rounded-xl transition-all shadow-lg">
                            <i class="fas fa-calendar-alt"></i>
                            Select Your Dates
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row gap-8 xl:gap-12">

                <div class="flex-1">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 font-heading">
                            <span class="text-teal-600">{{ count($rooms ?? []) }}</span> Rooms Available
                        </h2>
                        <div class="text-xs text-slate-500 hidden sm:block uppercase tracking-wider font-semibold">
                            Best Rate Guaranteed
                        </div>
                    </div>

                    <div class="space-y-8">
                        @forelse($rooms ?? [] as $room)
                            <div
                                class="group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col md:flex-row">

                                <div class="md:w-2/5 relative overflow-hidden cursor-pointer h-64 md:h-auto bg-gray-200"
                                    data-bs-target="#roomModal{{ $room->id }}">
                                    <a href="{{ route('user.rooms.show', $room->id) }}" class="block h-full">
                                        <div
                                            class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors z-10">
                                        </div>

                                        <img src="{{ asset($room->image ?? 'images/user/luxury-ocean-view-suite-hotel-room.jpg') }}"
                                            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
                                            alt="{{ $room->name }}" loading="lazy">

                                        <div
                                            class="absolute bottom-3 left-3 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <span
                                                class="bg-white/90 backdrop-blur-md text-slate-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1">
                                                <i class="fas fa-eye"></i> View Details
                                            </span>
                                        </div>
                                    </a>
                                </div>

                                <div class="md:w-3/5 p-6 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-2">
                                            <h3
                                                class="text-2xl font-heading font-bold text-slate-800 group-hover:text-teal-700 transition-colors">
                                                {{ $room->name }}
                                            </h3>
                                            @if ($room->rate_name)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                    {{ $room->rate_name }}
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-slate-500 text-sm leading-relaxed mb-4 line-clamp-2">
                                            {{ $room->short_description }}
                                        </p>

                                        <div class="flex flex-wrap gap-2 mb-6">
                                            <span
                                                class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                                <i class="fas fa-user-friends text-teal-600"></i> {{ $room->capacity }}
                                                Guests
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                                <i class="fas fa-bed text-teal-600"></i> {{ $room->bed_type }}
                                            </span>
                                            <span
                                                class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-500/10">
                                                <i class="fas fa-bath text-teal-600"></i> {{ $room->bathrooms }} Bath
                                            </span>
                                        </div>

                                        <div class="flex gap-4 text-xs text-green-600 font-medium mb-4">
                                            <span class="flex items-center gap-1"><i class="fas fa-check-circle"></i> Free
                                                Cancellation</span>
                                            <span class="flex items-center gap-1"><i class="fas fa-check-circle"></i> Pay
                                                Later</span>
                                        </div>
                                    </div>

                                    <!-- Rating Display -->
                                    <div class="flex items-center gap-2 mb-4">
                                        @if ($room->total_ratings > 0)
                                            <div class="flex items-center gap-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($room->average_rating))
                                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                    @elseif($i - 0.5 <= $room->average_rating)
                                                        <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                                    @else
                                                        <i class="far fa-star text-gray-300 text-sm"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span
                                                class="text-sm font-semibold text-slate-700">{{ $room->average_rating }}</span>
                                            <span class="text-xs text-slate-500">({{ $room->total_ratings }}
                                                {{ $room->total_ratings == 1 ? 'review' : 'reviews' }})</span>
                                        @else
                                            <span class="text-xs text-slate-400">No ratings yet</span>
                                        @endif
                                        <button
                                            onclick="openRatingModal({{ $room->id }}, '{{ addslashes($room->name) }}')"
                                            class="ml-auto text-xs text-teal-600 hover:text-teal-700 font-medium">
                                            <i class="fas fa-star"></i> Rate
                                        </button>
                                    </div>

                                    <div class="flex items-end justify-between border-t border-slate-100 pt-4 mt-auto">
                                        <div>
                                            <p class="text-xs text-slate-400 mb-0.5 font-medium uppercase tracking-wide">
                                                Price per night</p>
                                            <div class="flex items-baseline gap-1">
                                                <span
                                                    class="text-2xl font-bold text-slate-900 font-heading">₱{{ number_format($room->price, 0) }}</span>
                                            </div>
                                        </div>

                                        <button
                                            onclick="addToCart({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $room->price }})"
                                            class="rounded-xl bg-teal-700 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-teal-600 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                                            Select Room <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-24 bg-white rounded-2xl border border-dashed border-slate-300">
                                <div
                                    class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-search text-slate-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-slate-900 font-heading">No rooms found</h3>
                                <p class="text-slate-500 mt-2">We couldn't find any availability matching your criteria.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="lg:w-[380px] xl:w-[400px] flex-shrink-0">
                    <div
                        class="sticky top-8 bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">

                        <div class="bg-teal-900 px-6 py-5 flex justify-between items-center">
                            <h5 class="text-white font-heading font-semibold text-lg flex items-center gap-2">
                                <i class="fas fa-clipboard-list text-yellow-400"></i> Your Selection
                            </h5>
                            @if (session('cart') && count(session('cart')) > 0)
                                <button onclick="clearCart()"
                                    class="text-teal-200 hover:text-white text-xs font-medium hover:underline transition-colors">
                                    Clear All
                                </button>
                            @endif
                        </div>

                        <div class="p-6">
                            <div class="max-h-[400px] overflow-y-auto custom-scrollbar pr-2 -mr-2" id="cartItems">
                                @if (session('cart') && count(session('cart')) > 0)
                                    <div class="space-y-4">
                                        @foreach (session('cart') as $item)
                                            <div
                                                class="group relative bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:border-teal-500 transition-colors">
                                                <div class="flex justify-between items-start mb-3">
                                                    <h6 class="font-heading font-bold text-slate-800 pr-6 leading-tight">
                                                        {{ $item['room_name'] }}</h6>
                                                    <button
                                                        onclick="removeFromCart({{ $item['room_id'] }}, '{{ addslashes($item['room_name']) }}')"
                                                        class="absolute top-2 right-2 text-slate-300 hover:text-red-500 transition-colors p-1.5 rounded-full hover:bg-red-50">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>

                                                <div class="flex justify-between items-end">
                                                    <div class="text-xs text-slate-500">
                                                        <span class="block mb-1">Quantity: <strong
                                                                class="text-slate-800">{{ $item['quantity'] }}</strong></span>
                                                        <span class="block">Rate:
                                                            ₱{{ number_format($item['room_price'], 0) }}</span>
                                                    </div>

                                                    <div class="text-right">
                                                        <p class="font-bold text-teal-700 text-lg">
                                                            ₱{{ number_format($item['room_price'] * $item['quantity'], 0) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-10 px-4">
                                        <div
                                            class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                            <i class="fas fa-suitcase-rolling text-xl"></i>
                                        </div>
                                        <p class="text-slate-800 font-bold font-heading">Your stay is empty</p>
                                        <p class="text-slate-500 text-sm mt-1">Select a room from the list to start
                                            planning
                                            your trip.</p>
                                    </div>
                                @endif
                            </div>

                            @if (session('cart') && count(session('cart')) > 0)
                                @php
                                    $cartTotal = collect(session('cart'))->sum(function ($item) {
                                        return $item['room_price'] * $item['quantity'];
                                    });
                                @endphp

                                <div class="mt-6 pt-6 border-t border-dashed border-slate-300 space-y-3">
                                    <div class="flex justify-between text-sm text-slate-600">
                                        <span>Subtotal</span>
                                        <span class="font-medium">₱{{ number_format($cartTotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center pt-4 mt-2 border-t border-slate-200">
                                        <span class="font-heading font-bold text-lg text-slate-900">Total Due</span>
                                        <span
                                            class="font-bold text-2xl text-teal-700">₱{{ number_format($cartTotal, 2) }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 grid gap-3">
                                    <button onclick="proceedToCheckout()"
                                        class="w-full rounded-xl bg-teal-600 px-4 py-4 text-sm font-bold text-white shadow-lg shadow-teal-700/20 hover:bg-teal-700 hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2">
                                        Proceed to Booking <i class="fas fa-arrow-right"></i>
                                    </button>

                                    <button onclick="reserveRoom()"
                                        class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3.5 text-sm font-bold text-slate-600 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-800 transition-all duration-200">
                                        Reserve Only
                                    </button>
                                </div>

                                <p
                                    class="text-center text-[10px] text-slate-400 mt-4 flex items-center justify-center gap-1">
                                    <i class="fas fa-lock"></i> Secure checkout via Stripe
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal"
        class="fixed inset-0 z-[999] hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-slate-800 font-heading">Rate This Room</h3>
                <button onclick="closeRatingModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="ratingForm" onsubmit="submitRating(event)">
                <input type="hidden" id="rating_room_id" name="room_id">

                <div class="mb-4">
                    <p class="text-sm text-slate-600 mb-2" id="rating_room_name"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Your Rating *</label>
                    <div class="flex gap-2" id="starRating">
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="1"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="2"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="3"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="4"></i>
                        <i class="fas fa-star text-3xl text-gray-300 cursor-pointer hover:text-yellow-400 transition-colors"
                            data-rating="5"></i>
                    </div>
                    <input type="hidden" id="rating_value" name="rating" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Your Email *</label>
                    <input type="email" id="guest_email" name="guest_email" required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="your@email.com">
                    <p class="text-xs text-slate-500 mt-1">We use this to track ratings and prevent duplicates</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Your Name (Optional)</label>
                    <input type="text" id="guest_name" name="guest_name"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="John Doe">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Comment (Optional)</label>
                    <textarea id="rating_comment" name="comment" rows="3"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="Share your experience..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeRatingModal()"
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-teal-600 text-white rounded-lg font-bold hover:bg-teal-700 transition-colors">
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function addToCart(roomId, roomName, roomPrice) {
                Swal.fire({
                    title: 'Adding to cart...',
                    html: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route('cart.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            room_id: roomId,
                            room_name: roomName,
                            room_price: roomPrice,
                            quantity: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                title: "Room Added",
                                html: `<div style="font-family:'Inter',sans-serif; font-size:15px; color:#475569; margin-top:8px;"><strong>${roomName}</strong> has been added to your selection.</div>`,
                                icon: "success",
                                confirmButtonColor: "#0f766e",
                                confirmButtonText: "Continue Browsing",
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Something went wrong.",
                                icon: "error",
                                confirmButtonColor: "#ef4444"
                            });
                        }
                    });
            }

            function removeFromCart(roomId, roomName) {
                Swal.fire({
                    title: 'Removing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route('cart.remove', ['id' => ':id']) }}'.replace(':id', roomId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                title: "Removed",
                                html: `<div style="font-family:'Inter',sans-serif; font-size:15px; color:#475569; margin-top:8px;"><strong>${roomName}</strong> removed from selection.</div>`,
                                icon: "success",
                                confirmButtonColor: "#0f766e",
                                confirmButtonText: "Update View",
                            }).then(() => location.reload());
                        }
                    });
            }

            function clearCart() {
                Swal.fire({
                    title: 'Clear selection?',
                    text: "This will remove all rooms from your cart.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0f766e',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, clear it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('cart.clear') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }).then(() => {
                            Swal.fire({
                                    title: 'Cleared!',
                                    text: 'Cart is empty.',
                                    icon: 'success',
                                    confirmButtonColor: '#0f766e'
                                })
                                .then(() => location.reload());
                        });
                    }
                });
            }

            function proceedToCheckout() {
                window.location.href = '{{ route('user.booking.book') }}';
            }

            function reserveRoom() {
                window.location.href = '{{ route('user.reserve.index') }}';
            }

            // Rating Modal Functions
            let selectedRating = 0;

            function openRatingModal(roomId, roomName) {
                document.getElementById('rating_room_id').value = roomId;
                document.getElementById('rating_room_name').textContent = roomName;
                document.getElementById('ratingModal').classList.remove('hidden');
                selectedRating = 0;
                updateStars(0);
            }

            function closeRatingModal() {
                document.getElementById('ratingModal').classList.add('hidden');
                document.getElementById('ratingForm').reset();
                selectedRating = 0;
                updateStars(0);
            }

            // Star rating interaction
            document.addEventListener('DOMContentLoaded', function() {
                const stars = document.querySelectorAll('#starRating i');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        selectedRating = parseInt(this.dataset.rating);
                        document.getElementById('rating_value').value = selectedRating;
                        updateStars(selectedRating);
                    });

                    star.addEventListener('mouseenter', function() {
                        const hoverRating = parseInt(this.dataset.rating);
                        updateStars(hoverRating);
                    });
                });

                document.getElementById('starRating').addEventListener('mouseleave', function() {
                    updateStars(selectedRating);
                });

                const today = new Date().toISOString().split('T')[0];
                const checkIn = document.querySelector('input[name="check_in"]');
                const checkOut = document.querySelector('input[name="check_out"]');
                if (checkIn) checkIn.setAttribute('min', today);
                if (checkOut) checkOut.setAttribute('min', today);
            });

            function updateStars(rating) {
                const stars = document.querySelectorAll('#starRating i');
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.remove('text-gray-300');
                        star.classList.add('text-yellow-400');
                    } else {
                        star.classList.remove('text-yellow-400');
                        star.classList.add('text-gray-300');
                    }
                });
            }

            function submitRating(event) {
                event.preventDefault();

                if (selectedRating === 0) {
                    Swal.fire({
                        title: 'Rating Required',
                        text: 'Please select a star rating',
                        icon: 'warning',
                        confirmButtonColor: '#0f766e'
                    });
                    return;
                }

                const formData = {
                    room_id: document.getElementById('rating_room_id').value,
                    guest_email: document.getElementById('guest_email').value,
                    guest_name: document.getElementById('guest_name').value,
                    rating: selectedRating,
                    comment: document.getElementById('rating_comment').value
                };

                Swal.fire({
                    title: 'Submitting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route('ratings.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                title: 'Thank You!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#0f766e'
                            }).then(() => {
                                closeRatingModal();
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message,
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        Swal.fire({
                            title: 'Error',
                            text: 'Something went wrong. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    });
            }
        </script>

        <script>
            // Date Modal Functions
            function openDateModal() {
                document.getElementById('dateModal').style.display = 'flex';
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('modal_check_in').setAttribute('min', today);
                document.getElementById('modal_check_out').setAttribute('min', today);

                // Pre-fill with existing dates if available
                @if (isset($checkIn) && isset($checkOut))
                    document.getElementById('modal_check_in').value = '{{ $checkIn }}';
                    document.getElementById('modal_check_out').value = '{{ $checkOut }}';
                    calculateNights();
                @endif
            }

            function closeDateModal() {
                document.getElementById('dateModal').style.display = 'none';
                document.getElementById('nightsDisplay').classList.add('hidden');
                document.getElementById('dateError').classList.add('hidden');
            }

            // Calculate nights
            document.getElementById('modal_check_in').addEventListener('change', function() {
                document.getElementById('modal_check_out').setAttribute('min', this.value);
                calculateNights();
            });
            document.getElementById('modal_check_out').addEventListener('change', calculateNights);

            function calculateNights() {
                const checkIn = document.getElementById('modal_check_in').value;
                const checkOut = document.getElementById('modal_check_out').value;

                if (checkIn && checkOut) {
                    const start = new Date(checkIn);
                    const end = new Date(checkOut);
                    const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

                    if (nights > 0) {
                        document.getElementById('nightsCount').textContent = nights;
                        document.getElementById('nightsDisplay').classList.remove('hidden');
                        document.getElementById('dateError').classList.add('hidden');
                    } else {
                        document.getElementById('nightsDisplay').classList.add('hidden');
                        document.getElementById('dateError').classList.remove('hidden');
                        document.getElementById('dateError').querySelector('p').textContent =
                            'Check-out date must be after check-in date.';
                    }
                }
            }

            // Handle date form submission
            document.getElementById('dateSelectionForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const checkIn = document.getElementById('modal_check_in').value;
                const checkOut = document.getElementById('modal_check_out').value;

                if (!checkIn || !checkOut) {
                    document.getElementById('dateError').classList.remove('hidden');
                    document.getElementById('dateError').querySelector('p').textContent = 'Please select both dates.';
                    return;
                }

                const start = new Date(checkIn);
                const end = new Date(checkOut);
                const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

                if (nights <= 0) {
                    document.getElementById('dateError').classList.remove('hidden');
                    document.getElementById('dateError').querySelector('p').textContent =
                        'Check-out date must be after check-in date.';
                    return;
                }

                // Redirect to rooms page with dates
                window.location.href = `{{ route('user.rooms.index') }}?check_in=${checkIn}&check_out=${checkOut}`;
            });

            // Show modal on page load if no dates are set
            window.addEventListener('DOMContentLoaded', function() {
                @if (!isset($checkIn) || !isset($checkOut))
                    openDateModal();
                @endif
            });
        </script>
    @endpush

@endsection
