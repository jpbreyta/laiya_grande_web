@extends('user.layouts.app')

@section('content')
    <section class="relative isolate bg-center min-h-[80svh] md:min-h-[85svh] overflow-hidden">
        <div x-data="{
            images: [
                '{{ asset('images/bg3.jpg') }}',
                '{{ asset('images/poster.jpg') }}'
            ],
            current: 0,
            next() {
                this.current = (this.current + 1) % this.images.length
            }
        }" x-init="setInterval(() => next(), 5000)" class="relative overflow-hidden">

            <div class="absolute inset-0 -z-10">
                <template x-for="(img, index) in images" :key="index">
                    <img :src="img"
                        class="h-full w-full object-cover absolute inset-0 transition-opacity duration-1000"
                        :class="current === index ? 'opacity-100' : 'opacity-0'">
                </template>
            </div>

            <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-teal-900/50"></div>

            <div class="absolute inset-0 -z-5">
                <div class="absolute top-20 left-10 w-4 h-4 bg-yellow-400/30 rounded-full animate-pulse"></div>
                <div class="absolute top-40 right-20 w-6 h-6 bg-teal-400/20 rounded-full animate-bounce"
                    style="animation-delay: 1s;"></div>
                <div class="absolute bottom-40 left-20 w-3 h-3 bg-cyan-400/40 rounded-full animate-pulse"
                    style="animation-delay: 2s;"></div>
                <div class="absolute bottom-20 right-10 w-5 h-5 bg-yellow-300/25 rounded-full animate-bounce"
                    style="animation-delay: 3s;"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-6 py-24 flex min-h-[80svh] items-center justify-center">
                <div class="text-center text-white max-w-4xl">
                    <div
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mb-6 animate-fade-in">
                        <span class="text-sm font-medium">Luxury Beach Resort</span>
                    </div>

                    <h1
                        class="text-5xl md:text-7xl font-extrabold tracking-tight font-heading mb-6 animate-slide-up leading-[1.15]">
                        <span class="block">Welcome to</span>
                        <span class="block relative">
                            <span
                                class="bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 bg-clip-text text-transparent pb-1">
                                {{ $settings->resort_name ?? 'Laiya Grande Beach Resort' }}
                            </span>
                            <span class="absolute inset-0 pointer-events-none" aria-hidden="true"></span>
                        </span>
                    </h1>

                    <p class="text-xl md:text-2xl leading-relaxed text-white/90 mb-8 animate-fade-in"
                        style="animation-delay: 0.3s;">
                        {{ $settings->tagline ?? 'Your tropical escape in San Juan, Batangas' }}
                    </p>

                    <p class="text-lg text-white/80 mb-10 max-w-2xl mx-auto animate-fade-in" style="animation-delay: 0.6s;">
                        {{ $settings->description ?? 'Experience paradise with pristine beaches, luxury accommodations, and world-class amenities in the heart of Batangas.' }}
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in"
                        style="animation-delay: 0.9s;">
                        <a aria-label="Book a room" href="{{ route('user.rooms.index') }}"
                            class="group inline-flex items-center gap-3 rounded-full bg-yellow-400 px-8 py-4 font-semibold text-black shadow-xl shadow-black/30 ring-1 ring-white/20 hover:bg-yellow-300 hover:shadow-2xl hover:shadow-black/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2 focus-visible:ring-offset-black motion-safe:transition-all duration-300 transform hover:scale-105">
                            <span>Book Now</span>
                        </a>

                        <a aria-label="Explore our services" href="#services"
                            class="inline-block rounded-full bg-white/10 border border-white/30 backdrop-blur-md px-6 md:px-8 py-3 md:py-4 text-base md:text-lg font-semibold text-white shadow-sm hover:bg-white/20 hover:border-white/50 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-black transition-all duration-300 ease-out font-[Poppins] tracking-wide">
                            Explore Services
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16 animate-fade-in" style="animation-delay: 1.2s;">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-400 mb-2">10+</div>
                            <div class="text-sm text-white/80">Luxury Rooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-400 mb-2">5★</div>
                            <div class="text-sm text-white/80">Premium Service</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-yellow-400 mb-2">24/7</div>
                            <div class="text-sm text-white/80">Concierge</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
                <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center">
                    <div class="w-1 h-3 bg-white/50 rounded-full mt-2 animate-pulse"></div>
                </div>
            </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 bg-teal-100 rounded-full px-4 py-2 mb-6">
                        <div class="w-2 h-2 bg-teal-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-teal-700">Premium Experience</span>
                    </div>
                    <h2 class="text-4xl font-bold text-teal-600 font-heading mb-6">Experience Paradise</h2>
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-teal-500 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-lg text-gray-600">Relax on the sandy beaches of Laiya, enjoy crystal-clear
                                waters, and feel the tropical vibes.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-teal-500 rounded-full mt-2 flex-shrink-0"></div>
                            <p class="text-lg text-gray-600">{{ $settings->resort_name ?? 'Laiya Grande' }} offers comfort, luxury, and an unforgettable stay
                                with world-class amenities and personalized service.</p>
                        </li>
                    </ul>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">5-Star Service</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-teal-400 rounded-full"></div>
                            <span class="text-sm font-medium text-gray-700">Beachfront Location</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-teal-100 to-cyan-100 rounded-2xl p-8 text-center">
                    
                    <img src="{{ !empty($settings->logo_path) ? asset('storage/'.$settings->logo_path) : asset('images/laiyagrande-logo.png') }}" 
                         alt="{{ $settings->resort_name ?? 'Resort' }} Logo"
                         class="h-56 w-auto mx-auto mb-8 object-contain">
                         
                    <h3 class="text-2xl font-bold text-teal-700 mb-4">Tropical Paradise Awaits</h3>
                    <p class="text-teal-600">Your perfect getaway awaits with pristine beaches and luxury accommodations.
                    </p>
                </div>
            </div>
        </div>
    </section>



    @include('user.home.partials.rooms') 
    @include('user.home.partials.promos')
    <section class="py-16 bg-gradient-to-br from-teal-600 to-teal-700 text-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold tracking-tight font-heading mb-4">Ready to Book Paradise?</h2>
            
            <p class="text-lg text-white/90 mb-8">Book your stay at {{ $settings->resort_name ?? 'Laiya Grande' }} and experience the beauty of San Juan,
                Batangas</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/register') }}"
                    class="inline-block rounded-full bg-yellow-400 px-8 py-4 font-semibold text-black shadow-lg shadow-black/20 ring-1 ring-white/10 hover:bg-yellow-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-300 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-700 motion-safe:transition">
                    Book Your Stay
                </a>
                <a href="{{ url('/contact') }}"
                    class="inline-block rounded-full border-2 border-white/30 bg-white/10 backdrop-blur-sm px-8 py-4 font-semibold text-white hover:bg-white/20 hover:border-white/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-700 motion-safe:transition">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
@endsection