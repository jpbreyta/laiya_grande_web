@extends('user.layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');

    .font-heading { font-family: 'Playfair Display', serif; }
    .font-body { font-family: 'Inter', sans-serif; }
    
    .text-shadow { text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
</style>

<div class="min-h-screen bg-stone-50 font-body text-slate-600">

    <div class="relative h-[60vh] min-h-[500px] flex items-center justify-center bg-fixed bg-center bg-cover" 
         style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');">
        <div class="absolute inset-0 bg-teal-900/40 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-stone-50 via-transparent to-transparent"></div>
        
        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto animate-fade-in-up">
            <p class="text-white/90 uppercase tracking-[0.2em] text-sm md:text-base mb-4 font-medium">Since the Early 2000s</p>
            <h1 class="text-5xl md:text-7xl font-heading font-bold text-white mb-6 text-shadow">
                About Laiya Grande
            </h1>
            <div class="w-24 h-1 bg-yellow-400 mx-auto rounded-full"></div>
            <p class="mt-6 text-xl text-white/90 font-light max-w-2xl mx-auto leading-relaxed">
                Where the sun meets the sand and Filipino hospitality meets seaside serenity.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-20 md:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <div class="space-y-8 animate-fade-in-up">
                <div class="inline-flex items-center gap-2 text-teal-600 font-semibold tracking-wider uppercase text-xs">
                    <span class="w-8 h-[1px] bg-teal-600"></span> Our History
                </div>
                <h2 class="text-4xl md:text-5xl font-heading text-teal-900 font-bold leading-tight">
                    A Legacy of <br><span class="italic text-teal-600">Coastal Hospitality</span>
                </h2>
                <div class="space-y-6 text-lg leading-relaxed text-slate-600">
                    <p>
                        Nestled along the pristine shores of Laiya Aplaya in San Juan, Batangas, 
                        <span class="font-bold text-teal-800">Laiya Grande Beach Resort</span> has been a beloved destination since the early 2000s. 
                        What began as a modest beachfront retreat has grown into one of Laiya’s most recognized names.
                    </p>
                    <p>
                        Inspired by the natural beauty of Batangas — from its white sand beaches to its lush mountain views — 
                        we were built with the vision of blending relaxation, recreation, and warmth.
                    </p>
                    <p>
                        Today, we continue to evolve, offering upgraded amenities and curated activities, all while preserving 
                        the charm that made us a favorite getaway.
                    </p>
                </div>
                
                <div class="pt-4">
                    <div class="text-4xl font-heading text-teal-800/20 italic">Laiya Grande</div>
                </div>
            </div>

            <div class="relative hidden lg:block h-full min-h-[500px]">
                <div class="absolute top-0 right-0 w-4/5 h-4/5 rounded-2xl overflow-hidden shadow-2xl z-0 transform translate-x-4 -translate-y-4">
                     <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Resort View" class="w-full h-full object-cover opacity-80">
                </div>
                <div class="absolute bottom-0 left-0 w-4/5 h-4/5 bg-white p-2 rounded-2xl shadow-2xl z-10 transform -translate-x-4 translate-y-4">
                    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Laiya Grande Pool" class="w-full h-full object-cover rounded-xl">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-teal-900 py-24 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>

        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-10 hover:bg-white/10 transition-colors duration-300 group">
                    <div class="w-14 h-14 bg-yellow-500 rounded-full flex items-center justify-center mb-6 text-teal-900 text-2xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="text-2xl font-heading font-bold text-white mb-4">Our Mission</h3>
                    <p class="text-teal-100 text-lg leading-relaxed">
                        To provide a serene and welcoming beachfront experience that celebrates Filipino hospitality, nature, and relaxation.
                    </p>
                </div>

                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-3xl p-10 hover:bg-white/10 transition-colors duration-300 group">
                    <div class="w-14 h-14 bg-teal-400 rounded-full flex items-center justify-center mb-6 text-teal-900 text-2xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="text-2xl font-heading font-bold text-white mb-4">Our Vision</h3>
                    <p class="text-teal-100 text-lg leading-relaxed">
                        To be the leading beach resort in Laiya, known for exceptional service, sustainable practices, and unforgettable guest experiences.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-heading text-teal-900 font-bold mb-4">The Laiya Difference</h2>
            <div class="w-16 h-1 bg-yellow-400 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="p-8 rounded-3xl bg-white shadow-lg shadow-teal-900/5 hover:shadow-xl transition-all duration-300 border border-slate-100 group">
                <div class="w-16 h-16 mx-auto bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <h4 class="text-xl font-bold text-teal-900 mb-3">Warm Hospitality</h4>
                <p class="text-slate-600">Personalized guest service with a genuine Filipino touch that makes you feel like family.</p>
            </div>

            <div class="p-8 rounded-3xl bg-white shadow-lg shadow-teal-900/5 hover:shadow-xl transition-all duration-300 border border-slate-100 group">
                <div class="w-16 h-16 mx-auto bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <i class="fas fa-umbrella-beach"></i>
                </div>
                <h4 class="text-xl font-bold text-teal-900 mb-3">Pristine Shores</h4>
                <p class="text-slate-600">Direct beachfront access to Laiya’s famous white sands and crystal clear waters.</p>
            </div>

            <div class="p-8 rounded-3xl bg-white shadow-lg shadow-teal-900/5 hover:shadow-xl transition-all duration-300 border border-slate-100 group">
                <div class="w-16 h-16 mx-auto bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center text-2xl mb-6 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                    <i class="fas fa-leaf"></i>
                </div>
                <h4 class="text-xl font-bold text-teal-900 mb-3">Eco-Conscious</h4>
                <p class="text-slate-600">A commitment to sustainability, respecting nature, and preserving our coastal environment.</p>
            </div>
        </div>
    </div>

    <div class="bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6 py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                
                <div class="lg:col-span-5 space-y-10">
                    <div>
                        <h2 class="text-3xl font-heading text-teal-900 font-bold mb-6">Find Your Way to Paradise</h2>
                        <p class="text-slate-600 text-lg">Approximately 3 hours (136 km) from Metro Manila. Escape the city and embrace the calm.</p>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-teal-900">Address</h5>
                                <p class="text-slate-600">Laiya Grande Beach Resort<br>Laiya Aplaya, San Juan, Batangas, 4226</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-teal-900">Contact Us</h5>
                                <p class="text-slate-600">Globe: (0977) 222 4792<br>Smart: (0963) 003 7629</p>
                                <p class="text-slate-600 text-sm mt-1 text-teal-600">laiyagrandebr22@gmail.com</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-bus"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-teal-900">Public Transport</h5>
                                <p class="text-slate-600 text-sm mb-2">Bus terminals available at:</p>
                                <ul class="list-disc list-inside text-sm text-slate-500 space-y-1">
                                    <li>Cubao Bus Terminal</li>
                                    <li>LRT Station Bus Terminal</li>
                                    <li>Alabang South Station</li>
                                </ul>
                                <p class="text-xs text-slate-400 mt-2 italic">Take a bus to San Juan, Batangas, then a tricycle to Laiya Grande.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 h-[500px] bg-slate-100 rounded-3xl overflow-hidden shadow-2xl relative">
                    <iframe
                        src="https://maps.google.com/maps?q=Laiya%20Grande%20Beach%20Resort%20Batangas&t=&z=13&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        class="grayscale hover:grayscale-0 transition-all duration-500">
                    </iframe>
                    <div class="absolute bottom-6 left-6 bg-white p-4 rounded-xl shadow-lg max-w-xs hidden md:block">
                        <p class="text-sm font-bold text-teal-900">Laiya Grande Beach Resort</p>
                        <p class="text-xs text-slate-500">San Juan, Batangas</p>
                        <a href="https://goo.gl/maps/YOUR_LINK_HERE" target="_blank" class="text-xs text-teal-600 font-bold mt-2 inline-block hover:underline">Open in Google Maps</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

<section class="py-20 relative overflow-hidden bg-teal-900">
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 rounded-full bg-teal-500 blur-3xl opacity-20"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 rounded-full bg-yellow-400 blur-3xl opacity-10"></div>
    
    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <h2 class="text-3xl md:text-5xl font-bold tracking-tight text-white mb-6 font-heading">Ready to Experience Paradise?</h2>
        <p class="text-lg text-teal-100 mb-10 max-w-2xl mx-auto font-light leading-relaxed">Book your stay today and turn these pictures into your reality. Your perfect getaway awaits at Laiya Grande.</p>

        <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
            <a href="{{ url('/register') }}"
               class="min-w-[180px] rounded-full bg-yellow-400 px-8 py-4 font-bold text-teal-900 shadow-[0_0_20px_rgba(250,204,21,0.3)] hover:bg-yellow-300 hover:shadow-[0_0_30px_rgba(250,204,21,0.5)] transform hover:-translate-y-1 transition-all duration-300">
                Book Your Stay
            </a>
            <a href="{{ url('/contact') }}"
               class="min-w-[180px] rounded-full border border-white/30 bg-white/5 backdrop-blur-sm px-8 py-4 font-bold text-white hover:bg-white/10 hover:border-white transition-all duration-300">
                Contact Us
            </a>
        </div>
    </div>
</section>

</div>

@endsection