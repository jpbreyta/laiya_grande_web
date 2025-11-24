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


    <!-- Reservation Hero -->
    <section class="relative isolate bg-center min-h-[40svh] overflow-hidden flex items-center justify-center text-center">
        <div class="absolute inset-0 -z-10">
            <img src="{{ asset('images/real.jpg') }}" alt="" aria-hidden="true" class="h-full w-full object-cover">
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-teal-900/50">
        </div>
        <div class="relative mx-auto max-w-7xl px-6 py-16 flex min-h-[40svh] items-center justify-center">
            <div class="text-center text-white max-w-4xl">
                <h1
                    class="text-4xl md:text-5xl font-extrabold tracking-tight text-balance font-heading mb-4 animate-slide-up">
                    <span
                        class="block bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 bg-clip-text text-transparent">
                        Make Your Reservation
                    </span>
                </h1>
                <p class="text-lg md:text-xl leading-relaxed text-white/90 mb-6 animate-fade-in"
                    style="animation-delay: 0.3s;">
                    Fill out the form below to secure your perfect getaway
                </p>
            </div>
        </div>
    </section>


    <!-- Main Content with Cart Sidebar -->
    <div class="container mx-auto px-4 flex gap-8 bg-gray-50 py-8">
        <!-- Availability Section -->
        <section class="flex-1 py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @forelse($rooms ?? [] as $room)
                        <!-- Room Card -->
                        <div
                            class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                            <!-- Room Image -->
                            <div class="relative h-[300px] overflow-hidden cursor-pointer"
                                data-bs-target="#roomModal{{ $room->id }}">
                                <a href="{{ route('user.rooms.show', $room->id) }}">
                                                                    <img src="{{ asset($room->image ?? 'images/user/luxury-ocean-view-suite-hotel-room.jpg') }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                    alt="{{ $room->name }}">
                                </a>
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
                                <a href="{{ route('user.rooms.show', $room->id) }}">More info →</a>
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
                                <span class="text-gray-600 text-sm">Qty:
                                    <div class="flex items-center space-x-2">
                                        <button type="button" class="px-2 py-1 bg-gray-200 rounded decrement"
                                            data-id="{{ $item['room_id'] }}">-</button>
                                        <span class="text-gray-600 text-sm">{{ $item['quantity'] }}</span>
                                        <button type="button" class="px-2 py-1 bg-gray-200 rounded increment"
                                            data-id="{{ $item['room_id'] }}">+</button>
                                    </div>
                                </span>
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
                            Swal.fire({
                                title: "Room added to cart successfully!",
                                icon: "success",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Go Back"
                            }).then(() => {
                                location.reload(); // Reload after user clicks "Go Back"
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Minimal modal toggling (Tailwind) for this page
            document.addEventListener('click', (e) => {
                const opener = e.target.closest('[data-bs-toggle="modal"]');
                if (opener) {
                    const targetSel = opener.getAttribute('data-bs-target');
                    if (targetSel) {
                        const el = document.querySelector(targetSel);
                        if (el) {
                            e.preventDefault();
                            el.classList.remove('hidden');
                        }
                    }
                }
                const closer = e.target.closest('[data-bs-dismiss="modal"]');
                if (closer) {
                    const modal = closer.closest('.modal');
                    if (modal) {
                        e.preventDefault();
                        modal.classList.add('hidden');
                    }
                }
            });


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
                            Swal.fire({
                                title: "Room removed from cart!",
                                icon: "success",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK"
                            }).then(() => {
                                location.reload();
                            });
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
                window.location.href = '{{ route('user.reserve.index') }}';
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

            function addToCart(roomId, roomName, roomPrice) {
                Swal.fire({
                    title: 'Adding to cart...',
                    html: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
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
                        Swal.close(); // Close the loading alert
                        if (data.success) {
                            Swal.fire({
                                title: "Room Added",
                                html: `
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
                    <div style="
                        font-family:'Poppins',sans-serif;
                        font-size:15px;
                        color:#444;
                        margin-top:8px;">
                        <strong>${roomName}</strong> has been added to your cart.
                    </div>
                `,
                                icon: "success",
                                background: "#ffffff",
                                color: "#333",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Continue",
                                customClass: {
                                    popup: 'swal-clean',
                                    confirmButton: 'swal-btn'
                                }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Something went wrong. Please try again.",
                                icon: "error",
                                confirmButtonColor: "#d33"
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong. Please try again.",
                            icon: "error",
                            confirmButtonColor: "#d33"
                        });
                    });
            }


            function removeFromCart(roomId, roomName) {
                Swal.fire({
                    title: 'Removing from cart...',
                    html: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

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
                        Swal.close();
                        if (data.success) {
                            Swal.fire({
                                title: "Room Removed",
                                html: `
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
                    <div style="
                        font-family:'Poppins',sans-serif;
                        font-size:15px;
                        color:#444;
                        margin-top:8px;">
                        <strong>${roomName}</strong> has been removed from your cart.
                    </div>
                `,
                                icon: "success",
                                background: "#ffffff",
                                color: "#333",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Continue",
                                customClass: {
                                    popup: 'swal-clean',
                                    confirmButton: 'swal-btn'
                                }
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: "Something went wrong. Please try again.",
                                icon: "error",
                                confirmButtonColor: "#d33"
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong. Please try again.",
                            icon: "error",
                            confirmButtonColor: "#d33"
                        });
                    });
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
