@php
    $reservation = $reservation ?? session('reservation_data', []);
    $checkIn = !empty($reservation['check_in']) ? \Carbon\Carbon::parse($reservation['check_in']) : null;
    $checkOut = !empty($reservation['check_out']) ? \Carbon\Carbon::parse($reservation['check_out']) : null;
@endphp
<section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg sm:p-8">
    <h2 class="mb-5 text-xl font-bold text-teal-950">Stay Details</h2>
    <dl class="grid gap-5 sm:grid-cols-3">
        <div><dt class="text-xs font-bold uppercase text-slate-500">Check-in</dt><dd class="mt-1 text-lg text-teal-950">{{ $checkIn?->format('M d, Y') ?? 'N/A' }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Check-out</dt><dd class="mt-1 text-lg text-teal-950">{{ $checkOut?->format('M d, Y') ?? 'N/A' }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Duration</dt><dd class="mt-1 text-lg text-teal-950">{{ $reservation['nights'] ?? 1 }} night(s)</dd></div>
    </dl>
</section>
