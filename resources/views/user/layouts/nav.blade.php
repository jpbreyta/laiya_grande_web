<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    /* Custom font-styles */
    .font-poppins { font-family: 'Poppins', sans-serif; }
    .font-playfair { font-family: 'Playfair Display', serif; }

    /* Soft Glassmorphism */
    .nav-glass {
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(241, 245, 249, 0.6);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    /* Scrolled state */
    .nav-scrolled {
        background-color: rgba(255, 255, 255, 0.98);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>

<nav id="site-nav" 
     x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 20;
            });
        }
     }"
     :class="scrolled ? 'nav-scrolled py-2' : 'nav-glass py-4'"
     class="fixed top-0 w-full z-[100] transition-all duration-500 ease-in-out font-poppins">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center transition-all duration-300">

            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="group flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center text-[#0E7C7B] drop-shadow-sm group-hover:scale-105 transition-transform duration-300">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                            <path d="M2.75 12.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                            <path d="M2.75 8.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.6" />
                            <path d="M2.75 16.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.6" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="font-playfair text-xl font-bold text-slate-800 tracking-tight group-hover:text-[#0E7C7B] transition-colors duration-300">
                            Laiya Grande
                        </span>
                        <span class="text-[9px] font-poppins tracking-[0.2em] text-slate-400 uppercase mt-0.5 group-hover:text-[#0E7C7B] transition-colors duration-300">
                            Beach Resort
                        </span>
                    </div>
                </a>
            </div>

            <div class="hidden xl:flex items-center gap-8 text-sm font-medium text-slate-500 mx-auto whitespace-nowrap">
                
                <a href="{{ url('/') }}" class="hover:text-[#0E7C7B] transition-colors relative py-2">
                    Home
                </a>
                
                <a href="{{ route('search.index') }}" class="hover:text-[#0E7C7B] transition-colors relative py-2">
                    My Reservations
                </a>

                <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="flex items-center gap-1 hover:text-[#0E7C7B] transition-colors py-2 focus:outline-none">
                        About Us
                        <i class="fa-solid fa-caret-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-98"
                         class="absolute top-full left-0 mt-2 w-48 bg-white/95 backdrop-blur-sm rounded-lg shadow-xl border border-slate-100 py-1 z-50 overflow-hidden"
                         style="display: none;">
                        
                        <a href="{{ route('contact') }}" class="block px-4 py-2.5 text-slate-600 hover:bg-[#0E7C7B]/5 hover:text-[#0E7C7B] transition-colors duration-200">
                            Contact Us
                        </a>
                        <a href="{{ url('/gallery') }}" class="block px-4 py-2.5 text-slate-600 hover:bg-[#0E7C7B]/5 hover:text-[#0E7C7B] transition-colors duration-200">
                            Gallery
                        </a>
                    </div>
                </div>
            </div>

            <div class="hidden lg:flex items-center gap-6 flex-shrink-0">
                <div class="flex items-center gap-4 text-slate-400">
                    <a href="https://www.facebook.com/laiyagrande/" class="hover:text-[#0E7C7B] hover:scale-110 transition-transform duration-300"><i class="fa-brands fa-facebook text-lg"></i></a>
                    <a href="https://www.instagram.com/laiyagrande/" class="hover:text-[#0E7C7B] hover:scale-110 transition-transform duration-300"><i class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="https://www.tiktok.com/@laiyagrandebeachresort" class="hover:text-[#0E7C7B] hover:scale-110 transition-transform duration-300"><i class="fa-brands fa-tiktok text-lg"></i></a>
                </div>

                <a href="{{ route('user.rooms.index') }}" 
                   class="bg-[#0E7C7B] text-white px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 font-medium text-sm transform hover:scale-105">
                    Book Now
                </a>
            </div>

            <div class="lg:hidden flex items-center gap-4 ml-auto">
                <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-600 hover:text-[#0E7C7B] transition-colors">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span x-show="totalCount > 0" class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-[#0E7C7B] ring-2 ring-white"></span>
                </a>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-800 hover:text-[#0E7C7B] transition-colors focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden absolute top-full left-0 w-full bg-white border-t border-slate-100 shadow-xl py-4 px-6 flex flex-col gap-4 max-h-[80vh] overflow-y-auto"
         style="display: none;">
         
         <a href="{{ url('/') }}" class="text-sm font-medium text-slate-600 hover:text-[#0E7C7B] transition-colors">Home</a>
         <a href="{{ route('search.index') }}" class="text-sm font-medium text-slate-600 hover:text-[#0E7C7B] transition-colors">My Reservations</a>
         
         <div x-data="{ subOpen: false }">
            <button @click="subOpen = !subOpen" class="flex items-center justify-between w-full text-sm font-medium text-slate-600 hover:text-[#0E7C7B] transition-colors">
                About Us <i class="fa-solid fa-caret-down text-xs transition-transform" :class="subOpen ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="subOpen" class="pl-4 mt-2 flex flex-col gap-3 border-l border-slate-200 ml-1">
                <a href="{{ route('contact') }}" class="text-sm text-slate-500 hover:text-[#0E7C7B] transition-colors">Contact Us</a>
                <a href="{{ url('/location') }}" class="text-sm text-slate-500 hover:text-[#0E7C7B] transition-colors">Gallery</a>
            </div>
         </div>

         <a href="{{ route('user.rooms.index') }}" class="w-full text-center bg-[#0E7C7B] text-white font-medium text-sm py-3 rounded-lg shadow-md mt-2 hover:bg-[#095a59] transition-colors">
            Book Your Stay
         </a>
    </div>
</nav>