@php
    $cart = session('cart', []);
    $cartTotal = collect($cart)->sum(fn($item) => ($item['room_price'] ?? 0) * ($item['quantity'] ?? 0));
@endphp

<div class="lg:col-span-4">
    <div class="sticky top-24">
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-xl border border-slate-100">
            <h5 class="text-lg font-bold text-teal-900 mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                <i class="fas fa-receipt text-yellow-500"></i> Reservation Summary
            </h5>

            <div id="cartItems" class="max-h-[400px] overflow-y-auto mb-6 custom-scrollbar pr-2">
                @if (empty($cart))
                    <div class="text-center py-10">
                        <div
                            class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-400 text-2xl">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p class="text-slate-500 text-sm">No rooms selected</p>
                    </div>
                @else
                    @foreach ($cart as $item)
                        <div class="flex gap-4 mb-6 relative group cart-item" data-room-id="{{ $item['room_id'] }}">
                            <div
                                class="w-16 h-16 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                                <i class="fas fa-bed text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h6 class="text-teal-900 font-bold text-sm leading-tight pr-2">
                                        {{ $item['room_name'] }}
                                    </h6>
                                    <button type="button" onclick="removeFromCart({{ $item['room_id'] }})"
                                        class="text-red-500 hover:text-white hover:bg-red-500 rounded-full w-7 h-7 flex items-center justify-center flex-shrink-0 border border-red-300 hover:border-red-500"
                                        title="Remove room">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                                <div class="flex justify-between items-center text-xs text-slate-500 mb-1">
                                    <span>PHP {{ number_format($item['room_price'], 2) }} / night</span>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600 font-medium">
                                        Qty: {{ $item['quantity'] }}
                                    </span>
                                    <span class="text-teal-700 font-bold text-sm item-total"
                                        data-price="{{ $item['room_price'] }}" data-qty="{{ $item['quantity'] }}">
                                        PHP {{ number_format($item['room_price'] * $item['quantity'], 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            @if (!empty($cart))
                <div class="border-t-2 border-dashed border-slate-200 pt-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal (per night)</span>
                        <span class="font-semibold text-slate-700" id="subtotalPerNight">
                            PHP {{ number_format($cartTotal, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Number of Nights</span>
                        <span class="font-semibold text-slate-700" id="nightsDisplay">1 night(s)</span>
                    </div>
                    <div class="flex justify-between items-end pt-2">
                        <span class="text-base font-bold text-teal-900">Total</span>
                        <span class="text-2xl font-bold text-teal-700" id="grandTotal">
                            PHP {{ number_format($cartTotal, 2) }}
                        </span>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-400">
                    <i class="fas fa-lock"></i> Secure SSL Encrypted
                </div>
            @endif
        </div>
    </div>
</div>
