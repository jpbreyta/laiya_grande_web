@extends('user.layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .font-poppins { font-family: 'Poppins', sans-serif; }
        
        /* Hide scrollbar for sleek look */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        [x-cloak] { display: none !important; }

        /* Smooth fade animation */
        .fade-enter-active, .fade-leave-active { transition: opacity 0.5s ease; }
        .fade-enter-from, .fade-leave-to { opacity: 0; }
    </style>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a', // Primary Dark
                            950: '#042f2e',
                        },
                        sand: '#FAFAF9'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-sand font-sans text-teal-900 antialiased">

    <div x-data="{ 
        modalOpen: false, 
        modalImages: [], 
        currentModalSlide: 0,
        modalTitle: '',
        modalDesc: '',
        
        openModal(images, title, desc) { 
            this.modalImages = images; 
            this.modalTitle = title;
            this.modalDesc = desc;
            this.currentModalSlide = 0; // Reset to first image
            this.modalOpen = true;
            document.body.style.overflow = 'hidden'; 
        },
        
        closeModal() { 
            this.modalOpen = false;
            setTimeout(() => { this.modalImages = [] }, 300); // Clear after transition
            document.body.style.overflow = 'auto'; 
        },

        nextSlide() {
            this.currentModalSlide = (this.currentModalSlide === this.modalImages.length - 1) ? 0 : this.currentModalSlide + 1;
        },
        
        prevSlide() {
            this.currentModalSlide = (this.currentModalSlide === 0) ? this.modalImages.length - 1 : this.currentModalSlide - 1;
        }
    }">

        <header class="relative h-screen min-h-[700px] w-full overflow-hidden bg-teal-950">
            
            <div x-data="{
                active: 0,
                slides: [
                    '{{ asset('images/sunset1.jpg') }}',
                    '{{ asset('images/bg3.jpg') }}'
                ],
                init() { setInterval(() => { this.active = (this.active + 1) % this.slides.length }, 5000); }
            }" class="absolute inset-0 z-0">
                <template x-for="(slide, index) in slides" :key="index">
                    <div class="absolute inset-0 transition-opacity duration-[1500ms] ease-in-out"
                         :class="active === index ? 'opacity-100' : 'opacity-0'">
                        <img :src="slide" class="h-full w-full object-cover transition-transform duration-[8000ms] ease-linear transform origin-center"
                             :class="active === index ? 'scale-110' : 'scale-100'">
                        <div class="absolute inset-0 bg-gradient-to-t from-teal-950/90 via-teal-900/40 to-teal-900/30"></div>
                    </div>
                </template>
            </div>

            <nav class="absolute top-0 left-0 w-full z-20 px-6 py-6 flex justify-between items-center">
                <div class="text-white font-playfair font-bold text-2xl tracking-wide">
                    Laiya<span class="text-teal-300">Grande</span>
                </div>
                <div class="hidden md:flex space-x-8 text-white/90 text-sm font-medium tracking-widest uppercase">
                    <a href="#" class="hover:text-teal-300 transition-colors">Home</a>
                    <a href="#" class="hover:text-teal-300 transition-colors">Rooms</a>
                    <a href="#" class="hover:text-teal-300 transition-colors">Dining</a>
                    <a href="#" class="hover:text-teal-300 transition-colors">Contact</a>
                </div>
            </nav>

            <div class="relative z-10 flex h-full flex-col items-center justify-center px-6 text-center text-white">
                <div class="max-w-4xl space-y-8">
                    
                    <div class="inline-flex items-center gap-4 px-4 py-2 rounded-full border border-white/20 bg-white/10 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-300 animate-pulse"></span>
                        <span class="text-xs font-semibold uppercase tracking-widest text-teal-50">Escape to Paradise</span>
                    </div>

                    <h1 class="font-playfair text-6xl md:text-7xl lg:text-9xl font-medium leading-none drop-shadow-lg tracking-tight">
                        {{ $settings->resort_name ?? 'Laiya Grande' }}
                    </h1>

                    <p class="mx-auto max-w-2xl text-lg md:text-xl font-light text-teal-50/90 leading-relaxed font-sans">
                        {{ $settings->tagline ?? 'Experience the perfect blend of luxury and nature. Your pristine sanctuary awaits.' }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-5 pt-8">
                        <a href="{{ route('user.rooms.index') }}" 
                           class="group min-w-[180px] rounded-full bg-teal-700 px-8 py-4 text-sm font-semibold uppercase tracking-widest text-white shadow-lg shadow-teal-900/20 transition-all hover:bg-teal-500 hover:-translate-y-1">
                            Book Now
                        </a>
                        <button @click="document.getElementById('discover').scrollIntoView({behavior: 'smooth'})" 
                                class="group min-w-[180px] rounded-full border border-white/30 bg-transparent px-8 py-4 text-sm font-semibold uppercase tracking-widest text-white backdrop-blur-sm transition-all hover:bg-white hover:text-teal-900 hover:-translate-y-1">
                            Explore
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-sand to-transparent z-10"></div>
        </header>

        <section id="discover" class="py-20 md:py-32 overflow-hidden bg-sand relative">
            
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>

            <div class="relative z-10" x-data="{
                cardWidth: 400,
                items: [
                    { 
                        title: 'Accommodations', 
                        category: 'Available',
                        desc: 'Each designed with modern comforts, stunning ocean views, and thoughtful amenities to make your stay relaxing and memorable.',
                        detail: 'Reservation Required',
                        // UPDATED: Now an array of images
                        images: [
                            '{{ asset('images/rooms/Marina (3).jpg') }}',
                            '{{ asset('images/rooms/Sublian.jpg') }}',
                            '{{ asset('images/rooms/Marina (2).jpg') }}',
                            '{{ asset('images/rooms/HermosaKadayawan.jpg') }}',
                        ]
                    },
                    { 
                        title: 'Activities',
                        category: 'Available',
                        desc: 'Discover thrilling water sports, guided island tours, and beachside yoga sessions designed to enrich your stay.',
                        detail: 'RESERVATION REQUIRED',
                        images: [
                            '{{ asset('images/activities/jetski.jpg') }}',
                            '{{ asset('images/activities/banana-boat.jpg') }}',
                            '{{ asset('images/activities/island-hopping.jpg') }}'
                        ]
                    },
                    { 
                        title: 'Beach View',
                        category: 'Available',
                        desc: 'Unwind with sweeping views of Laiya’s pristine shoreline, where white sands meet crystal-clear waters.',
                        detail: 'Perfect Spot',
                        images: [
                            '{{ asset('images/beach/poster.jpg') }}',
                            '{{ asset('images/beach/bg3.jpg') }}',
                            '{{ asset('images/beach/real.jpg') }}'
                        ]
                    },
                    { 
                        title: 'Packages',
                        category: 'Available',
                        desc: 'Exclusive stay bundles combining beachfront accommodations, curated activities, and dining experiences for a complete getaway.',
                        detail: 'Reservation Required',
                        images: [
                            '{{ asset('images/packages/daytour.jpg') }}',
                            '{{ asset('images/packages/overnight.jpg') }}'
                        ]
                    }
                ],
                scroll(direction) {
                    const container = this.$refs.container;
                    const scrollAmount = direction === 'left' ? -this.cardWidth : this.cardWidth;
                    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                }
            }">
                <div class="mx-auto max-w-7xl px-6">
                    
                    <div class="mb-12 flex flex-col md:flex-row md:items-end md:justify-between">
                        <div class="max-w-2xl">
                            <span class="mb-3 block text-sm font-bold uppercase tracking-widest text-teal-600">Discover The Resort</span>
                            <h2 class="font-playfair text-4xl md:text-5xl lg:text-6xl text-teal-900 leading-tight">
                                Curated Experiences <br> 
                                <span class="text-teal-600/60 italic font-serif">& Amenities</span>
                            </h2>
                        </div>
                        
                        <div class="mt-8 flex gap-3 md:mt-0">
                            <button @click="scroll('left')" class="group rounded-full border border-teal-200 bg-white p-4 transition-all hover:bg-teal-900 hover:border-teal-900 shadow-sm">
                                <svg class="h-5 w-5 text-teal-700 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            <button @click="scroll('right')" class="group rounded-full border border-teal-200 bg-white p-4 transition-all hover:bg-teal-900 hover:border-teal-900 shadow-sm">
                                <svg class="h-5 w-5 text-teal-700 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>
                    </div>

                    <div x-ref="container" class="no-scrollbar flex gap-8 overflow-x-auto pb-12 snap-x snap-mandatory px-2">
                        <template x-for="item in items">
                            
                            <div class="group relative h-[500px] min-w-[320px] md:min-w-[400px] snap-center cursor-pointer" 
                                 @click="openModal(item.images, item.title, item.desc)">
                                
                                <div class="relative h-full w-full overflow-hidden rounded-3xl shadow-lg transition-all duration-500 group-hover:shadow-[0_20px_50px_rgba(13,148,136,0.15)] group-hover:-translate-y-2">
                                    <img :src="item.images[0]" class="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-teal-950/80 via-transparent to-transparent opacity-80 transition-opacity duration-500 group-hover:opacity-90"></div>
                                    
                                    <div class="absolute top-6 right-6 z-10">
                                        <span class="bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest text-teal-900 shadow-sm" x-text="item.category"></span>
                                    </div>

                                    <div class="absolute bottom-0 left-0 right-0 p-8 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                        <h3 class="font-playfair text-3xl text-white mb-2" x-text="item.title"></h3>
                                        <div class="w-12 h-0.5 bg-teal-400 mb-4 transition-all duration-500 group-hover:w-20"></div>
                                        <div class="flex items-center justify-between text-white/90">
                                            <p class="text-xs font-bold uppercase tracking-widest" x-text="item.detail"></p>
                                            <div class="bg-white/20 p-2 rounded-full backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100">
                                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <div x-show="modalOpen" x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-0 md:p-6"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="absolute inset-0 bg-teal-950/80 backdrop-blur-sm" @click="closeModal()"></div>

            <div class="relative grid h-full md:h-auto md:max-h-[90vh] w-full max-w-6xl grid-cols-1 overflow-hidden bg-white shadow-2xl md:rounded-3xl md:grid-cols-12"
                 x-transition:enter="transition ease-out duration-500 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <button @click="closeModal()" class="absolute right-4 top-4 z-50 rounded-full bg-white/20 backdrop-blur-md p-2 text-white md:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="relative h-[40vh] md:h-auto md:col-span-7 bg-gray-200 group">
                    
                    <template x-for="(img, index) in modalImages" :key="index">
                        <div x-show="currentModalSlide === index"
                             x-transition:enter="transition ease-in-out duration-500 absolute inset-0"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in-out duration-500 absolute inset-0"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="h-full w-full">
                            <img :src="img" class="h-full w-full object-cover">
                        </div>
                    </template>
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-teal-900/60 to-transparent pointer-events-none md:hidden"></div>

                    <div x-show="modalImages.length > 1" class="absolute inset-0 flex items-center justify-between p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button @click="prevSlide()" class="rounded-full bg-white/10 hover:bg-white/30 backdrop-blur-md p-3 text-white transition-colors border border-white/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button @click="nextSlide()" class="rounded-full bg-white/10 hover:bg-white/30 backdrop-blur-md p-3 text-white transition-colors border border-white/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <div x-show="modalImages.length > 1" class="absolute bottom-6 left-0 right-0 flex justify-center gap-2 z-20">
                        <template x-for="(img, index) in modalImages" :key="index">
                            <button @click="currentModalSlide = index" 
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="currentModalSlide === index ? 'w-6 bg-white' : 'w-1.5 bg-white/50 hover:bg-white/80'">
                            </button>
                        </template>
                    </div>
                </div>

                <div class="flex flex-col p-8 md:p-12 bg-white md:col-span-5 relative">
                    
                    <button @click="closeModal()" class="absolute right-6 top-6 hidden md:block rounded-full bg-gray-50 p-2 text-gray-400 hover:bg-teal-50 hover:text-teal-600 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-[1px] w-8 bg-teal-500"></div>
                        <span class="text-xs font-bold uppercase tracking-widest text-teal-500">Experience Details</span>
                    </div>
                    
                    <h3 class="font-playfair text-4xl md:text-5xl text-teal-900 mb-6 leading-none" x-text="modalTitle"></h3>
                    
                    <p class="font-sans text-gray-600 leading-relaxed mb-8 text-sm md:text-base font-light" x-text="modalDesc"></p>
                    
                    <div class="flex flex-wrap gap-2 mb-8">
                         <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-full">Free Wifi</span>
                         <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-full">Ocean View</span>
                         <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-medium rounded-full">24/7 Service</span>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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
                    <img src="{{ asset('images/sunset1.jpg') }}" alt="Resort View" class="w-full h-full object-cover opacity-80">
                </div>
                <div class="absolute bottom-0 left-0 w-4/5 h-4/5 bg-white p-2 rounded-2xl shadow-2xl z-10 transform -translate-x-4 translate-y-4">
                     <img src="https://pix8.agoda.net/hotelImages/488858/-1/6e755f3d001192a6da3cb212f513aad6.jpg" alt="Resort View" class="w-full h-full object-cover opacity-80">
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
                        <a href="https://www.google.com/maps/embed?origin=mfe&pb=!1m3!2m1!1sLaiya+Grande+Beach+Resort+Batangas!6i13" target="_blank" class="text-xs text-teal-600 font-bold mt-2 inline-block hover:underline">Open in Google Maps</a>
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