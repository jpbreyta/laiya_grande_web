@php($reservation = $reservation ?? session('reservation_data', []))
<aside class="lg:col-span-4">
    <div class="sticky top-24 rounded-3xl border border-slate-100 bg-white p-6 shadow-lg sm:p-7">
        <h2 class="mb-5 border-b border-slate-100 pb-4 text-lg font-bold text-teal-950">Reservation Summary</h2>
        <div class="space-y-4">
            @forelse (($reservation['items'] ?? []) as $item)
                <article class="rounded-2xl border border-slate-100 p-4">
                    <h3 class="font-bold text-teal-950">{{ $item['room_name'] }}</h3>
                    <p class="text-xs text-slate-500">{{ $item['rate_name'] ?? 'Room rate' }} · Qty {{ $item['quantity'] }}</p>
                    <p class="mt-2 text-sm font-semibold text-teal-800">PHP {{ number_format($item['line_total'], 2) }}</p>
                </article>
            @empty
                <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No item summary is available.</p>
            @endforelse
        </div>
        <dl class="mt-6 space-y-3 border-t border-slate-100 pt-5 text-sm">
            <div class="flex justify-between text-slate-600"><dt>Nightly subtotal</dt><dd>PHP {{ number_format($reservation['subtotal'] ?? 0, 2) }}</dd></div>
            <div class="flex justify-between text-slate-600"><dt>Nights</dt><dd>{{ $reservation['nights'] ?? 1 }}</dd></div>
            <div class="flex justify-between border-t border-slate-100 pt-3 text-base font-bold text-teal-950"><dt>Total</dt><dd>PHP {{ number_format($reservation['total'] ?? 0, 2) }}</dd></div>
        </dl>
    </div>
</aside>
