<div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-100">
    <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
        <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                <i class="fas fa-calendar-alt"></i>
            </span>
            Reservation Details
        </h5>
    </div>
    <div class="p-8 space-y-6">
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-xl mb-4">
            <p class="text-sm text-yellow-800 font-semibold">
                <i class="fas fa-clock"></i> 24-Hour Deadline: Complete payment within 24 hours to confirm your
                reservation.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Check-in Date *</label>
                <input type="date" id="check_in" name="check_in" required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                    value="{{ old('check_in') }}">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Check-out Date *</label>
                <input type="date" id="check_out" name="check_out" required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                    value="{{ old('check_out') }}">
            </div>
            <input type="hidden" name="days_count" id="days_count_hidden" value="1">
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Number of Guests *</label>
                <input type="number" name="guests" min="1" value="{{ old('guests', 2) }}" required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                    id="guestsInput">
                @php
                    $cart = session('cart', []);
                    $totalCapacity = 0;
                    foreach ($cart as $item) {
                        $room = \App\Models\Room::find($item['room_id']);
                        if ($room) {
                            $totalCapacity += $room->capacity * $item['quantity'];
                        }
                    }
                @endphp
                <small class="text-slate-500 text-xs mt-2 block flex items-center gap-1" id="capacityHint">
                    @if ($totalCapacity > 0)
                        <i class="fas fa-info-circle"></i> Max capacity: <strong>{{ $totalCapacity }} guests</strong>
                    @endif
                </small>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Special Requests</label>
                <input type="text" name="special_requests" placeholder="e.g., Ground floor"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                    value="{{ old('special_requests') }}">
            </div>
        </div>
    </div>
</div>
