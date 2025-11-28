@extends('admin.layouts.app')

@php
    $pageTitle = 'Edit Booking';
@endphp

@section('content')
    <section class="bg-gradient-to-br from-slate-50 via-white to-slate-100 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Reuse Hero Header logic from Show Blade for consistency -->
            @php
                $room = $booking->room;
                $roomImages = $room ? (is_array($room->images) ? $room->images : json_decode($room->images, true)) : [];
                $mainImage = $room->image ?? null;
                $bgImage = $mainImage ? asset('storage/'.$mainImage) : null;
            @endphp

            <div class="relative h-64 md:h-80 rounded-3xl overflow-hidden shadow-2xl mb-8 group">
                @if($bgImage)
                    <img src="{{ $bgImage }}" class="absolute inset-0 w-full h-full object-cover" alt="Room Image">
                @else
                    <div class="absolute inset-0 bg-gradient-to-r from-teal-500 to-cyan-600"></div>
                @endif
                <div class="absolute inset-0 bg-black/50"></div>
                
                <div class="absolute bottom-0 left-0 p-8 z-10 text-white">
                    <h1 class="text-3xl font-extrabold mb-1">Editing Booking #{{ $booking->reservation_number }}</h1>
                    <p class="opacity-90">{{ $booking->room->name ?? 'Room Unassigned' }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                            
                            <!-- Guest Information Column -->
                            <div class="space-y-5">
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest border-b pb-2">Guest Information</h3>
                                
                                <div class="p-4 bg-teal-50/50 rounded-xl border border-teal-100">
                                    <label class="block text-xs font-bold text-teal-700 uppercase mb-2">Full Name</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input type="text" name="firstname" value="{{ old('firstname', $booking->customer->firstname) }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 text-sm" placeholder="First Name">
                                        </div>
                                        <div>
                                            <input type="text" name="lastname" value="{{ old('lastname', $booking->customer->lastname) }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 text-sm" placeholder="Last Name">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $booking->customer->email) }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Phone Number</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $booking->customer->phone_number) }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Guest Count</label>
                                    <input type="number" name="number_of_guests" value="{{ old('number_of_guests', $booking->number_of_guests) }}" min="1" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                                </div>
                            </div>

                            <!-- Booking Details Column -->
                            <div class="space-y-5">
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest border-b pb-2">Reservation Details</h3>

                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Room Assignment</label>
                                    <select name="room_id" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                                        @foreach($rooms as $room)
                                            <option value="{{ $room->id }}" {{ $booking->room_id == $room->id ? 'selected' : '' }}>
                                                {{ $room->name }} (Cap: {{ $room->capacity }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Check In</label>
                                        <input type="date" name="check_in" value="{{ $booking->check_in->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Check Out</label>
                                        <input type="date" name="check_out" value="{{ $booking->check_out->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Total Price (₱)</label>
                                        <input type="number" step="0.01" name="total_price" value="{{ $booking->total_price }}" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500 font-bold text-emerald-600">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status</label>
                                        <select name="status" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">
                                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="rejected" {{ $booking->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Special Request</label>
                                    <textarea name="special_request" rows="2" class="w-full rounded-lg border-gray-300 focus:ring-teal-500 focus:border-teal-500">{{ $booking->special_request }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Action Bar -->
                        <div class="flex items-center justify-between pt-6 border-t border-slate-100 mt-6">
                            <a href="{{ route('admin.booking.show', $booking->id) }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm flex items-center gap-2">
                                <i class="fas fa-arrow-left"></i> Cancel & Return
                            </a>
                            <button type="submit" class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition transform">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection