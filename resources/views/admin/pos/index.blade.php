@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-gray-50 via-slate-100 to-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="mx-auto max-w-6xl relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-600 via-emerald-500 to-cyan-500 text-white shadow-2xl">
                <div class="relative p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                        <div class="space-y-4">
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest rounded-full bg-white/15 text-indigo-100 ring-1 ring-white/30">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                Operations
                            </span>
                            <div class="space-y-2">
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight">Point of Sale</h1>
                                <p class="max-w-2xl text-indigo-100 text-sm md:text-base leading-relaxed">
                                    Seamlessly manage in-house orders, curate kitchen items, and keep track of payments in real-time.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.pos.transactions') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 px-5 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-200 hover:from-emerald-300 hover:via-teal-300 hover:to-cyan-300">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-emerald-500">
                                <i class="fas fa-list"></i>
                            </span>
                            View Transactions
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
                <div class="space-y-6">
                    <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200">
                        <div class="flex flex-col gap-2 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Foods Menu</h2>
                                <p class="text-sm text-slate-500">Select items to add them to the cart.</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                                <div class="group relative flex h-full flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">Grilled Seafood Platter</h3>
                                            <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-teal-500">Signature Dish</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 shadow-sm">
                                            ₱1,250.00
                                        </span>
                                    </div>
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-500">
                                        Fresh catch prawns, squid, and fish grilled to perfection with herb butter and citrus glaze.
                                    </p>
                                    <button class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 px-4 py-2 text-sm font-semibold text-white shadow transition hover:from-emerald-300 hover:via-teal-300 hover:to-cyan-300">
                                        <i class="fas fa-plus-circle"></i>
                                        Add to Cart
                                    </button>
                                </div>

                                <div class="group relative flex h-full flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">Tropical Sunrise Smoothie</h3>
                                            <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-teal-500">Beverage</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 shadow-sm">
                                            ₱190.00
                                        </span>
                                    </div>
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-500">
                                        Pineapple, mango, and coconut milk blended over ice, topped with toasted coconut flakes.
                                    </p>
                                    <button class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 px-4 py-2 text-sm font-semibold text-white shadow transition hover:from-emerald-300 hover:via-teal-300 hover:to-cyan-300">
                                        <i class="fas fa-plus-circle"></i>
                                        Add to Cart
                                    </button>
                                </div>

                                <div class="group relative flex h-full flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">Beachside Burger Stack</h3>
                                            <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-teal-500">Grill</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 shadow-sm">
                                            ₱420.00
                                        </span>
                                    </div>
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-500">
                                        Double patty burger with cheddar, caramelized onions, and house-made aioli on brioche buns.
                                    </p>
                                    <button class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 px-4 py-2 text-sm font-semibold text-white shadow transition hover:from-emerald-300 hover:via-teal-300 hover:to-cyan-300">
                                        <i class="fas fa-plus-circle"></i>
                                        Add to Cart
                                    </button>
                                </div>

                                <div class="group relative flex h-full flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">Lagoon Garden Salad</h3>
                                            <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-teal-500">Greens</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 shadow-sm">
                                            ₱320.00
                                        </span>
                                    </div>
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-500">
                                        Mix of crisp greens, cucumber ribbons, cherry tomatoes, and calamansi vinaigrette.
                                    </p>
                                    <button class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 px-4 py-2 text-sm font-semibold text-white shadow transition hover:from-emerald-300 hover:via-teal-300 hover:to-cyan-300">
                                        <i class="fas fa-plus-circle"></i>
                                        Add to Cart
                                    </button>
                                </div>

                                <div class="group relative flex h-full flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">Cottage Pancake Tower</h3>
                                            <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-teal-500">Breakfast</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 shadow-sm">
                                            ₱280.00
                                        </span>
                                    </div>
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-500">
                                        Fluffy pancakes layered with ube butter, macapuno, and muscovado syrup drizzle.
                                    </p>
                                    <button class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 px-4 py-2 text-sm font-semibold text-white shadow transition hover:from-emerald-300 hover:via-teal-300 hover:to-cyan-300">
                                        <i class="fas fa-plus-circle"></i>
                                        Add to Cart
                                    </button>
                                </div>

                                <div class="group relative flex h-full flex-col rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-900">Sunset S’mores Skillet</h3>
                                            <p class="mt-1 text-xs font-semibold uppercase tracking-widest text-teal-500">Dessert</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold text-white bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 shadow-sm">
                                            ₱350.00
                                        </span>
                                    </div>
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-500">
                                        Toasted marshmallows over melted chocolate chunks served with graham crackers.
                                    </p>
                                    <button class="mt-6 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 px-4 py-2 text-sm font-semibold text-white shadow transition hover:from-emerald-300 hover:via-teal-300 hover:to-cyan-300">
                                        <i class="fas fa-plus-circle"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl bg-white shadow-2xl ring-1 ring-indigo-100">
                        <div class="border-b border-indigo-50 px-6 py-5">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-slate-900">Current Cart</h2>
                                <span class="text-xs uppercase tracking-widest text-indigo-500">Summary</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">Adjust quantities or remove items in real-time.</p>
                        </div>
                        <div class="px-6 py-6 space-y-6">
                            <div id="cart-items" class="space-y-4">
                                @if (empty($cart))
                                    <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-indigo-200 bg-indigo-50/60 px-4 py-10 text-center">
                                        <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-indigo-400 shadow">
                                            <i class="fas fa-shopping-bag"></i>
                                        </span>
                                        <p class="text-sm font-medium text-indigo-700">Cart is empty</p>
                                        <p class="text-xs text-indigo-400">Select items from the menu to get started.</p>
                                    </div>
                                @else
                                    @foreach ($cart as $item)
                                        <div class="cart-item flex items-start gap-4 rounded-xl border border-indigo-100 bg-indigo-50/70 p-4 shadow-sm" data-food-id="{{ $item['id'] }}">
                                            <div class="flex-1 space-y-3">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <p class="text-sm font-semibold text-slate-900">{{ $item['name'] }}</p>
                                                        <p class="text-xs uppercase tracking-widest text-indigo-500">₱{{ number_format($item['price'], 2) }} each</p>
                                                    </div>
                                                    <button class="remove-item inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-500/10 text-red-500 transition hover:bg-red-500 hover:text-white" data-food-id="{{ $item['id'] }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <input type="number"
                                                        class="quantity-input w-20 rounded-lg border border-indigo-200 bg-white px-3 py-2 text-sm font-medium text-slate-900 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                                        value="{{ $item['quantity'] }}" min="1">
                                                    <span class="text-base font-semibold text-slate-900">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="totals mt-6 space-y-4 rounded-xl border border-indigo-100 bg-white p-5 text-sm shadow-sm">
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>Subtotal</span>
                                    <span class="font-semibold text-slate-900">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-500">
                                    <span>Tax (12%)</span>
                                    <span class="font-semibold text-slate-900">₱{{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between border-t border-indigo-100 pt-4 text-base font-semibold text-slate-900">
                                    <span>Total</span>
                                    <span>₱{{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <button id="checkout-btn"
                                class="checkout-btn mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 px-4 py-3 text-sm font-semibold text-white shadow-lg transition hover:from-emerald-400 hover:via-teal-400 hover:to-cyan-400 disabled:cursor-not-allowed disabled:opacity-50"
                                @if (empty($cart)) disabled @endif>
                                <i class="fas fa-credit-card"></i>
                                Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="checkout-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4 py-6">
        <div class="absolute inset-0" id="checkout-modal-overlay"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-slate-900">Checkout</h3>
                <button type="button" id="close-checkout-modal"
                    class="group inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200 hover:text-slate-700">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <div class="px-6 py-5">
                <form id="checkout-form" class="space-y-4">
                    <div>
                        <label for="payment_method" class="block text-sm font-semibold text-slate-700">Payment Method</label>
                        <div class="mt-2 relative">
                            <select id="payment_method" name="payment_method"
                                class="block w-full appearance-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                required>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="online">Online</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">
                <button type="button" id="cancel-checkout"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    Cancel
                </button>
                <button type="button" id="confirm-checkout"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 px-4 py-2 text-sm font-semibold text-white shadow-lg transition hover:from-indigo-500 hover:via-blue-500 hover:to-sky-400">
                    <i class="fas fa-credit-card"></i>
                    Confirm Payment
                </button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Add to cart
            $('.add-to-cart').on('click', function() {
                var foodId = $(this).data('food-id');
                $.post("{{ route('admin.pos.addToCart') }}", {
                    food_id: foodId,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        updateCartDisplay(response.cart);
                    }
                });
            });

            // Update quantity
            $(document).on('change', '.quantity-input', function() {
                var foodId = $(this).closest('.cart-item').data('food-id');
                var quantity = parseInt($(this).val(), 10) || 1;
                if (quantity < 1) {
                    quantity = 1;
                    $(this).val(quantity);
                }

                $.post("{{ route('admin.pos.updateCart') }}", {
                    food_id: foodId,
                    quantity: quantity,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        updateCartDisplay(response.cart);
                    }
                });
            });

            // Remove item
            $(document).on('click', '.remove-item', function() {
                var foodId = $(this).data('food-id');
                $.post("{{ route('admin.pos.removeFromCart') }}", {
                    food_id: foodId,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        updateCartDisplay(response.cart);
                    }
                });
            });

            // Checkout
            var checkoutModal = $('#checkout-modal');

            function openCheckoutModal() {
                checkoutModal.removeClass('hidden').addClass('flex');
                $('body').addClass('overflow-hidden');
                $('#payment_method').trigger('focus');
            }

            function closeCheckoutModal() {
                checkoutModal.removeClass('flex').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }

            $('#checkout-btn').click(function() {
                if (!$(this).prop('disabled')) {
                    openCheckoutModal();
                }
            });

            $('#close-checkout-modal, #cancel-checkout, #checkout-modal-overlay').on('click', function() {
                closeCheckoutModal();
            });

            $(document).on('keyup', function(event) {
                if (event.key === 'Escape' && checkoutModal.hasClass('flex')) {
                    closeCheckoutModal();
                }
            });

            $('#confirm-checkout').click(function() {
                var paymentMethod = $('#payment_method').val();
                $.post("{{ route('admin.pos.checkout') }}", {
                    payment_method: paymentMethod,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        closeCheckoutModal();
                        alert('Transaction completed successfully!');
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                });
            });

            function updateCartDisplay(cart) {
                var cartHtml = '';
                var subtotal = 0;
                for (var foodId in cart) {
                    var item = cart[foodId];
                    var itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;
                    cartHtml += `
                <div class="cart-item flex items-start gap-4 rounded-xl border border-indigo-100 bg-indigo-50/70 p-4 shadow-sm" data-food-id="${item.id}">
                    <div class="flex-1 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">${item.name}</p>
                                <p class="text-xs uppercase tracking-widest text-indigo-500">₱${parseFloat(item.price).toFixed(2)} each</p>
                            </div>
                            <button class="remove-item inline-flex h-8 w-8 items-center justify-center rounded-full bg-red-500/10 text-red-500 transition hover:bg-red-500 hover:text-white" data-food-id="${item.id}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <input type="number" class="quantity-input w-20 rounded-lg border border-indigo-200 bg-white px-3 py-2 text-sm font-medium text-slate-900 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300" value="${item.quantity}" min="1">
                            <span class="text-base font-semibold text-slate-900">₱${itemTotal.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            `;
                }
                if (cartHtml === '') {
                    cartHtml = `
                <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-indigo-200 bg-indigo-50/60 px-4 py-10 text-center">
                    <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-indigo-400 shadow">
                        <i class="fas fa-shopping-bag"></i>
                    </span>
                    <p class="text-sm font-medium text-indigo-700">Cart is empty</p>
                    <p class="text-xs text-indigo-400">Select items from the menu to get started.</p>
                </div>`;
                }
                $('#cart-items').html(cartHtml);

                var tax = subtotal * 0.12;
                var total = subtotal + tax;
                $('.totals').html(`
            <div class="flex items-center justify-between text-slate-500">
                <span>Subtotal</span>
                <span class="font-semibold text-slate-900">₱${subtotal.toFixed(2)}</span>
            </div>
            <div class="flex items-center justify-between text-slate-500">
                <span>Tax (12%)</span>
                <span class="font-semibold text-slate-900">₱${tax.toFixed(2)}</span>
            </div>
            <div class="flex items-center justify-between border-t border-indigo-100 pt-4 text-base font-semibold text-slate-900">
                <span>Total</span>
                <span>₱${total.toFixed(2)}</span>
            </div>
        `);

                $('#checkout-btn').prop('disabled', Object.keys(cart).length === 0);
            }
        });
    </script>
@endsection
