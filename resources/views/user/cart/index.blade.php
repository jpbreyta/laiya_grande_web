@extends('user.layouts.app')

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap');

        .font-heading {
            font-family: 'Playfair Display', serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm {
            background-color: #0f766e !important;
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
                    Your Selection
                </span>
                <h1 class="text-4xl sm:text-5xl font-heading font-bold text-white tracking-tight mb-4">
                    Shopping Cart
                </h1>
                <p class="text-teal-100 text-lg max-w-2xl mx-auto font-light leading-relaxed">
                    Review your selected rooms before proceeding to checkout.
                </p>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            @if (!empty($cart) && count($cart) > 0)
                <div class="flex flex-col lg:flex-row gap-8">

                    <!-- Cart Items -->
                    <div class="flex-1">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="bg-teal-900 px-6 py-4">
                                <h2 class="text-white font-heading font-semibold text-xl flex items-center gap-2">
                                    <i class="fas fa-shopping-cart"></i> Cart Items ({{ count($cart) }})
                                </h2>
                            </div>

                            <div class="p-6 space-y-4">
                                @foreach ($cart as $id => $item)
                                    <div
                                        class="group bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                                        <div class="flex gap-4">
                                            <!-- Room Image -->
                                            <div class="w-32 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-slate-100">
                                                <img src="{{ $item['room_image'] ?? asset('images/user/luxury-ocean-view-suite-hotel-room.jpg') }}"
                                                    alt="{{ $item['room_name'] }}" class="w-full h-full object-cover">
                                            </div>

                                            <!-- Room Details -->
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start mb-2">
                                                    <h3 class="font-heading font-bold text-lg text-slate-800">
                                                        {{ $item['room_name'] }}
                                                    </h3>
                                                    <button
                                                        onclick="removeFromCart({{ $id }}, '{{ addslashes($item['room_name']) }}')"
                                                        class="text-slate-400 hover:text-red-500 transition-colors p-2">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>

                                                <p class="text-sm text-slate-500 mb-3">
                                                    Price per night: <span
                                                        class="font-semibold text-slate-700">₱{{ number_format($item['room_price'], 0) }}</span>
                                                </p>

                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <span class="text-sm text-slate-600">Quantity:</span>
                                                        <div class="flex items-center gap-2">
                                                            <button
                                                                onclick="updateQuantity({{ $id }}, 'decrement')"
                                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors">
                                                                <i class="fas fa-minus text-xs"></i>
                                                            </button>
                                                            <span class="w-12 text-center font-bold text-slate-800"
                                                                id="quantity-{{ $id }}">{{ $item['quantity'] }}</span>
                                                            <button
                                                                onclick="updateQuantity({{ $id }}, 'increment')"
                                                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition-colors">
                                                                <i class="fas fa-plus text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="text-right">
                                                        <p class="text-xs text-slate-500 mb-1">Subtotal</p>
                                                        <p class="font-bold text-xl text-teal-700"
                                                            id="subtotal-{{ $id }}">
                                                            ₱{{ number_format($item['room_price'] * $item['quantity'], 0) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('user.rooms.index') }}"
                                class="inline-flex items-center gap-2 text-teal-700 hover:text-teal-800 font-medium transition-colors">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:w-[400px] flex-shrink-0">
                        <div class="sticky top-8 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                            <div class="bg-teal-900 px-6 py-4">
                                <h3 class="text-white font-heading font-semibold text-lg flex items-center gap-2">
                                    <i class="fas fa-receipt"></i> Order Summary
                                </h3>
                            </div>

                            <div class="p-6">
                                @php
                                    $subtotal = collect($cart)->sum(function ($item) {
                                        return $item['room_price'] * $item['quantity'];
                                    });
                                    $tax = $subtotal * 0.12;
                                    $total = $subtotal + $tax;
                                @endphp

                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between text-slate-600">
                                        <span>Subtotal</span>
                                        <span class="font-medium"
                                            id="cart-subtotal">₱{{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-slate-600">
                                        <span>Taxes & Fees (12%)</span>
                                        <span class="font-medium" id="cart-tax">₱{{ number_format($tax, 2) }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center py-4 border-t border-b border-slate-200 mb-6">
                                    <span class="font-heading font-bold text-lg text-slate-900">Total</span>
                                    <span class="font-bold text-2xl text-teal-700"
                                        id="cart-total">₱{{ number_format($total, 2) }}</span>
                                </div>

                                <div class="space-y-3">
                                    <button onclick="proceedToCheckout()"
                                        class="w-full rounded-xl bg-teal-600 px-4 py-4 text-sm font-bold text-white shadow-lg shadow-teal-700/20 hover:bg-teal-700 hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2">
                                        Proceed to Checkout <i class="fas fa-arrow-right"></i>
                                    </button>

                                    <button onclick="clearCart()"
                                        class="w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3.5 text-sm font-bold text-slate-600 hover:border-red-300 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                                        Clear Cart
                                    </button>
                                </div>

                                <p class="text-center text-xs text-slate-400 mt-4 flex items-center justify-center gap-1">
                                    <i class="fas fa-lock"></i> Secure checkout
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                        <div
                            class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <i class="fas fa-shopping-cart text-4xl"></i>
                        </div>
                        <h2 class="text-2xl font-heading font-bold text-slate-800 mb-3">Your cart is empty</h2>
                        <p class="text-slate-500 mb-8">Start adding rooms to your cart to plan your perfect stay.</p>
                        <a href="{{ route('user.rooms.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-teal-700/20 hover:bg-teal-700 transition-all duration-200">
                            <i class="fas fa-search"></i> Browse Rooms
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function updateQuantity(roomId, action) {
                const route = action === 'increment' ? '{{ route('cart.increment') }}' : '{{ route('cart.decrement') }}';

                fetch(route, {
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
                    });
            }

            function removeFromCart(roomId, roomName) {
                Swal.fire({
                    title: 'Remove this room?',
                    text: `Remove ${roomName} from your cart?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0f766e',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, remove it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('cart.remove', ['id' => ':id']) }}'.replace(':id', roomId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Removed!',
                                        text: 'Room removed from cart.',
                                        icon: 'success',
                                        confirmButtonColor: '#0f766e'
                                    }).then(() => location.reload());
                                }
                            });
                    }
                });
            }

            function clearCart() {
                Swal.fire({
                    title: 'Clear entire cart?',
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
                                text: 'Your cart is now empty.',
                                icon: 'success',
                                confirmButtonColor: '#0f766e'
                            }).then(() => location.reload());
                        });
                    }
                });
            }

            function proceedToCheckout() {
                window.location.href = '{{ route('user.booking.book') }}';
            }
        </script>
    @endpush

@endsection
