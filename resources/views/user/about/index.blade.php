@extends('user.layouts.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600&display=swap');

        .font-heading {
            font-family: 'Playfair Display', serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <div class="bg-slate-50 min-h-screen font-body">
        <!-- Hero Section -->
        <section class="relative bg-teal-900 py-20 isolate overflow-hidden">
            <div class="absolute inset-0 -z-10">
                <img src="{{ asset('images/bg3.jpg') }}" alt="Laiya Grande Resort"
                    class="h-full w-full object-cover opacity-30">
                <div class="absolute inset-0 bg-gradient-to-t from-teal-900 via-teal-900/60 to-transparent"></div>
            </div>

            <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center relative z-10">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-xs font-medium tracking-widest text-white uppercase mb-4">
                    Our Story
                </span>
                <h1 class="text-4xl sm:text-5xl font-heading font-bold text-white tracking-tight mb-4">
                    About {{ $settings->resort_name ?? 'Laiya Grande' }}
                </h1>
                <p class="text-teal-100 text-lg max-w-2xl mx-auto font-light leading-relaxed">
                    {{ $settings->tagline ?? 'Your tropical escape in San Juan, Batangas' }}
                </p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
                    <div>
                        <h2 class="text-3xl font-heading font-bold text-slate-900 mb-6">Welcome to Paradise</h2>
                        <div class="space-y-4 text-slate-600 leading-relaxed">
                            <p>
                                {{ $settings->resort_name ?? 'Laiya Grande Beach Resort' }} is a premier beachfront
                                destination nestled in the pristine shores of San Juan, Batangas.
                                We offer an unforgettable tropical getaway where luxury meets nature.
                            </p>
                            <p>
                                Our resort features world-class accommodations, stunning ocean views, and a wide range of
                                amenities designed to make your stay comfortable and memorable.
                                Whether you're looking for a romantic escape, a family vacation, or a corporate retreat, we
                                have everything you need.
                            </p>
                            <p>
                                With crystal-clear waters, white sandy beaches, and breathtaking sunsets,
                                {{ $settings->resort_name ?? 'Laiya Grande' }}
                                is the perfect place to relax, unwind, and create lasting memories.
                            </p>
                        </div>
                    </div>
                    <div class="relative">
                        <img src="{{ asset('images/poster.jpg') }}" alt="Resort View"
                            class="rounded-2xl shadow-2xl w-full h-auto">
                        <div class="absolute -bottom-6 -left-6 bg-teal-600 text-white p-6 rounded-xl shadow-xl max-w-xs">
                            <p class="text-4xl font-bold mb-1">10+</p>
                            <p class="text-sm opacity-90">Years of Excellence</p>
                        </div>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div class="bg-slate-50 p-8 rounded-2xl text-center hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-umbrella-beach text-teal-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-heading font-bold text-slate-900 mb-3">Beachfront Location</h3>
                        <p class="text-slate-600">Direct access to pristine white sand beaches and crystal-clear waters</p>
                    </div>

                    <div class="bg-slate-50 p-8 rounded-2xl text-center hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bed text-teal-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-heading font-bold text-slate-900 mb-3">Luxury Rooms</h3>
                        <p class="text-slate-600">Comfortable and spacious accommodations with modern amenities</p>
                    </div>

                    <div class="bg-slate-50 p-8 rounded-2xl text-center hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-concierge-bell text-teal-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-heading font-bold text-slate-900 mb-3">24/7 Service</h3>
                        <p class="text-slate-600">Dedicated staff ready to assist you anytime during your stay</p>
                    </div>
                </div>

                <!-- Mission & Vision -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                    <div class="bg-gradient-to-br from-teal-600 to-teal-700 text-white p-8 rounded-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fas fa-bullseye text-3xl"></i>
                            <h3 class="text-2xl font-heading font-bold">Our Mission</h3>
                        </div>
                        <p class="leading-relaxed opacity-90">
                            To provide exceptional hospitality and create unforgettable experiences for our guests through
                            world-class service, pristine facilities, and a commitment to excellence in everything we do.
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-slate-700 to-slate-800 text-white p-8 rounded-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <i class="fas fa-eye text-3xl"></i>
                            <h3 class="text-2xl font-heading font-bold">Our Vision</h3>
                        </div>
                        <p class="leading-relaxed opacity-90">
                            To be the premier beach resort destination in Batangas, recognized for our outstanding service,
                            sustainable practices, and dedication to creating memorable moments for every guest.
                        </p>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-heading font-bold text-slate-900 mb-4">Resort Amenities</h2>
                    <p class="text-slate-600 max-w-2xl mx-auto">
                        Everything you need for a perfect beach vacation
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-4">
                        <i class="fas fa-swimming-pool text-teal-600 text-3xl mb-3"></i>
                        <p class="text-sm font-medium text-slate-700">Swimming Pool</p>
                    </div>
                    <div class="text-center p-4">
                        <i class="fas fa-wifi text-teal-600 text-3xl mb-3"></i>
                        <p class="text-sm font-medium text-slate-700">Free WiFi</p>
                    </div>
                    <div class="text-center p-4">
                        <i class="fas fa-utensils text-teal-600 text-3xl mb-3"></i>
                        <p class="text-sm font-medium text-slate-700">Restaurant</p>
                    </div>
                    <div class="text-center p-4">
                        <i class="fas fa-parking text-teal-600 text-3xl mb-3"></i>
                        <p class="text-sm font-medium text-slate-700">Free Parking</p>
                    </div>
                    <div class="text-center p-4">
                        <i class="fas fa-water text-teal-600 text-3xl mb-3"></i>
                        <p class="text-sm font-medium text-slate-700">Water Sports</p>
                    </div>
                    <div class="text-center p-4">
                        <i class="fas fa-spa text-teal-600 text-3xl mb-3"></i>
                        <p class="text-sm font-medium text-slate-700">Spa Services</p>
                    </div>
                    <div class="text-center p-4">
                        <i class="fas fa-dumbbell text-teal-600 text-3xl mb-3"></i>
                        <p class="text-sm font-medium text-slate-700">Fitness Center</p>
                    </div>
                    <div class="text-center p-4">
                        <i class="fas fa-shield-alt text-teal-600 text-3xl mb-3"></i>
                        <p class="text-sm font-medium text-slate-700">24/7 Security</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-gradient-to-br from-teal-600 to-teal-700 text-white">
            <div class="max-w-7xl mx-auto px-6 text-center">
                <h2 class="text-3xl md:text-4xl font-heading font-bold tracking-tight mb-4">Ready to Experience Paradise?
                </h2>
                <p class="text-lg text-teal-100 mb-8 max-w-2xl mx-auto">
                    Book your stay today and discover why {{ $settings->resort_name ?? 'Laiya Grande' }} is the perfect
                    tropical getaway.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ url('/rooms') }}"
                        class="inline-block rounded-full bg-yellow-400 px-8 py-4 font-semibold text-black shadow-lg hover:bg-yellow-300 transition-colors">
                        View Our Rooms
                    </a>
                    <a href="{{ route('contact') }}"
                        class="inline-block rounded-full border-2 border-white/30 bg-white/10 backdrop-blur-sm px-8 py-4 font-semibold text-white hover:bg-white/20 transition-colors">
                        Contact Us
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
