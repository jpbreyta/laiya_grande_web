@php($allowRemove = $allowRemove ?? true)
<aside class="lg:col-span-4">
    <div class="sticky top-24 rounded-3xl border border-slate-100 bg-white p-6 shadow-lg shadow-teal-900/5 sm:p-7">
        <h2 class="mb-5 flex items-center gap-2 border-b border-slate-100 pb-4 text-lg font-bold text-teal-900">
            <i class="fas fa-receipt text-amber-500" aria-hidden="true"></i>
            Stay Summary
        </h2>

        <div class="space-y-4" data-cart-items>
            @forelse ($cart as $item)
                @php($lineSubtotal = (float) $item['room_price'] * (int) $item['quantity'])
                <article class="rounded-2xl border border-slate-100 p-4" data-cart-item data-room-id="{{ $item['room_id'] }}">
                    <div class="flex gap-3">
                        <img src="{{ $item['room_image'] }}" alt="" class="h-16 w-16 rounded-xl object-cover" loading="lazy" decoding="async">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="truncate text-sm font-bold text-teal-950">{{ $item['room_name'] }}</h3>
                                    <p class="text-xs text-slate-500">{{ $item['rate_name'] ?? 'Room rate' }}</p>
                                </div>
                                @if ($allowRemove)
                                    @php($removeUrl = \Illuminate\Support\Facades\Route::has('cart.remove') ? route('cart.remove', $item['room_id']) : url('/cart/remove/' . $item['room_id']))
                                    <button type="button" data-remove-cart data-remove-url="{{ $removeUrl }}"
                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-red-500 transition hover:bg-red-50"
                                        aria-label="Remove {{ $item['room_name'] }}">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="mt-3 flex items-end justify-between gap-2 text-xs">
                                <span class="text-slate-500">PHP {{ number_format($item['room_price'], 2) }} × {{ $item['quantity'] }}</span>
                                <strong class="text-teal-800" data-item-total data-line-subtotal="{{ $lineSubtotal }}">
                                    PHP {{ number_format($lineSubtotal * $nights, 2) }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl bg-slate-50 p-8 text-center text-sm text-slate-500">
                    No rooms are currently selected.
                </div>
            @endforelse
        </div>

        <dl class="mt-6 space-y-3 border-t border-slate-100 pt-5 text-sm">
            <div class="flex justify-between gap-3 text-slate-600">
                <dt>Nightly subtotal</dt>
                <dd>PHP {{ number_format($cartSubtotal, 2) }}</dd>
            </div>
            <div class="flex justify-between gap-3 text-slate-600">
                <dt>Duration</dt>
                <dd><span data-nights>{{ $nights }}</span> night(s)</dd>
            </div>
            <div class="flex justify-between gap-3 border-t border-slate-100 pt-3 text-base font-bold text-teal-950">
                <dt>Total</dt>
                <dd data-total>PHP {{ number_format($cartTotal, 2) }}</dd>
            </div>
        </dl>

        <p class="mt-4 rounded-xl bg-slate-50 p-3 text-xs leading-relaxed text-slate-500">
            Final pricing and room availability are rechecked by the server before the booking is saved.
        </p>
    </div>
</aside>
