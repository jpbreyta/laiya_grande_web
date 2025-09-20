@extends('layouts.app')

@section('content')
    <!-- Hero Section with Laiya Grande Background -->
    <section class="relative bg-cover bg-center h-[80vh]" 
             style="background-image: url('{{ asset('images/laiyagrande.png') }}');">
        <div class="absolute inset-0 bg-opacity-30"></div> <!-- overlay -->
        <div class="relative z-10 flex flex-col justify-center items-center h-full text-white text-center px-4">
            <h1 class="text-5xl font-bold">Reserve Your Stay at Laiya Grande</h1>
            <p class="mt-4 text-xl">Relax, unwind, and enjoy the beach life</p>
        </div>
    </section>

    <!-- Reservation Form -->
    <div class="max-w-4xl mx-auto my-10 px-4">
        <h2 class="text-3xl font-semibold mb-6">Reserve Now</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('guest.reserve') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Guest Name & Contact Number -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium mb-1">Guest Name</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name') }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('guest_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block font-medium mb-1">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('contact_number')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Date of Arrival & Total Guests -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium mb-1">Date of Arrival</label>
                    <input type="date" name="arrival_date" value="{{ old('arrival_date') }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('arrival_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block font-medium mb-1">Total of Guests</label>
                    <input type="number" name="total_guests" value="{{ old('total_guests') }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('total_guests')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Room Names -->
            <div>
                <label class="block font-medium mb-1">Room Names</label>
                <input type="text" name="room_names" value="{{ old('room_names') }}" 
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('room_names')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Car Plate & Payment Mode -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-medium mb-1">Car Type / Plate #</label>
                    <input type="text" name="car_plate" value="{{ old('car_plate') }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-medium mb-1">Balance Mode of Payment</label>
                    <select name="balance_mode" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Cash">Cash</option>
                        <option value="GCash">GCash</option>
                        <option value="BDO">BDO</option>
                    </select>
                </div>
            </div>

            <!-- Key Deposit -->
            <div>
                <label class="block font-medium mb-1">Key Deposit</label>
                <input type="number" name="key_deposit" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Checkboxes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="eco_ticket" class="form-checkbox h-5 w-5 text-blue-600">
                    <span>Eco Ticket</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="parking" class="form-checkbox h-5 w-5 text-blue-600">
                    <span>Parking</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="cookwares" class="form-checkbox h-5 w-5 text-blue-600">
                    <span>Cookwares</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="videoke" class="form-checkbox h-5 w-5 text-blue-600">
                    <span>Videoke</span>
                </label>
            </div>

            <!-- Others / Special Requests -->
            <div>
                <label class="block font-medium mb-1">Others / Special Requests</label>
                <textarea name="others" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('others') }}</textarea>
            </div>

            <!-- Total Amount -->
            <div>
                <label class="block font-medium mb-1">Total Amount</label>
                <input type="number" step="0.01" name="total_amount" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Reserve Now</button>
        </form>
    </div>
@endsection
