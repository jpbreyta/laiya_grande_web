@extends('user.layouts.app')

@section('content')
<section class="relative isolate bg-center min-h-[40svh] overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img src="{{ asset('images/laiyagrande.png') }}" alt="" aria-hidden="true" class="h-full w-full object-cover">
    </div>
    
    <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-teal-900/50 dark:from-black/70 dark:via-black/50 dark:to-teal-900/60"></div>
    
    <div class="relative mx-auto max-w-7xl px-6 py-16 flex min-h-[40svh] items-center justify-center">
        <div class="text-center text-white max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-balance font-heading mb-4 animate-slide-up">
                <span class="block bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 bg-clip-text text-transparent">
                    Location
                </span>
            </h1>
            <p class="text-lg md:text-xl leading-relaxed text-white/90 mb-6 animate-fade-in" style="animation-delay: 0.3s;">
                Discover the beauty of Laiya Grande's prime location in San Juan, Batangas
            </p>
        </div>
    </div>
</section>

<section class="py-16 bg-white dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-6">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Why Choose Our Location?</h3>
                <ul class="space-y-6">
                    <li class="flex items-start gap-4">
                        <div class="w-2 h-2 bg-teal-500 rounded-full mt-3 flex-shrink-0"></div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Beachfront Access</h4>
                            <p class="text-gray-600 dark:text-slate-300">Direct access to pristine white sand beaches with crystal clear waters</p>
                        </div>
                    </li>
                    
                    <li class="flex items-start gap-4">
                        <div class="w-2 h-2 bg-teal-500 rounded-full mt-3 flex-shrink-0"></div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Breathtaking Sunsets</h4>
                            <p class="text-gray-600 dark:text-slate-300">Witness spectacular sunsets over the West Philippine Sea</p>
                        </div>
                    </li>
                    
                    <li class="flex items-start gap-4">
                        <div class="w-2 h-2 bg-teal-500 rounded-full mt-3 flex-shrink-0"></div>
                        <div>
                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Tropical Paradise</h4>
                            <p class="text-gray-600 dark:text-slate-300">Surrounded by lush tropical vegetation and natural beauty</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="relative">
                <div class="bg-gradient-to-br from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 rounded-2xl p-8 text-center h-96 flex items-center justify-center">
                    <div class="text-center">
                        <img src="{{ asset('images/laiyagrande-logo.png') }}" alt="Laiya Grande Beach Resort" class="h-56 w-auto mx-auto mb-8">
                        <h3 class="text-2xl font-bold text-teal-700 dark:text-teal-300 mb-2">Laiya Grande Resort</h3>
                        <p class="text-teal-600 dark:text-teal-400">Your gateway to paradise in San Juan, Batangas</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Prime Location</h3>
                <p class="text-gray-600 dark:text-slate-300">Located in the heart of San Juan, Batangas</p>
            </div>
            
            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Easy Access</h3>
                <p class="text-gray-600 dark:text-slate-300">Just 2-3 hours drive from Metro Manila</p>
            </div>
            
            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Beach Activities</h3>
                <p class="text-gray-600 dark:text-slate-300">Perfect for swimming, diving, and water sports</p>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-teal-600 dark:text-teal-400 font-heading mb-4">Find Us</h2>
            <p class="text-lg text-gray-600 dark:text-slate-300">Located in the beautiful coastal town of San Juan, Batangas</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="h-96">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.234567890123!2d121.3979232!3d13.6731728!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd244ce27742e5%3A0xa4c7df8e965a6e99!2sLaiya%20Grande%20Beach%20Resort!5e0!3m2!1sen!2sph!4v1234567890123!5m2!1sen!2sph"
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="rounded-2xl">
                </iframe>
            </div>
        </div>

        <div class="bg-gradient-to-r from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 rounded-2xl p-6 mb-8">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-teal-700 dark:text-teal-300 mb-4">Location Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div class="bg-white/80 dark:bg-slate-800/80 rounded-lg p-3">
                        <p class="font-semibold text-gray-900 dark:text-white">Address</p>
                        <p class="text-gray-600 dark:text-slate-300">Laiya Grande Beach Resort, San Juan, Batangas, Philippines</p>
                    </div>
                    <div class="bg-white/80 dark:bg-slate-800/80 rounded-lg p-3">
                        <p class="font-semibold text-gray-900 dark:text-white">Coordinates</p>
                        <p class="text-gray-600 dark:text-slate-300">13.6732° N, 121.3979° E</p>
                    </div>
                    <div class="bg-white/80 dark:bg-slate-800/80 rounded-lg p-3">
                        <p class="font-semibold text-gray-900 dark:text-white">Distance from Manila</p>
                        <p class="text-gray-600 dark:text-slate-300">~130 km</p>
                    </div>
                    <div class="bg-white/80 dark:bg-slate-800/80 rounded-lg p-3">
                        <p class="font-semibold text-gray-900 dark:text-white">Travel Time</p>
                        <p class="text-gray-600 dark:text-slate-300">2.5-3 hours by car</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Contact Information</h3>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full flex-shrink-0"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Address</p>
                            <p class="text-gray-600 dark:text-slate-300">Laiya Grande Beach Resort, San Juan, Batangas, Philippines 4226</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full flex-shrink-0"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Phone</p>
                            <p class="text-gray-600 dark:text-slate-300">0963 033 7629</p>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full flex-shrink-0"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">Email</p>
                            <p class="text-gray-600 dark:text-slate-300">laiyagrandebr22@gmail.com</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Getting Here</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">By Car</p>
                            <p class="text-gray-600 dark:text-slate-300">Take SLEX to Batangas, exit at Lipa, then follow the road to San Juan/Laiya</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">By Bus</p>
                            <p class="text-gray-600 dark:text-slate-300">Take a bus from Manila to San Juan, Batangas (Laiya route)</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-2 h-2 bg-teal-500 rounded-full mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">By Air</p>
                            <p class="text-gray-600 dark:text-slate-300">Fly to Manila (NAIA), then take a bus or drive to San Juan</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white dark:bg-slate-950">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-teal-600 dark:text-teal-400 font-heading mb-4">Nearby Attractions</h2>
            <p class="text-lg text-gray-600 dark:text-slate-300">Explore the beautiful attractions around Laiya Grande</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Laiya Beach</h3>
                <p class="text-gray-600 dark:text-slate-300">Pristine white sand beach perfect for swimming and sunbathing</p>
            </div>

            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Island Hopping</h3>
                <p class="text-gray-600 dark:text-slate-300">Explore nearby islands and discover hidden coves</p>
            </div>

            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Diving Spots</h3>
                <p class="text-gray-600 dark:text-slate-300">Discover vibrant coral reefs and marine life</p>
            </div>

            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Water Sports</h3>
                <p class="text-gray-600 dark:text-slate-300">Enjoy surfing, kayaking, and other water activities</p>
            </div>

            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sunset Viewing</h3>
                <p class="text-gray-600 dark:text-slate-300">Witness breathtaking sunsets over the West Philippine Sea</p>
            </div>

            <div class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 text-center hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Local Cuisine</h3>
                <p class="text-gray-600 dark:text-slate-300">Savor fresh seafood and local delicacies</p>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-gradient-to-br from-teal-600 to-teal-700 text-white dark:from-teal-700 dark:to-teal-800">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold tracking-tight font-heading mb-4">Ready to Visit Paradise?</h2>
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
