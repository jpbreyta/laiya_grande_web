@php($reservation = $reservation ?? session('reservation_data', []))
<section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg sm:p-8">
    <h2 class="mb-5 text-xl font-bold text-teal-950">Payment Information</h2>
    <dl class="grid gap-5 sm:grid-cols-2">
        <div><dt class="text-xs font-bold uppercase text-slate-500">Method</dt><dd class="mt-1 text-lg capitalize text-teal-950">{{ str_replace('_', ' ', $reservation['payment_method'] ?? 'N/A') }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Status</dt><dd class="mt-1"><span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-800">Pending verification</span></dd></div>
    </dl>
</section>
