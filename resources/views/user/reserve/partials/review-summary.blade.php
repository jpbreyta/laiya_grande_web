@php
    $cart = session('cart', []);
    $reservation = session('reservation_data', []);
    $checkIn = isset($reservation['check_in']) ? \Carbon\Carbon::parse($reservation['check_in']) : null;
    $checkOut = isset($reservation['check_out']) ? \Carbon\Carbon::parse($reservation['check_out']) : null;
    $nights = $checkIn && $checkOut ? max(1, $checkIn->diffInDays($checkOut)) : 1;
    $subtotal = collect($cart)->sum(fn($item) => ($item['room_price'] ?? 0) * ($item['quantity'] ?? 0));
    $total = $subtotal * $nights;
@endphp

<div class="lg:col-span-4">
    <div class="sticky top-24 space-y-6">
        {{-- Reservation Summary --}}
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-xl border border-slate-100">
            <h5 class="text-lg font-bold text-teal-900 mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                <i class="fas fa-receipt text-yellow-500"></i> Reservation Summary
            </h5>

            <div class="space-y-4 mb-6">
                @if (!empty($cart))
                    @foreach ($cart as $item)
                        <div class="flex gap-4 pb-4 border-b border-slate-100 last:border-0">
                            <div
                                class="w-16 h-16 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 flex-shrink-0">
                                <i class="fas fa-bed text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h6 class="text-teal-900 font-bold text-sm leading-tight mb-1">
                                    {{ $item['room_name'] }}
                                </h6>
                                <p class="text-xs text-slate-500 mb-2">
                                    PHP {{ number_format($item['room_price'], 2) }} / night
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs bg-slate-100 px-2 py-1 rounded text-slate-600 font-medium">
                                        Qty: {{ $item['quantity'] }}
                                    </span>
                                    <span class="text-teal-700 font-bold text-sm">
                                        PHP {{ number_format($item['room_price'] * $item['quantity'] * $nights, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-inbox text-slate-300 text-3xl mb-2"></i>
                        <p class="text-slate-500 text-sm">No rooms in cart</p>
                    </div>
                @endif
            </div>

            @if (!empty($cart))
                <div class="border-t-2 border-dashed border-slate-200 pt-6 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal (per night)</span>
                        <span class="font-semibold text-slate-700">
                            PHP {{ number_format($subtotal, 2) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Number of Nights</span>
                        <span class="font-semibold text-slate-700">
                            {{ $nights }} {{ Str::plural('night', $nights) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-end pt-3 border-t border-slate-200">
                        <span class="text-base font-bold text-teal-900">Total Amount</span>
                        <span class="text-2xl font-bold text-teal-700">
                            PHP {{ number_format($total, 2) }}
                        </span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Contact Support --}}
        <div class="bg-gradient-to-br from-teal-50 to-blue-50 p-6 rounded-2xl border border-teal-100">
            <h6 class="text-sm font-bold text-teal-900 mb-3 flex items-center gap-2">
                <i class="fas fa-headset"></i>
                Need Help?
            </h6>
            <p class="text-xs text-slate-600 mb-4">
                Contact our support team if you have any questions about your reservation.
            </p>
            <div class="space-y-2 text-xs">
                <a href="mailto:support@laiyagrande.com"
                    class="flex items-center gap-2 text-teal-700 hover:text-teal-900">
                    <i class="fas fa-envelope"></i>
                    <span>support@laiyagrande.com</span>
                </a>
                <a href="tel:+639123456789" class="flex items-center gap-2 text-teal-700 hover:text-teal-900">
                    <i class="fas fa-phone"></i>
                    <span>+63 912 345 6789</span>
                </a>
            </div>
        </div>
    </div>
</div>
