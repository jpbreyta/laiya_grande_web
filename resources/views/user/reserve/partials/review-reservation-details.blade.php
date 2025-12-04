@php
    $reservation = session('reservation_data', []);
    $checkIn = isset($reservation['check_in']) ? \Carbon\Carbon::parse($reservation['check_in']) : null;
    $checkOut = isset($reservation['check_out']) ? \Carbon\Carbon::parse($reservation['check_out']) : null;
    $nights = $checkIn && $checkOut ? $checkIn->diffInDays($checkOut) : 1;
@endphp

<div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-100">
    <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
        <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                <i class="fas fa-calendar-alt"></i>
            </span>
            Reservation Details
        </h5>
    </div>
    <div class="p-8">
        <div class="grid md:grid-cols-3 gap-6">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase">Check-in Date</p>
                <p class="text-lg font-semibold text-teal-900">
                    {{ $checkIn ? $checkIn->format('M d, Y') : 'N/A' }}
                </p>
                <p class="text-xs text-slate-500">{{ $checkIn ? $checkIn->format('l') : '' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase">Check-out Date</p>
                <p class="text-lg font-semibold text-teal-900">
                    {{ $checkOut ? $checkOut->format('M d, Y') : 'N/A' }}
                </p>
                <p class="text-xs text-slate-500">{{ $checkOut ? $checkOut->format('l') : '' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase">Duration</p>
                <p class="text-lg font-semibold text-teal-900">
                    {{ $nights }} {{ Str::plural('Night', $nights) }}
                </p>
                <p class="text-xs text-slate-500">{{ $nights + 1 }} {{ Str::plural('Day', $nights + 1) }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-slate-200">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-500 uppercase">Number of Guests</p>
                    <p class="text-lg font-semibold text-teal-900 flex items-center gap-2">
                        <i class="fas fa-users text-teal-600"></i>
                        {{ $reservation['guests'] ?? 'N/A' }} {{ Str::plural('Guest', $reservation['guests'] ?? 1) }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500">Check-in Time</p>
                    <p class="text-sm font-semibold text-teal-900">2:00 PM</p>
                    <p class="text-xs text-slate-500 mt-2">Check-out Time</p>
                    <p class="text-sm font-semibold text-teal-900">12:00 NN</p>
                </div>
            </div>
        </div>
    </div>
</div>
