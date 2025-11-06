@extends('admin.layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Point of Sale</h1>
                <a href="{{ route('admin.pos.transactions') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-list mr-2"></i>
                    View Transactions
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Foods List -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Foods Menu</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                @foreach ($foods as $food)
                                    <div
                                        class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <div class="p-6">
                                            <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ $food->name }}</h5>
                                            <p class="text-gray-600 text-sm mb-4">{{ $food->description }}</p>
                                            <div class="flex justify-between items-center">
                                                <span
                                                    class="text-xl font-bold text-green-600">₱{{ number_format($food->price, 2) }}</span>
                                                <button
                                                    class="add-to-cart inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                    data-food-id="{{ $food->id }}">
                                                    Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Cart</h3>
                        </div>
                        <div class="p-6">
                            <div id="cart-items" class="space-y-4 mb-6">
                                @if (empty($cart))
                                    <p class="text-gray-500 text-center py-8">Cart is empty</p>
                                @else
                                    @foreach ($cart as $item)
                                        <div class="cart-item flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                                            data-food-id="{{ $item['id'] }}">
                                            <div class="flex-1">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="font-medium text-gray-900">{{ $item['name'] }}</span>
                                                    <button
                                                        class="remove-item text-red-600 hover:text-red-800 focus:outline-none"
                                                        data-food-id="{{ $item['id'] }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="flex justify-between items-center">
                                                    <input type="number"
                                                        class="quantity-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                        value="{{ $item['quantity'] }}" min="1">
                                                    <span
                                                        class="font-semibold text-gray-900">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="totals space-y-2 mb-6">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-medium">₱{{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Tax (12%):</span>
                                        <span class="font-medium">₱{{ number_format($tax, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                                        <span>Total:</span>
                                        <span>₱{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>

                                <button
                                    class="checkout-btn w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="checkout-btn" @if (empty($cart)) disabled @endif>
                                    Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Checkout</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="checkout-form">
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select class="form-control" name="payment_method" required>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirm-checkout">Confirm Payment</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Add to cart
            $('.add-to-cart').click(function() {
                var foodId = $(this).data('food-id');
                $.post('{{ route('admin.pos.addToCart') }}', {
                    food_id: foodId,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.success) {
                        updateCartDisplay(response.cart);
                    }
                });
            });

            // Update quantity
            $(document).on('change', '.quantity-input', function() {
                var foodId = $(this).closest('.cart-item').data('food-id');
                var quantity = $(this).val();
                $.post('{{ route('admin.pos.updateCart') }}', {
                    food_id: foodId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.success) {
                        updateCartDisplay(response.cart);
                    }
                });
            });

            // Remove item
            $(document).on('click', '.remove-item', function() {
                var foodId = $(this).data('food-id');
                $.post('{{ route('admin.pos.removeFromCart') }}', {
                    food_id: foodId,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.success) {
                        updateCartDisplay(response.cart);
                    }
                });
            });

            // Checkout
            $('#checkout-btn').click(function() {
                $('#checkoutModal').modal('show');
            });

            $('#confirm-checkout').click(function() {
                var paymentMethod = $('select[name=payment_method]').val();
                $.post('{{ route('admin.pos.checkout') }}', {
                    payment_method: paymentMethod,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.success) {
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
                <div class="cart-item flex items-center justify-between p-4 bg-gray-50 rounded-lg" data-food-id="${item.id}">
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-gray-900">${item.name}</span>
                            <button class="remove-item text-red-600 hover:text-red-800 focus:outline-none" data-food-id="${item.id}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="flex justify-between items-center">
                            <input type="number" class="quantity-input w-16 px-2 py-1 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500" value="${item.quantity}" min="1">
                            <span class="font-semibold text-gray-900">₱${itemTotal.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
            `;
                }
                if (cartHtml === '') {
                    cartHtml = '<p class="text-gray-500 text-center py-8">Cart is empty</p>';
                }
                $('#cart-items').html(cartHtml);

                var tax = subtotal * 0.12;
                var total = subtotal + tax;
                $('.totals').html(`
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal:</span>
                <span class="font-medium">₱${subtotal.toFixed(2)}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Tax (12%):</span>
                <span class="font-medium">₱${tax.toFixed(2)}</span>
            </div>
            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-2">
                <span>Total:</span>
                <span>₱${total.toFixed(2)}</span>
            </div>
        `);

                $('#checkout-btn').prop('disabled', Object.keys(cart).length === 0);
            }
        });
    </script>
@endsection
