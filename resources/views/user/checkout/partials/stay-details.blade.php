@php($datesEditable = $datesEditable ?? false)
<section class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg shadow-teal-900/5">
    <header class="border-b border-slate-100 bg-teal-50/40 px-6 py-5 sm:px-8">
        <h2 class="flex items-center gap-3 text-xl font-bold text-teal-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-100 text-teal-700">
                <i class="fas fa-calendar-days" aria-hidden="true"></i>
            </span>
            Stay Details
        </h2>
    </header>

    <div class="space-y-6 p-6 sm:p-8">
        @if ($datesEditable)
            <div class="grid gap-5 sm:grid-cols-2">
                <label class="block">
                    <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Check-in</span>
                    <input type="date" name="check_in" value="{{ old('check_in', $checkIn) }}" min="{{ today()->toDateString() }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200" data-check-in>
                </label>
                <label class="block">
                    <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Check-out</span>
                    <input type="date" name="check_out" value="{{ old('check_out', $checkOut) }}" min="{{ today()->addDay()->toDateString() }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200" data-check-out>
                </label>
            </div>
        @else
            <div class="grid gap-4 rounded-2xl border border-teal-100 bg-teal-50 p-5 sm:grid-cols-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-teal-600">Check-in</p>
                    <p class="mt-1 font-semibold text-teal-950">{{ \Carbon\Carbon::parse($checkIn)->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-teal-600">Check-out</p>
                    <p class="mt-1 font-semibold text-teal-950">{{ \Carbon\Carbon::parse($checkOut)->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-teal-600">Duration</p>
                    <p class="mt-1 font-semibold text-teal-950"><span data-nights>{{ $nights }}</span> night(s)</p>
                </div>
            </div>
            <input type="hidden" name="check_in" value="{{ $checkIn }}">
            <input type="hidden" name="check_out" value="{{ $checkOut }}">
        @endif

        <div class="grid gap-5 sm:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Number of guests</span>
                <input type="number" name="guests" value="{{ old('guests', 1) }}" min="1" max="{{ ($totalCapacity ?? 0) > 0 ? $totalCapacity : 100 }}" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                @if (($totalCapacity ?? 0) > 0)
                    <span class="mt-2 block text-xs text-slate-500">Selected rooms can accommodate up to {{ $totalCapacity }} guests.</span>
                @endif
            </label>

            <label class="block">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Special request</span>
                <textarea name="special_request" rows="3" maxlength="2000"
                    class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200"
                    placeholder="Optional requests for your stay">{{ old('special_request', old('special_requests')) }}</textarea>
            </label>
        </div>
    </div>
</section>
