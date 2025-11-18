@extends('user.layouts.app')

@section('content')
<section class="relative isolate bg-center min-h-[40svh] overflow-hidden">
    <div class="absolute inset-0 -z-10">
        <img src="{{ asset('images/laiyagrande.png') }}" alt="" aria-hidden="true" class="h-full w-full object-cover">
    </div>

    <div class="absolute inset-0 bg-gradient-to-br from-black/60 via-black/40 to-teal-900/50"></div>
    <div class="relative mx-auto max-w-7xl px-6 py-16 flex min-h-[40svh] items-center justify-center">
        <div class="text-center text-white max-w-4xl">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-balance font-heading mb-4 animate-slide-up">
                <span class="block bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400 bg-clip-text text-transparent">
                    Gallery
                </span>
            </h1>
            <p class="text-lg md:text-xl leading-relaxed text-white/90 mb-6 animate-fade-in" style="animation-delay: 0.3s;">
                Discover the beauty of Laiya Grande through our stunning collection of photos
            </p>
        </div>
    </div>
</section>

<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-wrap justify-center gap-4">
            <button class="gallery-filter active bg-teal-500 text-white px-6 py-2 rounded-full font-medium transition-all duration-300 hover:bg-teal-600" data-filter="all">
                ALL
            </button>
            <button class="gallery-filter bg-white text-gray-700 px-6 py-2 rounded-full font-medium transition-all duration-300 hover:bg-teal-50" data-filter="beach">
                BEACH
            </button>
            <button class="gallery-filter bg-white text-gray-700 px-6 py-2 rounded-full font-medium transition-all duration-300 hover:bg-teal-50" data-filter="rooms">
                ROOMS
            </button>
            <button class="gallery-filter bg-white text-gray-700 px-6 py-2 rounded-full font-medium transition-all duration-300 hover:bg-teal-50" data-filter="amenities">
                AMENITIES
            </button>
            <button class="gallery-filter bg-white text-gray-700 px-6 py-2 rounded-full font-medium transition-all duration-300 hover:bg-teal-50" data-filter="activities">
                ACTIVITIES
            </button>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery-grid">
            
            <div class="gallery-item beach group cursor-pointer" data-category="beach">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-teal-100 to-cyan-100 flex items-center justify-center">
                        <img src="{{ asset('images/beach1.jpg') }}" alt="Pristine Beach" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="text-center hidden">
                            <h3 class="text-lg font-bold text-teal-700">Pristine Beach</h3>
                            <p class="text-sm text-teal-600">Crystal clear waters</p>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item beach group cursor-pointer" data-category="beach">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-yellow-100 to-orange-100 flex items-center justify-center">
                        <img src="{{ asset('images/sunset1.jpg') }}" alt="Sunset Views" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="text-center hidden">
                            <h3 class="text-lg font-bold text-orange-700">Sunset Views</h3>
                            <p class="text-sm text-orange-600">Breathtaking sunsets</p>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item beach group cursor-pointer" data-category="beach">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-blue-100 to-cyan-100 flex items-center justify-center">
                            <div class="text-center">
                            <h3 class="text-lg font-bold text-blue-700">Ocean Waves</h3>
                            <p class="text-sm text-blue-600">Gentle waves</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item rooms group cursor-pointer" data-category="rooms">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-purple-700">Luxury Suite</h3>
                                <p class="text-sm text-purple-600">Premium accommodation</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item rooms group cursor-pointer" data-category="rooms">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-green-100 to-emerald-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-green-700">Deluxe Room</h3>
                                <p class="text-sm text-green-600">Comfortable stay</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item rooms group cursor-pointer" data-category="rooms">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-indigo-100 to-blue-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-indigo-700">Beachfront Villa</h3>
                                <p class="text-sm text-indigo-600">Ocean view</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item amenities group cursor-pointer" data-category="amenities">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-red-100 to-pink-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-red-700">Fine Dining</h3>
                                <p class="text-sm text-red-600">Restaurant & Bar</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item amenities group cursor-pointer" data-category="amenities">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-teal-100 to-cyan-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-teal-700">Swimming Pool</h3>
                                <p class="text-sm text-teal-600">Infinity pool</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item amenities group cursor-pointer" data-category="amenities">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-yellow-100 to-amber-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-yellow-700">Spa & Wellness</h3>
                                <p class="text-sm text-yellow-600">Relaxation center</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item activities group cursor-pointer" data-category="activities">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-orange-700">Water Sports</h3>
                                <p class="text-sm text-orange-600">Surfing & diving</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item activities group cursor-pointer" data-category="activities">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-green-100 to-teal-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-green-700">Island Hopping</h3>
                                <p class="text-sm text-green-600">Boat tours</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item activities group cursor-pointer" data-category="activities">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-purple-100 to-indigo-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-purple-700">Fishing</h3>
                                <p class="text-sm text-purple-600">Deep sea fishing</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item beach group cursor-pointer" data-category="beach">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-cyan-100 to-blue-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-cyan-700">Private Island</h3>
                                <p class="text-sm text-cyan-600">Exclusive access</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-item beach group cursor-pointer" data-category="beach">
                <div class="relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:scale-105">
                    <div class="aspect-square bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center">
                            <div class="text-center">
                                <h3 class="text-lg font-bold text-emerald-700">Tropical Garden</h3>
                                <p class="text-sm text-emerald-600">Lush vegetation</p>
                            </div>
                    </div>
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="text-white text-2xl">View</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-teal-600 font-heading mb-4">Gallery Highlights</h2>
        <p class="text-lg text-gray-600 mb-12">Discover what makes Laiya Grande special</p>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-teal-600 mb-2">50+</div>
                <div class="text-sm text-gray-600">Stunning Photos</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-teal-600 mb-2">4</div>
                <div class="text-sm text-gray-600">Photo Categories</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-teal-600 mb-2">360°</div>
                <div class="text-sm text-gray-600">Virtual Tours</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-teal-600 mb-2">24/7</div>
                <div class="text-sm text-gray-600">Photo Updates</div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-gradient-to-br from-teal-600 to-teal-700 text-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold tracking-tight font-heading mb-4">Ready to Experience Paradise?</h2>
        <p class="text-lg text-white/90 mb-8">Book your stay and create your own memories at Laiya Grande</p>
        
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

<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-6xl max-h-full">
        <button id="close-lightbox" class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300 transition-colors z-10">
            ✕
        </button>
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
            <div class="aspect-video relative">
                <img id="lightbox-image" src="" alt="" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                    <div class="text-center text-white">
                        <h3 id="lightbox-title" class="text-3xl font-bold mb-2">Beautiful Beach View</h3>
                        <p id="lightbox-description" class="text-lg">Experience the pristine beauty of Laiya Grande</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.gallery-filter');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const lightbox = document.getElementById('lightbox');
        const closeLightbox = document.getElementById('close-lightbox');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                filterButtons.forEach(btn => {
                    btn.classList.remove('active', 'bg-teal-500', 'text-white');
                    btn.classList.add('bg-white', 'text-gray-700');
                });
                this.classList.add('active', 'bg-teal-500', 'text-white');
                this.classList.remove('bg-white', 'text-gray-700');

                galleryItems.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-category') === filter) {
                        item.style.display = 'block';
                        item.style.animation = 'fadeIn 0.5s ease-in-out';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                const photoImg = this.querySelector('img');
                const photoTitleEl = this.querySelector('h3');
                const photoDescriptionEl = this.querySelector('p');
                
                const lightboxImage = lightbox.querySelector('#lightbox-image');
                const lightboxTitle = lightbox.querySelector('#lightbox-title');
                const lightboxDescription = lightbox.querySelector('#lightbox-description');
                
                if (photoImg && lightboxImage) {
                    lightboxImage.src = photoImg.src;
                    lightboxImage.alt = photoImg.alt;
                }
                if (lightboxTitle && photoTitleEl) lightboxTitle.textContent = photoTitleEl.textContent;
                if (lightboxDescription && photoDescriptionEl) lightboxDescription.textContent = photoDescriptionEl.textContent;
                
                lightbox.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        closeLightbox.addEventListener('click', function() {
            lightbox.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                lightbox.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !lightbox.classList.contains('hidden')) {
                lightbox.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    });

    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection
