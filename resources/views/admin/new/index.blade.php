@extends('admin.layouts.app')

@section('content')

<section class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <div class="relative rounded-xl overflow-hidden shadow-lg mb-4 h-32">
            <div class="absolute inset-0 bg-[#2C5F5F]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-[#5f9ea0] to-[#99e6b3]"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-4">
                <div class="inline-block bg-white/10 backdrop-blur-md px-4 py-2 rounded-lg border border-white/20 shadow">
                    <h1 class="text-xl md:text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-white to-white tracking-tight">Create Walk-in</h1>
                </div>
                <p class="text-white/90 mt-1 text-sm">Quickly add an on-site reservation or booking.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.new.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg border border-green-100">
                            <label class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1 block">Type</label>
                            <select id="typeSelect" name="type" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="reservation" {{ old('type') === 'reservation' ? 'selected' : '' }}>Reservation</option>
                                <option value="booking" {{ old('type') === 'booking' ? 'selected' : '' }}>Booking</option>
                            </select>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1 block">Status</label>
                            <select id="statusSelect" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Paid is for reservations only.</p>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                            <label class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1 block">Total Price</label>
                            <input type="number" step="0.01" min="0" name="total_price" value="{{ old('total_price') }}" class="w-full px-3 py-2 border border-green-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm font-semibold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                <label class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1 block">Guest Name</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="text" name="firstname" value="{{ old('firstname') }}" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="First Name">
                                    <input type="text" name="lastname" value="{{ old('lastname') }}" class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Last Name">
                                </div>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1 block">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="guest@example.com">
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1 block">Phone Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="09XXXXXXXXX">
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1 block">Number of Guests</label>
                                <input type="number" min="1" name="number_of_guests" value="{{ old('number_of_guests') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
                                <label class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1 block">Room</label>
                                <select name="room_id" class="w-full px-3 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                                    <option value="">Select Room</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1 block">Check-in Date</label>
                                <input type="date" name="check_in" value="{{ old('check_in') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1 block">Check-out Date</label>
                                <input type="date" name="check_out" value="{{ old('check_out') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>

                            <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                                <label class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2 block">Special Request</label>
                                <textarea name="special_request" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Optional notes or preferences">{{ old('special_request') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">Cancel</a>
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">Create Walk-in</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    (function() {
        const typeSelect = document.getElementById('typeSelect');
        const statusSelect = document.getElementById('statusSelect');

        function syncStatusOptions() {
            const isBooking = typeSelect.value === 'booking';
            // Ensure Paid is hidden/removed for bookings
            Array.from(statusSelect.options).forEach(opt => {
                if (opt.value === 'paid') {
                    opt.disabled = isBooking;
                    opt.hidden = isBooking;
                    if (isBooking && statusSelect.value === 'paid') {
                        statusSelect.value = 'pending';
                    }
                }
            });
        }

        if (typeSelect && statusSelect) {
            typeSelect.addEventListener('change', syncStatusOptions);
            syncStatusOptions();
        }
    })();
</script>

@endsection