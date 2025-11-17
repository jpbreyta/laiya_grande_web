@extends('user.layouts.app')

@section('content')
<section class="relative isolate bg-center min-h-[80svh] md:min-h-[85svh] overflow-hidden">
    <!-- Background Image with Parallax Effect -->
    <div class="absolute inset-0 -z-10">
        <img src="{{ asset('images/laiyagrande.png') }}" alt="" aria-hidden="true" class="h-full w-full object-cover transform scale-105 hover:scale-110 transition-transform duration-[10s] ease-out">
    </div>
    
    <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-teal-900/50"></div>
    
    <div class="absolute inset-0 -z-5">
        <div class="absolute top-20 left-10 w-4 h-4 bg-yellow-400/30 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-6 h-6 bg-teal-400/20 rounded-full animate-bounce" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-40 left-20 w-3 h-3 bg-cyan-400/40 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 right-10 w-5 h-5 bg-yellow-300/25 rounded-full animate-bounce" style="animation-delay: 3s;"></div>
    </div>
    
    <div class="relative mx-auto max-w-7xl px-6 py-24 flex min-h-[80svh] items-center justify-center">
        <div class="text-center text-white max-w-4xl">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mb-6 animate-fade-in">
                <span class="text-sm font-medium">Luxury Beach Resort</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-balance font-heading mb-6 animate-slide-up">
                <span class="block">Welcome to</span>
                <span class="block bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 bg-clip-text text-transparent">
                    Laiya Grande Resort
                </span>
            </h1>
            
            <p class="text-xl md:text-2xl leading-relaxed text-white/90 mb-8 animate-fade-in" style="animation-delay: 0.3s;">
                Your tropical escape in San Juan, Batangas
            </p>
            
            <p class="text-lg text-white/80 mb-10 max-w-2xl mx-auto animate-fade-in" style="animation-delay: 0.6s;">
                Experience paradise with pristine beaches, luxury accommodations, and world-class amenities in the heart of Batangas.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in" style="animation-delay: 0.9s;">
                <a aria-label="Book a room at Laiya Grande" href="{{ route('user.rooms.index') }}" 
                   class="group inline-flex items-center gap-3 rounded-full bg-yellow-400 px-8 py-4 font-semibold text-black shadow-xl shadow-black/30 ring-1 ring-white/20 hover:bg-yellow-300 hover:shadow-2xl hover:shadow-black/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-400 focus-visible:ring-offset-2 focus-visible:ring-offset-black motion-safe:transition-all duration-300 transform hover:scale-105">
                    <span>Book Now</span>
                    <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
                
                <a aria-label="Explore our services" href="#services" 
                   class="group inline-flex items-center gap-3 rounded-full border-2 border-white/30 bg-white/10 backdrop-blur-sm px-8 py-4 font-semibold text-white hover:bg-white/20 hover:border-white/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-black motion-safe:transition-all duration-300">
                    <span>Explore Services</span>
                    <span class="group-hover:translate-x-1 transition-transform">↓</span>
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
                        <p class="text-lg text-gray-600">Relax on the sandy beaches of Laiya, enjoy crystal-clear waters, and feel the tropical vibes.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full mt-2 flex-shrink-0"></div>
                        <p class="text-lg text-gray-600">Laiya Grande offers comfort, luxury, and an unforgettable stay with world-class amenities and personalized service.</p>
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
                <img src="{{ asset('images/laiyagrande-logo.png') }}" alt="Laiya Grande Beach Resort" class="h-56 w-auto mx-auto mb-8">
                <h3 class="text-2xl font-bold text-teal-700 mb-4">Tropical Paradise Awaits</h3>
                <p class="text-teal-600">Your perfect getaway awaits with pristine beaches and luxury accommodations.</p>
            </div>
        </div>
    </div>
</section>

<section id="services" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-teal-600 font-heading mb-4">Our Services</h2>
        <p class="text-lg text-gray-600 mb-12">Discover everything we offer to make your stay unforgettable</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Beach Access</h3>
                <p class="text-gray-600">Direct access to pristine beaches</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Fine Dining</h3>
                <p class="text-gray-600">World-class restaurant & bar</p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Spa & Wellness</h3>
                <p class="text-gray-600">Relaxation & rejuvenation</p>
            </div>
            <!-- Service Card 4 -->
            <div class="bg-gray-50 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Activities</h3>
                <p class="text-gray-600">Water sports & adventures</p>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-teal-600 font-heading mb-4">Our Rooms</h2>
        <p class="text-lg text-gray-600 mb-12">Choose from our cozy and spacious accommodations</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="bg-gradient-to-br from-teal-100 to-cyan-100 h-48 flex items-center justify-center">
                    <span class="text-6xl font-bold text-teal-600">B</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Beachfront Cottage</h3>
                    <p class="text-gray-600 mb-4">Wake up to the sound of waves</p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            Ocean View
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            Private Balcony
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            King Bed
                        </li>
                    </ul>
                    <button class="w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="bg-gradient-to-br from-teal-100 to-cyan-100 h-48 flex items-center justify-center">
                    <span class="text-6xl font-bold text-teal-600">D</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Deluxe Room</h3>
                    <p class="text-gray-600 mb-4">Perfect for couples or families</p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            Modern Amenities
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            Air Conditioning
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            Flat Screen TV
                        </li>
                    </ul>
                    <button class="w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                        View Details
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="bg-gradient-to-br from-teal-100 to-cyan-100 h-48 flex items-center justify-center">
                    <span class="text-6xl font-bold text-teal-600">G</span>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Garden Villa</h3>
                    <p class="text-gray-600 mb-4">Surrounded by lush tropical gardens</p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            Private Pool
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            Garden View
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span>
                            Spacious Layout
                        </li>
                    </ul>
                    <button class="w-full bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                        View Details
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-teal-600 font-heading mb-4">Our Promos</h2>
        <p class="text-lg text-gray-600 mb-12">Special offers and packages for your perfect getaway</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border-2 border-yellow-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex flex-col">
                <div class="text-center flex-1 flex flex-col">
                    <div class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full inline-block mb-3">
                        LIMITED TIME
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Early Bird Special</h3>
                    <p class="text-gray-600 mb-4">Book 30 days in advance and save up to 25%</p>
                    <div class="text-3xl font-bold text-yellow-600 mb-4">
                        <span class="line-through text-gray-400 text-xl">₱8,000</span>
                        <span class="ml-2">₱6,000</span>
                    </div>
                    <ul class="text-sm text-gray-600 mb-4 space-y-1">
                        <li>✓ 2 nights accommodation</li>
                        <li>✓ Free breakfast included</li>
                        <li>✓ Priority room selection</li>
                    </ul>
                    <button class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 mt-auto">
                        Book Now
                    </button>
                </div>
            </div>

            <div class="bg-gradient-to-br from-teal-50 to-cyan-50 rounded-2xl p-6 border-2 border-teal-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex flex-col">
                <div class="text-center flex-1 flex flex-col">
                    <div class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full inline-block mb-3">
                        FAMILY FAVORITE
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Family Package</h3>
                    <p class="text-gray-600 mb-4">Perfect for families with kids under 12</p>
                    <div class="text-3xl font-bold text-teal-600 mb-4">
                        <span class="line-through text-gray-400 text-xl">₱15,000</span>
                        <span class="ml-2">₱12,000</span>
                    </div>
                    <ul class="text-sm text-gray-600 mb-4 space-y-1">
                        <li>✓ 2 nights accommodation</li>
                        <li>✓ Free breakfast for kids</li>
                        <li>✓ Beach activities included</li>
                    </ul>
                    <button class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 mt-auto">
                        Book Now
                    </button>
                </div>
            </div>

            <div class="bg-gradient-to-br from-pink-50 to-purple-50 rounded-2xl p-6 border-2 border-pink-200 hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex flex-col">
                <div class="text-center flex-1 flex flex-col">
                    <div class="bg-pink-500 text-white text-sm font-bold px-3 py-1 rounded-full inline-block mb-3">
                        ROMANTIC
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Honeymoon Special</h3>
                    <p class="text-gray-600 mb-4">Create unforgettable memories with your loved one</p>
                    <div class="text-3xl font-bold text-pink-600 mb-4">
                        <span class="line-through text-gray-400 text-xl">₱20,000</span>
                        <span class="ml-2">₱16,000</span>
                    </div>
                    <ul class="text-sm text-gray-600 mb-4 space-y-1">
                        <li>✓ 3 nights luxury suite</li>
                        <li>✓ Couples spa treatment</li>
                        <li>✓ Private dinner on the beach</li>
                    </ul>
                    <button class="w-full bg-gradient-to-r from-pink-500 to-purple-500 hover:from-pink-600 hover:to-purple-600 text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 mt-auto">
                        Book Now
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-12 bg-gradient-to-r from-teal-100 to-cyan-100 rounded-2xl p-8">
            <h3 class="text-2xl font-bold text-teal-700 mb-4">All Promos Include:</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="flex items-center justify-center gap-3">
                    <span class="text-gray-700 font-medium">Beach Access</span>
                </div>
                <div class="flex items-center justify-center gap-3">
                    <span class="text-gray-700 font-medium">Daily Breakfast</span>
                </div>
                <div class="flex items-center justify-center gap-3">
                    <span class="text-gray-700 font-medium">Free Parking</span>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-4">
                * Terms and conditions apply. Promos valid until December 31, 2025. Subject to availability.
            </p>
        </div>
    </div>
</section>

<section class="py-16 bg-gradient-to-br from-teal-600 to-teal-700 text-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold tracking-tight font-heading mb-4">Ready to Book Paradise?</h2>
        <p class="text-lg text-white/90 mb-8">Book your stay at Laiya Grande and experience the beauty of San Juan, Batangas</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ url('/register') }}" class="inline-block rounded-full bg-yellow-400 px-8 py-4 font-semibold text-black shadow-lg shadow-black/20 ring-1 ring-white/10 hover:bg-yellow-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-yellow-300 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-700 motion-safe:transition">
                Book Your Stay
            </a>
            <a href="{{ url('/contact') }}" class="inline-block rounded-full border-2 border-white/30 bg-white/10 backdrop-blur-sm px-8 py-4 font-semibold text-white hover:bg-white/20 hover:border-white/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-700 motion-safe:transition">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection
