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
</div>

@endsection