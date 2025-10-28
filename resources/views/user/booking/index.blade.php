@extends('user.layouts.app')

@section('content')

    <style>
        /* General modal polish */
        .modal-content {
            border-radius: 1rem !important;
            overflow: hidden;
            border: none !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease-in-out;
        }

        /* Modal Header */
        .modal-header {
            background-color: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            padding: 1.25rem 1.5rem;
        }

        .modal-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Modal Body */
        .modal-body {
            background-color: #ffffff;
            padding: 2rem;
        }

        /* Modal Footer */
        .modal-footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Button tweaks */
        .btn-close {
            filter: invert(60%);
        }

        .modal-footer button {
            font-weight: 600;
            border-radius: 0.5rem;
        }

        /* Guest Modal specific */
        #guestModal .modal-dialog {
            max-width: 450px;
        }

        #guestModal .modal-body {
            background-color: #fefefe;
        }

        #guestModal label {
            font-size: 0.95rem;
        }

        /* Carousel fix for room modal */
        .carousel-inner img {
            border-bottom: 1px solid #e5e7eb;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(100%);
        }

        /* Animation */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translateY(-10%);
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }
    </style>


    <!-- Hero Section with Search Bar -->
    <section class="relative min-h-[500px] flex items-center text-white bg-cover bg-center"
        style="background: linear-gradient(rgba(248, 248, 248, 0.88), rgba(29, 91, 130, 0.7)), url('{{ asset('images/user/elegant-villa-bedroom.jpg') }}');">
        <div class="container mx-auto w-full px-4">
            <!-- Search Form -->
            <div class="bg-white p-8 rounded-xl shadow-2xl border border-[#1a4d6d]">
                <form method="GET" action="" id="searchForm">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">
                                <i class="fas fa-calendar"></i> Check-in Date
                            </label>
                            <input type="date"
                                class="w-full border border-gray-300 rounded-md px-3 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                                name="check_in" value="{{ request('check_in') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">
                                <i class="fas fa-calendar"></i> Check-out Date
                            </label>
                            <input type="date"
                                class="w-full border border-gray-300 text-black rounded-md px-3 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                name="check_out" value="{{ request('check_out') }}" required>
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">
                                <i class="fas fa-door-open"></i> Rooms & Guests
                            </label>
                            <button type="button"
                                class="w-full text-left border border-gray-300 rounded-md px-3 py-3 text-gray-800 hover:border-gray-400 transition"
                                data-bs-toggle="modal" data-bs-target="#guestModal">
                                <span id="guestDisplay">1 Room, 2 Guests</span>
                            </button>
                            <input type="hidden" name="rooms" id="roomsInput" value="{{ request('rooms', 1) }}">
                            <input type="hidden" name="guests" id="guestsInput" value="{{ request('guests', 2) }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mt-4">
                        <div class="md:col-span-8">
                            <input type="text"
                                class="w-full border border-gray-300 rounded-md px-3 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 text-black"
                                name="promo_code" value="{{ request('promo_code') }}"
                                placeholder="Add promo code (optional)">
                        </div>
                        <div class="md:col-span-4 flex gap-2">
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition duration-300">
                                Apply
                            </button>
                            <button type="reset"
                                class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-md transition duration-300">
                                Clear
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>


    <!-- Guest Selection Modal -->
    <div class="modal fade" id="guestModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-xl border-0">
                <div class="modal-header bg-gray-50 border-b border-gray-200">
                    <h5 class="modal-title font-serif text-gray-800">Select Rooms & Guests</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-8">
                    <div class="mb-6">
                        <label class="block font-semibold text-gray-800 mb-4">Room 1</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Adults (Ages 12+)</label>
                                <div class="flex items-center border border-gray-300 rounded-md">
                                    <button class="px-4 py-2 hover:bg-gray-100" type="button"
                                        onclick="decreaseAdults()">−</button>
                                    <input type="number" class="flex-1 text-center border-0 focus:outline-none"
                                        id="adultsInput" value="2" min="1" readonly>
                                    <button class="px-4 py-2 hover:bg-gray-100" type="button"
                                        onclick="increaseAdults()">+</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-2">Children (Ages 1-11)</label>
                                <div class="flex items-center border border-gray-300 rounded-md">
                                    <button class="px-4 py-2 hover:bg-gray-100" type="button"
                                        onclick="decreaseChildren()">−</button>
                                    <input type="number" class="flex-1 text-center border-0 focus:outline-none"
                                        id="childrenInput" value="0" min="0" readonly>
                                    <button class="px-4 py-2 hover:bg-gray-100" type="button"
                                        onclick="increaseChildren()">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button"
                        class="w-full border border-gray-300 text-gray-800 py-2 rounded-md hover:bg-gray-50 mb-4 transition"
                        onclick="addRoom()">
                        + Add additional room
                    </button>
                </div>
                <div class="modal-footer bg-gray-50 border-t border-gray-200">
                    <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition"
                        data-bs-dismiss="modal" onclick="updateGuestDisplay()">
                        Done
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content with Cart Sidebar -->
    <div class="flex gap-8 bg-gray-50 py-8">
        <!-- Availability Section -->
        <section class="flex-1 py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @forelse($rooms ?? [] as $room)
                        <!-- Room Card -->
                        <div
                            class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                            <!-- Room Image -->
                            <div class="relative h-[300px] overflow-hidden cursor-pointer" data-bs-toggle="modal"
                                data-bs-target="#roomModal{{ $room->id }}">
                                <img src="{{ asset($room->image ?? 'images/user/luxury-ocean-view-suite-hotel-room.jpg') }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                    alt="{{ $room->name }}">
                                @if ($room->availability <= 2)
                                    <div
                                        class="absolute top-4 right-4 bg-amber-100/90 text-blue-900 px-4 py-2 rounded-md font-semibold">
                                        ONLY {{ $room->availability }} LEFT
                                    </div>
                                @endif
                            </div>

                            <!-- Room Info -->
                            <div class="p-6">
                                <h5 class="text-2xl font-serif mb-2">{{ $room->name }}</h5>
                                <div class="text-gray-600 text-sm mb-4">
                                    <i class="fas fa-users"></i> Sleeps {{ $room->capacity }} &nbsp;
                                    <i class="fas fa-bed"></i> {{ $room->bed_type }} &nbsp;
                                    <i class="fas fa-bath"></i> {{ $room->bathrooms }}
                                    Bathroom{{ $room->bathrooms > 1 ? 's' : '' }}
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                                    {{ Str::limit($room->short_description, 120) }}
                                </p>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#roomModal{{ $room->id }}"
                                    class="text-amber-600 hover:text-amber-700 font-semibold">
                                    More info →
                                </a>
                            </div>

                            <!-- Room Pricing -->
                            <div class="px-6 pb-6 border-t border-gray-200 pt-6">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <span class="text-green-600 font-semibold">Free cancellation!</span>
                                </div>
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <span class="text-green-600 font-semibold">Book now, pay later</span>
                                </div>
                                <div class="bg-white border border-gray-200 p-4 rounded-md mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span
                                            class="font-semibold text-gray-800">{{ $room->rate_name ?? 'Published Rate' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">More info</span>
                                        <span class="text-xl font-bold text-gray-800">PHP
                                            {{ number_format($room->price, 2) }}</span>
                                    </div>
                                </div>
                                <button type="button"
                                    onclick="addToCart({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $room->price }})"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition duration-300">
                                    Select
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-20">
                            <p class="text-gray-500 text-lg">No rooms available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Booking Cart Sidebar -->
        <aside class="w-[400px] bg-white rounded-xl shadow-md p-8 h-[900px] sticky top-32 mr-4">
            <h5 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-bed"></i> Your Selection
            </h5>

            <!-- Cart Items -->
            <div class="max-h-[600px] overflow-y-auto mb-6" id="cartItems">
                @if (session('cart') && count(session('cart')) > 0)
                    @foreach (session('cart') as $item)
                        <div class="border border-gray-200 rounded-md p-4 mb-4 bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <h6 class="text-gray-800 font-medium">{{ $item['room_name'] }}</h6>
                                <button type="button" onclick="removeFromCart({{ $item['room_id'] }})"
                                    class="text-red-500 hover:text-red-700 transition">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 text-sm">Qty: {{ $item['quantity'] }}</span>
                                <span class="font-semibold text-amber-600">PHP
                                    {{ number_format($item['room_price'] * $item['quantity'], 2) }}</span>
                            </div>
                            <p class="text-gray-500 text-xs">PHP {{ number_format($item['room_price'], 2) }}/night</p>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl block mb-2"></i>
                        No rooms selected yet
                    </div>
                @endif
            </div>

            <!-- Cart Summary -->
            @if (session('cart') && count(session('cart')) > 0)
                @php
                    $cartTotal = collect(session('cart'))->sum(function ($item) {
                        return $item['room_price'] * $item['quantity'];
                    });
                @endphp
                <div class="border-t border-gray-200 pt-4 mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">PHP {{ number_format($cartTotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-4">
                        <span class="text-gray-600">Taxes & Fees (12%)</span>
                        <span class="font-semibold">PHP {{ number_format($cartTotal * 0.12, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold text-amber-600 pt-4 border-t border-gray-200">
                        <span>Total</span>
                        <span>PHP {{ number_format($cartTotal * 1.12, 2) }}</span>
                    </div>
                </div>

                <button type="button" onclick="proceedToCheckout()"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md mb-2 transition duration-300">
                    Book
                </button>
                <button type="button" onclick="reserveRoom()"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition duration-300">
                    Reserve
                </button>
            @endif
        </aside>
    </div>

    <!-- Room Detail Modals -->
    @foreach ($rooms ?? [] as $room)
        <div class="modal fade" id="roomModal{{ $room->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-xl border-0">
                    <div class="modal-header border-b border-gray-200">
                        <h5 class="modal-title font-serif text-gray-800">{{ $room->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <!-- Gallery Carousel -->
                        <div id="roomCarousel{{ $room->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @php
                                    $images = is_array($room->images) ? $room->images : explode(',', $room->images);
                                @endphp

                                @if (!empty($images))
                                    @foreach ($images as $index => $image)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ asset($image) }}" class="w-full h-[400px] object-cover"
                                                alt="Room Image">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="carousel-item active">
                                        <img src="{{ asset('images/user/luxury-ocean-view-suite-hotel-room.jpg') }}"
                                            class="w-full h-[400px] object-cover" alt="Room Image">
                                    </div>
                                @endif
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#roomCarousel{{ $room->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#roomCarousel{{ $room->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>

                        <!-- Room Details -->
                        <div class="p-8">
                            <h6 class="text-lg font-semibold text-gray-800 mb-4">Room Features</h6>
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Capacity</p>
                                    <p class="font-semibold">Sleeps {{ $room->capacity }} guests</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Size</p>
                                    <p class="font-semibold">{{ $room->size ?? 'N/A' }} m²</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Beds</p>
                                    <p class="font-semibold">{{ $room->bed_type }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm mb-1">Bathrooms</p>
                                    <p class="font-semibold">{{ $room->bathrooms }} En-suite</p>
                                </div>
                            </div>

                            <h6 class="text-lg font-semibold text-gray-800 mb-4">Amenities</h6>
                            <ul class="list-none mb-6">
                                @php
                                    $amenities = is_array($room->amenities)
                                        ? $room->amenities
                                        : json_decode($room->amenities, true);
                                @endphp

                                @if (is_array($amenities))
                                    @foreach ($amenities as $amenity)
                                        <li class="py-2 text-gray-600">
                                            <i class="fas fa-check text-green-600 mr-2"></i>{{ $amenity }}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                            <p class="text-gray-600 leading-relaxed">
                                {{ $room->full_description ?? $room->short_description }}
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer border-t border-gray-200">
                        <button type="button"
                            class="bg-gray-200 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-300 transition"
                            data-bs-dismiss="modal">Close</button>
                        <button type="button"
                            onclick="addToCart({{ $room->id }}, '{{ addslashes($room->name) }}', {{ $room->price }}); bootstrap.Modal.getInstance(document.getElementById('roomModal{{ $room->id }}')).hide();"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md transition">Select
                            Room</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach



    <!-- Amenities Section -->
    <section class="py-12" id="amenities">
        <div class="container mx-auto px-4">
            <h2 class="text-center text-4xl font-serif mb-12">Resort Amenities</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach ([['icon' => 'fa-wifi', 'title' => 'Free WiFi', 'desc' => 'High-speed internet throughout the resort'], ['icon' => 'fa-swimming-pool', 'title' => 'Swimming Pool', 'desc' => 'Olympic-sized pool with heated water'], ['icon' => 'fa-utensils', 'title' => 'Fine Dining', 'desc' => 'Award-winning restaurant and bar'], ['icon' => 'fa-spa', 'title' => 'Spa & Wellness', 'desc' => 'Full-service spa with massage therapy']] as $amenity)
                    <div class="text-center">
                        <div class="text-5xl text-amber-600 mb-4">
                            <i class="fas {{ $amenity['icon'] }}"></i>
                        </div>
                        <h5 class="font-semibold mb-2">{{ $amenity['title'] }}</h5>
                        <p class="text-gray-600 text-sm">{{ $amenity['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function addToCart(roomId, roomName, roomPrice) {
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
                        if (data.success) {
                            location.reload(); // Reload to show updated cart
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }


            function removeFromCart(roomId) {
                fetch('{{ route('cart.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            room_id: roomId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            function clearCart() {
                Swal.fire({
                    title: 'Clear your cart?',
                    text: "Are you sure you want to remove all items?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, clear it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('cart.clear') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(() => {
                                Swal.fire({
                                    title: 'Cleared!',
                                    text: 'Your cart has been emptied.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    location.reload();
                                });
                            });
                    }
                });
            }

            function proceedToCheckout() {
                window.location.href = '{{ route('user.booking.book') }}';
            }

            function reserveRoom() {
                window.location.href = '{{ route('user.booking.reserve') }}';
            }

            function increaseAdults() {
                const input = document.getElementById('adultsInput');
                input.value = parseInt(input.value) + 1;
            }

            function decreaseAdults() {
                const input = document.getElementById('adultsInput');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            }

            function increaseChildren() {
                const input = document.getElementById('childrenInput');
                input.value = parseInt(input.value) + 1;
            }

            function decreaseChildren() {
                const input = document.getElementById('childrenInput');
                if (parseInt(input.value) > 0) {
                    input.value = parseInt(input.value) - 1;
                }
            }

            function addRoom() {
                alert('Additional room functionality would be added here');
            }

            function updateGuestDisplay() {
                const adults = document.getElementById('adultsInput').value;
                const children = document.getElementById('childrenInput').value;
                const totalGuests = parseInt(adults) + parseInt(children);
                document.getElementById('guestDisplay').textContent = '1 Room, ' + totalGuests + ' Guests';
                document.getElementById('guestsInput').value = totalGuests;
            }

            // Set minimum dates
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date().toISOString().split('T')[0];
                document.querySelector('input[name="check_in"]').setAttribute('min', today);
                document.querySelector('input[name="check_out"]').setAttribute('min', today);
            });
        </script>
    @endpush

@endsection
