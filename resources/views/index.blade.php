@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative bg-cover bg-center h-[80vh]" style="background-image: url('{{ asset('images/laiyagrande.png') }}');">
    <div class="absolute inset-0 bg-opacity-50 flex items-center justify-center">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold">Welcome to Laiya Grande</h1>
            <p class="mt-4 text-lg md:text-xl">Your tropical escape in San Juan, Batangas 🌴</p>
            <a href="{{ url('/rooms') }}" class="mt-6 inline-block px-6 py-3 bg-yellow-400 text-black font-semibold rounded-lg shadow-lg hover:bg-yellow-300 transition">
                Book Now
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-teal-600">Experience Paradise</h2>
        <p class="mt-4 text-gray-600">
            Relax on the sandy beaches of Laiya, enjoy crystal-clear waters, and feel the tropical vibes.  
            Laiya Grande offers comfort, luxury, and an unforgettable stay.
        </p>
    </div>
</section>

<!-- Rooms Section -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold text-teal-600">Our Rooms</h2>
        <p class="mt-2 text-gray-600">Choose from our cozy and spacious accommodations.</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
            <div class="bg-gray-100 rounded-lg shadow hover:shadow-lg transition p-4">
                <img src="{{ asset('images/room1.jpg') }}" alt="Room 1" class="rounded-lg">
                <h3 class="mt-4 font-semibold text-lg">Beachfront Cottage</h3>
                <p class="text-gray-600 text-sm">Wake up to the sound of waves 🌊</p>
            </div>
            <div class="bg-gray-100 rounded-lg shadow hover:shadow-lg transition p-4">
                <img src="{{ asset('images/room2.jpg') }}" alt="Room 2" class="rounded-lg">
                <h3 class="mt-4 font-semibold text-lg">Deluxe Room</h3>
                <p class="text-gray-600 text-sm">Perfect for couples or families 🏝️</p>
            </div>
            <div class="bg-gray-100 rounded-lg shadow hover:shadow-lg transition p-4">
                <img src="{{ asset('images/room3.jpg') }}" alt="Room 3" class="rounded-lg">
                <h3 class="mt-4 font-semibold text-lg">Garden Villa</h3>
                <p class="text-gray-600 text-sm">Surrounded by lush tropical gardens 🌺</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-teal-600 text-white">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <h2 class="text-3xl font-bold">Get in Touch</h2>
        <p class="mt-2">Have questions? Ready to book? Contact us today!</p>
        <p class="mt-4">📞 0963 033 7629 | 📧 laiyagrandebr22@gmail.com</p>
        <a href="{{ url('/contact') }}" class="mt-6 inline-block px-6 py-3 bg-yellow-400 text-black font-semibold rounded-lg shadow hover:bg-yellow-300 transition">
            Contact Us
        </a>
    </div>
</section>
@endsection
