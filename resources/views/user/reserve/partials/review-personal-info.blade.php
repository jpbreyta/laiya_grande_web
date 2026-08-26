@php($reservation = $reservation ?? session('reservation_data', []))
<section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg sm:p-8">
    <h2 class="mb-5 text-xl font-bold text-teal-950">Personal Information</h2>
    <dl class="grid gap-5 sm:grid-cols-2">
        <div><dt class="text-xs font-bold uppercase text-slate-500">Full name</dt><dd class="mt-1 text-lg text-teal-950">{{ $reservation['first_name'] ?? 'N/A' }} {{ $reservation['last_name'] ?? '' }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Email</dt><dd class="mt-1 break-all text-lg text-teal-950">{{ $reservation['email'] ?? 'N/A' }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Phone</dt><dd class="mt-1 text-lg text-teal-950">{{ $reservation['phone'] ?? 'N/A' }}</dd></div>
        <div><dt class="text-xs font-bold uppercase text-slate-500">Guests</dt><dd class="mt-1 text-lg text-teal-950">{{ $reservation['guests'] ?? 'N/A' }}</dd></div>
    </dl>
    @if (!empty($reservation['special_request']))
        <div class="mt-5 rounded-2xl bg-amber-50 p-4 text-sm text-amber-900"><strong>Special request:</strong> {{ $reservation['special_request'] }}</div>
    @endif
</section>
