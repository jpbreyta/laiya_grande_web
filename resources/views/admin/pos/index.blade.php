@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-teal-50/30 to-cyan-50/50 py-6">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Point of Sale</h1>
                        <p class="text-sm text-slate-600 mt-1">Manage orders and process payments efficiently</p>
                    </div>
                    <a href="{{ route('admin.pos.transactions') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-white border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md">
                        <i class="fas fa-history"></i>
                        View Transactions
                    </a>
                </div>
            </div>

            <!-- Main Layout -->
            <div class="grid gap-6 lg:grid-cols-[1fr_420px]">
                <!-- Menu Section -->
                <div class="space-y-4">
                    <!-- Search and Filter Bar -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1 relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" id="search-input" placeholder="Search items..."
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                            </div>
                            <select id="category-filter"
                                class="px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                                <option value="">All Categories</option>
                                @php
                                    $categories = $foods->pluck('category')->filter()->unique('id')->values();
                                @endphp
                                @foreach ($categories as $category)
                                    @if($category)
                                        <option value="{{ strtolower($category->name) }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Menu Items Grid -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-slate-900">Menu Items</h2>
                            <span id="item-count" class="text-sm text-slate-500">{{ count($foods) }} items</span>
                        </div>
                        <div id="menu-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach ($foods as $food)
                                <div class="menu-item food-card group relative bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-5 transition-all duration-200 hover:shadow-lg hover:-translate-y-1 hover:border-cyan-300"
                                    data-name="{{ strtolower($food->name) }}"
                                    data-category="{{ strtolower($food->category->name) }}">
                                    <div class="flex items-start justify-between gap-3 mb-3">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-semibold text-slate-900 truncate">{{ $food->name }}
                                            </h3>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $food->category->name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                                        <span class="text-lg font-bold text-cyan-600">₱{{ number_format($food->price, 2) }}</span>
                                        <button
                                            class="add-to-cart flex items-center justify-center gap-2 rounded-lg bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 text-sm font-medium transition-all shadow-sm hover:shadow-md active:scale-95"
                                            data-food-id="{{ $food->id }}">
                                            <i class="fas fa-plus text-xs"></i>
                                            <span>Add</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="no-results" class="hidden text-center py-12">
                            <i class="fas fa-search text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">No items found</p>
                        </div>
                    </div>
                </div>

                <!-- Cart Section -->
                <div class="lg:sticky lg:top-6 h-fit">
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
                        <!-- Cart Header -->
                        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg font-bold text-white">Shopping Cart</h2>
                                    <p class="text-xs text-cyan-100 mt-0.5" id="cart-item-count">
                                        @if (!empty($cart))
                                            {{ count($cart) }} item(s)
                                        @else
                                            0 items
                                        @endif
                                    </p>
                                </div>
                                <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-shopping-cart text-white"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div class="px-4 py-4 max-h-[500px] overflow-y-auto" id="cart-container">
                            <div id="cart-items" class="space-y-3">
                                @if (empty($cart))
                                    <div class="flex flex-col items-center justify-center py-12 text-center">
                                        <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                            <i class="fas fa-shopping-bag text-2xl text-slate-400"></i>
                                        </div>
                                        <p class="text-sm font-medium text-slate-600 mb-1">Your cart is empty</p>
                                        <p class="text-xs text-slate-400">Add items from the menu to get started</p>
                                    </div>
                                @else
                                    @foreach ($cart as $item)
                                        <div class="cart-item bg-slate-50 rounded-lg border border-slate-200 p-4 transition-all"
                                            data-food-id="{{ $item['id'] }}">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2 mb-2">
                                                        <h4 class="text-sm font-semibold text-slate-900 truncate">
                                                            {{ $item['name'] }}</h4>
                                                        <button
                                                            class="remove-item flex-shrink-0 h-6 w-6 rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors flex items-center justify-center"
                                                            data-food-id="{{ $item['id'] }}" title="Remove item">
                                                            <i class="fas fa-times text-xs"></i>
                                                        </button>
                                                    </div>
                                                    <p class="text-xs text-slate-500 mb-3">₱{{ number_format($item['price'], 2) }} each</p>
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-2">
                                                            <button
                                                                class="quantity-decrease h-7 w-7 rounded border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors"
                                                                data-food-id="{{ $item['id'] }}">
                                                                <i class="fas fa-minus text-xs"></i>
                                                            </button>
                                                            <input type="number"
                                                                class="quantity-input w-14 text-center border border-slate-300 rounded bg-white text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                                                                value="{{ $item['quantity'] }}" min="1" readonly>
                                                            <button
                                                                class="quantity-increase h-7 w-7 rounded border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors"
                                                                data-food-id="{{ $item['id'] }}">
                                                                <i class="fas fa-plus text-xs"></i>
                                                            </button>
                                                        </div>
                                                        <span class="text-sm font-bold text-slate-900">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="border-t border-slate-200 bg-slate-50 px-4 py-4 space-y-3">
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between text-slate-600">
                                    <span>Subtotal</span>
                                    <span class="font-semibold text-slate-900">₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between text-slate-600">
                                    <span>Tax (12%)</span>
                                    <span class="font-semibold text-slate-900">₱{{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                                    <span class="font-bold text-slate-900">Total</span>
                                    <span class="text-lg font-bold text-cyan-600">₱{{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                            <button id="checkout-btn"
                                class="checkout-btn w-full mt-4 flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white px-4 py-3.5 text-sm font-semibold shadow-lg transition-all hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:shadow-lg"
                                @if (empty($cart)) disabled @endif>
                                <i class="fas fa-credit-card"></i>
                                <span>Proceed to Checkout</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Checkout Modal -->
    <div id="checkout-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm px-4 py-6">
        <div class="absolute inset-0" id="checkout-modal-overlay"></div>
        <div class="relative w-full max-w-md rounded-xl bg-white shadow-2xl">
            <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">Complete Payment</h3>
                    <button type="button" id="close-checkout-modal"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/20 text-white transition hover:bg-white/30">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            <div class="px-6 py-6">
                <form id="checkout-form" class="space-y-5">
                    <div>
                        <label for="payment_method" class="block text-sm font-semibold text-slate-700 mb-2">Payment
                            Method</label>
                        <div class="relative">
                            <select id="payment_method" name="payment_method"
                                class="block w-full appearance-none rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20"
                                required>
                                <option value="cash">💵 Cash</option>
                                <option value="card">💳 Card</option>
                                <option value="online">🌐 Online Payment</option>
                            </select>
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                        <p class="text-xs text-slate-500 mb-2">Total Amount</p>
                        <p id="modal-total" class="text-2xl font-bold text-cyan-600">₱{{ number_format($total, 2) }}</p>
                    </div>
                </form>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4 bg-slate-50 rounded-b-xl">
                <button type="button" id="cancel-checkout"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Cancel
                </button>
                <button type="button" id="confirm-checkout"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:shadow-xl">
                    <i class="fas fa-check"></i>
                    Confirm Payment
                </button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Search and Filter functionality
            function filterMenu() {
                var searchTerm = $('#search-input').val().toLowerCase().trim();
                var categoryFilter = $('#category-filter').val().toLowerCase().trim();
                var visibleCount = 0;

                $('.menu-item').each(function() {
                    var $item = $(this);
                    var itemName = $item.data('name') || '';
                    var itemCategory = $item.data('category') || '';
                    
                    // Normalize strings for comparison
                    itemName = itemName.toLowerCase().trim();
                    itemCategory = itemCategory.toLowerCase().trim();
                    
                    var matchesSearch = searchTerm === '' || itemName.includes(searchTerm);
                    var matchesCategory = categoryFilter === '' || itemCategory === categoryFilter;

                    if (matchesSearch && matchesCategory) {
                        $item.show();
                        visibleCount++;
                    } else {
                        $item.hide();
                    }
                });

                $('#item-count').text(visibleCount + ' item' + (visibleCount !== 1 ? 's' : ''));
                $('#no-results').toggle(visibleCount === 0);
                $('#menu-grid').toggle(visibleCount > 0);
            }

            $('#search-input').on('input', filterMenu);
            $('#category-filter').on('change', filterMenu);
            
            // Initialize filter on page load
            filterMenu();

            // Add to cart with animation
            $('.add-to-cart').on('click', function() {
                var $btn = $(this);
                var foodId = $btn.data('food-id');
                
                // Button animation
                $btn.addClass('opacity-75').prop('disabled', true);
                
                $.post("{{ route('admin.pos.addToCart') }}", {
                    food_id: foodId,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        updateCartDisplay(response.cart);
                        // Reset button
                        $btn.removeClass('opacity-75').prop('disabled', false);
                    }
                }).fail(function() {
                    $btn.removeClass('opacity-75').prop('disabled', false);
                });
            });

            // Quantity controls
            $(document).on('click', '.quantity-increase', function() {
                var foodId = $(this).data('food-id');
                var $input = $(this).siblings('.quantity-input');
                var currentQty = parseInt($input.val(), 10) || 1;
                $input.val(currentQty + 1);
                updateQuantity(foodId, currentQty + 1);
            });

            $(document).on('click', '.quantity-decrease', function() {
                var foodId = $(this).data('food-id');
                var $input = $(this).siblings('.quantity-input');
                var currentQty = parseInt($input.val(), 10) || 1;
                if (currentQty > 1) {
                    $input.val(currentQty - 1);
                    updateQuantity(foodId, currentQty - 1);
                }
            });

            // Update quantity on input change
            $(document).on('change', '.quantity-input', function() {
                var foodId = $(this).closest('.cart-item').data('food-id');
                var quantity = parseInt($(this).val(), 10) || 1;
                if (quantity < 1) {
                    quantity = 1;
                    $(this).val(quantity);
                }
                updateQuantity(foodId, quantity);
            });

            function updateQuantity(foodId, quantity) {
                $.post("{{ route('admin.pos.updateCart') }}", {
                    food_id: foodId,
                    quantity: quantity,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        updateCartDisplay(response.cart);
                    }
                });
            }

            // Remove item
            $(document).on('click', '.remove-item', function() {
                if (!confirm('Remove this item from cart?')) return;
                
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

            // Checkout Modal
            var checkoutModal = $('#checkout-modal');

            function openCheckoutModal() {
                var total = $('.border-t.border-slate-200.bg-slate-50').first().find('.text-lg.font-bold.text-cyan-600').text() || '₱0.00';
                $('#modal-total').text(total);
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

            $('#close-checkout-modal, #cancel-checkout, #checkout-modal-overlay').on('click', function(e) {
                if (e.target === this || $(e.target).closest('#checkout-modal-overlay, #close-checkout-modal, #cancel-checkout').length) {
                    closeCheckoutModal();
                }
            });

            $(document).on('keyup', function(event) {
                if (event.key === 'Escape' && checkoutModal.hasClass('flex')) {
                    closeCheckoutModal();
                }
            });

            $('#confirm-checkout').click(function() {
                var $btn = $(this);
                var paymentMethod = $('#payment_method').val();
                
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                
                $.post("{{ route('admin.pos.checkout') }}", {
                    payment_method: paymentMethod,
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        closeCheckoutModal();
                        // Show success message
                        alert('Transaction completed successfully!');
                        location.reload();
                    } else {
                        alert(response.message || 'An error occurred. Please try again.');
                        $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Confirm Payment');
                    }
                }).fail(function() {
                    alert('An error occurred. Please try again.');
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Confirm Payment');
                });
            });

            // Update cart display
            function updateCartDisplay(cart) {
                var cartHtml = '';
                var subtotal = 0;
                var itemCount = 0;

                for (var foodId in cart) {
                    var item = cart[foodId];
                    var itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;
                    itemCount += item.quantity;
                    
                    cartHtml += `
                        <div class="cart-item bg-slate-50 rounded-lg border border-slate-200 p-4 transition-all" data-food-id="${item.id}">
                            <div class="flex items-start gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h4 class="text-sm font-semibold text-slate-900 truncate">${item.name}</h4>
                                        <button class="remove-item flex-shrink-0 h-6 w-6 rounded-full bg-red-50 text-red-500 hover:bg-red-100 transition-colors flex items-center justify-center" data-food-id="${item.id}" title="Remove item">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    <p class="text-xs text-slate-500 mb-3">₱${parseFloat(item.price).toFixed(2)} each</p>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <button class="quantity-decrease h-7 w-7 rounded border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors" data-food-id="${item.id}">
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <input type="number" class="quantity-input w-14 text-center border border-slate-300 rounded bg-white text-sm font-medium text-slate-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent" value="${item.quantity}" min="1" readonly>
                                            <button class="quantity-increase h-7 w-7 rounded border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors" data-food-id="${item.id}">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </div>
                                        <span class="text-sm font-bold text-slate-900">₱${itemTotal.toFixed(2)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                if (cartHtml === '') {
                    cartHtml = `
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="h-16 w-16 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                <i class="fas fa-shopping-bag text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Your cart is empty</p>
                            <p class="text-xs text-slate-400">Add items from the menu to get started</p>
                        </div>
                    `;
                }

                $('#cart-items').html(cartHtml);
                $('#cart-item-count').text(itemCount + ' item' + (itemCount !== 1 ? 's' : ''));

                var tax = subtotal * 0.12;
                var total = subtotal + tax;
                
                // Update totals section
                var totalsHtml = `
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-900">₱${subtotal.toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Tax (12%)</span>
                            <span class="font-semibold text-slate-900">₱${tax.toFixed(2)}</span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                            <span class="font-bold text-slate-900">Total</span>
                            <span class="text-lg font-bold text-cyan-600">₱${total.toFixed(2)}</span>
                        </div>
                    </div>
                `;
                
                // Update totals in cart summary
                var $cartSummary = $('.border-t.border-slate-200.bg-slate-50').first();
                if ($cartSummary.length) {
                    $cartSummary.find('.space-y-2').replaceWith(totalsHtml);
                }

                $('#checkout-btn').prop('disabled', Object.keys(cart).length === 0);
            }
        });
    </script>
@endsection
