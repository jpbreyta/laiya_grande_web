@extends('user.layouts.app')

@section('content')

<style>

    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .lightbox-animate {
        animation: zoomIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    ::selection {
        background-color: #0d9488;
        color: white;
    }
</style>

<section class="relative isolate h-[50vh] min-h-[400px] overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img src="{{ asset('images/bg3.jpg') }}" alt="Laiya Grande Resort" class="h-full w-full object-cover transition-transform duration-1000 hover:scale-105">
    </div>

    <div class="absolute inset-0 bg-gradient-to-t from-teal-950/90 via-black/40 to-black/30"></div>

    <div class="relative mx-auto max-w-7xl px-6 h-full flex flex-col justify-center items-center text-center">
        <div class="max-w-3xl animate-slide-up">
            <span class="inline-block py-1 px-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs font-bold tracking-widest text-white uppercase mb-4">
                Visual Experience
            </span>
            <h1 class="text-5xl md:text-7xl font-bold tracking-tight text-white font-heading mb-6 drop-shadow-lg">
                The Gallery
            </h1>
            <p class="text-lg md:text-xl text-gray-200 font-light max-w-2xl mx-auto leading-relaxed">
                Explore the pristine beauty, luxurious accommodations, and vibrant life at <span class="text-yellow-400 font-medium">Laiya Grande</span>.
            </p>
        </div>
    </div>
</section>

<section class="sticky top-0 z-30 bg-white/90 backdrop-blur-md border-b border-gray-200 py-4 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 overflow-x-auto no-scrollbar">
        <div class="flex flex-nowrap md:flex-wrap justify-start md:justify-center gap-2 md:gap-4 min-w-max px-2">
            <button class="gallery-filter active group relative px-6 py-2.5 rounded-full text-sm font-bold tracking-wide transition-all duration-300 bg-teal-700 text-white shadow-lg ring-2 ring-teal-700 ring-offset-2" data-filter="all">
                All Photos
            </button>
            
            <button class="gallery-filter group relative px-6 py-2.5 rounded-full text-sm font-bold tracking-wide transition-all duration-300 bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 hover:border-gray-300" data-filter="beach">
                Beach
            </button>
            
            <button class="gallery-filter group relative px-6 py-2.5 rounded-full text-sm font-bold tracking-wide transition-all duration-300 bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 hover:border-gray-300" data-filter="rooms">
                Accommodations
            </button>
            
            <button class="gallery-filter group relative px-6 py-2.5 rounded-full text-sm font-bold tracking-wide transition-all duration-300 bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 hover:border-gray-300" data-filter="amenities">
                Activities
            </button>
            
            <button class="gallery-filter group relative px-6 py-2.5 rounded-full text-sm font-bold tracking-wide transition-all duration-300 bg-gray-50 text-gray-600 hover:bg-gray-100 border border-gray-200 hover:border-gray-300" data-filter="packages">
                Packages
            </button>
        </div>
    </div>
</section>

<section class="py-12 md:py-20 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8" id="gallery-grid">

            @php
                $beachPhotos = [
                    ['file' => 'real', 'title' => 'Serene Shoreline', 'desc' => 'Relax on our pristine white sands while enjoying the calming sound of the waves.'],
                    ['file' => 'poster', 'title' => 'Resort Overview', 'desc' => 'A panoramic shot of the Laiya Grande resort grounds and beachfront.'],
                    ['file' => 'bg3', 'title' => 'Golden Hour', 'desc' => 'Witness the breathtaking sunset that paints the sky in hues of orange and purple.'],
                ];
                $rooms = [
                    ['file' => 'HermosaKadayawan', 'title' => 'Hermosa Kadayawan', 'desc' => 'A spacious family suite perfect for large gatherings.'],
                    ['file' => 'Marina (2)', 'title' => 'Marina View', 'desc' => 'Enjoy a direct view of the ocean from this cozy double room.'],
                    ['file' => 'Sublian', 'title' => 'Sublian Villa', 'desc' => 'Traditional aesthetics meet modern comfort in this standalone villa.'],
                    ['file' => 'Ati-atihan', 'title' => 'Ati-atihan Suite', 'desc' => 'Vibrant interiors inspired by the famous Filipino festival.'],
                    ['file' => 'Sigapawan', 'title' => 'Sigapawan Hut', 'desc' => 'A native-style accommodation for a true island experience.'],
                    ['file' => 'Aliwan', 'title' => 'Aliwan Lodge', 'desc' => 'Perfect for couples looking for a private getaway.'],
                    ['file' => 'Pahiyas', 'title' => 'Pahiyas Room', 'desc' => 'Colorful decor that brightens up your stay.'],
                    ['file' => 'Marina (3)', 'title' => 'Marina Deluxe', 'desc' => 'Upgraded amenities with a private balcony.'],
                    ['file' => 'Marina (1)', 'title' => 'Marina Standard', 'desc' => 'Comfortable and affordable luxury by the beach.'],
                    ['file' => 'Panagbenga', 'title' => 'Panagbenga Flower', 'desc' => 'Surrounded by the resort gardens for a refreshing stay.'],
                ];
                $activities = [
                    ['file' => 'banana-boat', 'title' => 'Banana Boat Ride', 'desc' => 'Hold on tight! A thrilling group activity riding the waves.'],
                    ['file' => 'kayaking', 'title' => 'Kayaking Adventure', 'desc' => 'Paddle through the calm waters and explore the coastline at your own pace.'],
                    ['file' => 'jetski', 'title' => 'Jet Ski Experience', 'desc' => 'Feel the need for speed with our high-powered jet skis.'],
                    ['file' => 'island-hopping', 'title' => 'Island Hopping', 'desc' => 'Discover hidden coves and nearby islands with our boat tours.'],
                ];
                $packages = [
                    ['file' => 'daytour', 'title' => 'Day Tour Special', 'desc' => 'Access to all amenities from 8 AM to 5 PM with lunch included.'],
                    ['file' => 'overnight', 'title' => 'Overnight Getaway', 'desc' => 'Experience the magic of Laiya at night with our full-board overnight stay.'],
                ];
            @endphp

            @foreach ($beachPhotos as $photo)
                <div class="gallery-item beach group relative cursor-zoom-in overflow-hidden rounded-2xl bg-gray-200 shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-1" 
                     data-category="beach"
                     data-src="{{ asset('images/beach/' . $photo['file'] . '.jpg') }}"
                     data-title="{{ $photo['title'] }}"
                     data-description="{{ $photo['desc'] }}">
                    
                    <div class="aspect-[4/5] overflow-hidden">
                        <img src="{{ asset('images/beach/' . $photo['file'] . '.jpg') }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 will-change-transform group-hover:scale-110 group-hover:filter group-hover:brightness-110">
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                         <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <span class="text-yellow-400 text-xs font-bold uppercase tracking-wider mb-1 block">Beach</span>
                            <h3 class="text-white font-bold text-lg leading-tight">{{ $photo['title'] }}</h3>
                         </div>
                    </div>
                    
                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                    </div>
                </div>
            @endforeach

            @foreach ($rooms as $room)
                <div class="gallery-item rooms group relative cursor-zoom-in overflow-hidden rounded-2xl bg-gray-200 shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-1" 
                     data-category="rooms"
                     data-src="{{ asset('images/rooms/' . $room['file'] . '.jpg') }}"
                     data-title="{{ $room['title'] }}"
                     data-description="{{ $room['desc'] }}">
                    
                     <div class="aspect-[4/5] overflow-hidden">
                        <img src="{{ asset('images/rooms/' . $room['file'] . '.jpg') }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 will-change-transform group-hover:scale-110 group-hover:filter group-hover:brightness-110">
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                         <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <span class="text-teal-300 text-xs font-bold uppercase tracking-wider mb-1 block">Room</span>
                            <h3 class="text-white font-bold text-lg leading-tight">{{ $room['title'] }}</h3>
                         </div>
                    </div>
                    
                     <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                    </div>
                </div>
            @endforeach

            @foreach ($activities as $activity)
                <div class="gallery-item amenities group relative cursor-zoom-in overflow-hidden rounded-2xl bg-gray-200 shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-1" 
                     data-category="amenities"
                     data-src="{{ asset('images/activities/' . $activity['file'] . '.jpg') }}"
                     data-title="{{ $activity['title'] }}"
                     data-description="{{ $activity['desc'] }}">
                    
                    <div class="aspect-[4/5] overflow-hidden">
                        <img src="{{ asset('images/activities/' . $activity['file'] . '.jpg') }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 will-change-transform group-hover:scale-110 group-hover:filter group-hover:brightness-110">
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                         <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <span class="text-blue-300 text-xs font-bold uppercase tracking-wider mb-1 block">Activity</span>
                            <h3 class="text-white font-bold text-lg leading-tight">{{ $activity['title'] }}</h3>
                         </div>
                    </div>

                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                    </div>
                </div>
            @endforeach

            @foreach ($packages as $package)
                <div class="gallery-item packages group relative cursor-zoom-in overflow-hidden rounded-2xl bg-gray-200 shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-1" 
                     data-category="packages"
                     data-src="{{ asset('images/packages/' . $package['file'] . '.jpg') }}"
                     data-title="{{ $package['title'] }}"
                     data-description="{{ $package['desc'] }}">
                    
                     <div class="aspect-[4/5] overflow-hidden">
                        <img src="{{ asset('images/packages/' . $package['file'] . '.jpg') }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 will-change-transform group-hover:scale-110 group-hover:filter group-hover:brightness-110">
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                         <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <span class="text-orange-300 text-xs font-bold uppercase tracking-wider mb-1 block">Package</span>
                            <h3 class="text-white font-bold text-lg leading-tight">{{ $package['title'] }}</h3>
                         </div>
                    </div>

                    <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>

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

<div id="lightbox" class="fixed inset-0 z-[999] hidden" aria-modal="true">
    
    <div class="absolute inset-0 bg-black/95 backdrop-blur-lg transition-opacity duration-300 opacity-0" id="lightbox-backdrop"></div>

    <div class="relative w-full h-full flex flex-col md:flex-row lightbox-animate opacity-0 transition-all duration-300" id="lightbox-content">

        <button id="close-lightbox" class="absolute top-6 right-6 z-50 p-3 text-white/50 hover:text-white bg-black/50 hover:bg-white/10 backdrop-blur-xl rounded-full transition-all duration-200 hover:rotate-90 border border-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="relative flex-1 flex items-center justify-center p-4 md:p-12 h-[65vh] md:h-full group bg-gradient-to-b from-gray-900 to-black">
            
            <img id="lightbox-img" src="" class="max-w-full max-h-full object-contain rounded shadow-2xl transition-opacity duration-300" alt="Gallery Image" />

            <button id="prev-btn" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 p-4 text-white/80 hover:text-white bg-white/5 hover:bg-white/10 backdrop-blur-md rounded-full border border-white/10 transition-all duration-300 hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </button>

            <button id="next-btn" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 p-4 text-white/80 hover:text-white bg-white/5 hover:bg-white/10 backdrop-blur-md rounded-full border border-white/10 transition-all duration-300 hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </button>
        </div>

        <div class="w-full md:w-[400px] bg-white text-gray-800 flex flex-col h-[35vh] md:h-full shadow-2xl relative z-40">
            <div class="flex-1 overflow-y-auto p-8 md:p-10 no-scrollbar flex flex-col justify-center">
                
                <div class="w-16 h-1.5 bg-teal-500 mb-8 rounded-full"></div>

                <h3 id="lightbox-title" class="text-3xl font-bold font-heading text-gray-900 mb-6 leading-tight"></h3>
                
                <p id="lightbox-desc" class="text-gray-600 text-lg leading-relaxed font-light"></p>

                <div class="mt-auto pt-10 border-t border-gray-100">
                    <div class="flex items-center text-teal-600 text-xs font-bold uppercase tracking-widest mb-2">
                        <span class="w-2 h-2 bg-teal-500 rounded-full mr-3 animate-pulse"></span>
                        Laiya Grande Collection
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const filterButtons = document.querySelectorAll(".gallery-filter");
    const items = [...document.querySelectorAll(".gallery-item")]; 

    const lightbox = document.getElementById("lightbox");
    const backdrop = document.getElementById("lightbox-backdrop");
    const content = document.getElementById("lightbox-content");
    const img = document.getElementById("lightbox-img");
    const title = document.getElementById("lightbox-title");
    const desc = document.getElementById("lightbox-desc");

    const nextBtn = document.getElementById("next-btn");
    const prevBtn = document.getElementById("prev-btn");
    const closeBtn = document.getElementById("close-lightbox");

    let currentIndex = 0;
    let visibleItems = items; 


    filterButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const filter = btn.dataset.filter;


            filterButtons.forEach(b => {
                b.classList.remove("bg-teal-700", "text-white", "shadow-lg", "ring-2", "ring-teal-700", "ring-offset-2");
                b.classList.add("bg-gray-50", "text-gray-600", "hover:bg-gray-100", "border", "border-gray-200");
            });
            

            btn.classList.remove("bg-gray-50", "text-gray-600", "hover:bg-gray-100", "border", "border-gray-200");
            btn.classList.add("bg-teal-700", "text-white", "shadow-lg", "ring-2", "ring-teal-700", "ring-offset-2");


            items.forEach(item => {
                if (filter === "all" || item.dataset.category === filter) {
                    item.style.display = "block";

                } else {
                    item.style.display = "none";
                }
            });

            visibleItems = items.filter(item => item.style.display !== "none");
        });
    });

    // --- LIGHTBOX FUNCTIONS 

    function openLightbox(targetItem) {
        currentIndex = visibleItems.indexOf(targetItem);
        if (currentIndex === -1) return; 

        updateContent();
        
        lightbox.classList.remove("hidden");
        setTimeout(() => {
            backdrop.classList.remove("opacity-0");
            content.classList.remove("opacity-0"); 
        }, 10);

        document.body.style.overflow = "hidden";
    }

    function closeLightbox() {
        backdrop.classList.add("opacity-0");
        content.classList.add("opacity-0");

        setTimeout(() => {
            lightbox.classList.add("hidden");
            document.body.style.overflow = "auto";
        }, 300);
    }

    function updateContent() {
        const item = visibleItems[currentIndex];
        
        img.style.opacity = "0.5";
        img.style.transform = "scale(0.98)";
        
        setTimeout(() => {
            img.src = item.dataset.src;
            title.textContent = item.dataset.title;
            desc.textContent = item.dataset.description;
            
            img.onload = () => {
                img.style.opacity = "1";
                img.style.transform = "scale(1)";
            };
        }, 150);
    }

    function next() {
        currentIndex = (currentIndex + 1) % visibleItems.length;
        updateContent();
    }

    function prev() {
        currentIndex = (currentIndex - 1 + visibleItems.length) % visibleItems.length;
        updateContent();
    }

    items.forEach(item => {
        item.addEventListener("click", () => openLightbox(item));
    });

    nextBtn.onclick = (e) => { e.stopPropagation(); next(); };
    prevBtn.onclick = (e) => { e.stopPropagation(); prev(); };
    closeBtn.onclick = closeLightbox;

    lightbox.onclick = (e) => {
        if (e.target === lightbox || e.target === backdrop || e.target.closest('.flex-1.bg-gradient-to-b')) {
   
             if (e.target !== img && e.target !== nextBtn && e.target !== prevBtn) {
                 closeLightbox();
             }
        }
    };

    document.addEventListener("keydown", (e) => {
        if (lightbox.classList.contains("hidden")) return;
        if (e.key === "ArrowRight") next();
        if (e.key === "ArrowLeft") prev();
        if (e.key === "Escape") closeLightbox();
    });
});
</script>

@endsection