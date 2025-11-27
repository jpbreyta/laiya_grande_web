<nav id="site-nav"
    class="sticky top-0 z-[100] bg-white/95 backdrop-blur-md border-b border-slate-100 transition-all duration-500 font-poppins">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div id="nav-inner" class="flex justify-between items-center h-16 transition-all duration-500">
            
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="group flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center text-teal-600 group-hover:text-teal-800 transition-colors duration-300">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                            <path d="M2.75 12.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                            <path d="M2.75 8.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.6"/>
                            <path d="M2.75 16.5c2.761 0 3.5 2.5 6.25 2.5s3.489-2.5 6.25-2.5c2.761 0 3.5 2.5 6.25 2.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity="0.6"/>
                        </svg>
                    </div>
                    
                    <div class="flex flex-col justify-center">
                        <span class="font-playfair text-xl font-bold text-teal-900 tracking-tight group-hover:text-teal-700 transition-colors">
                            Laiya Grande
                        </span>
                        <span class="text-[9px] font-poppins tracking-[0.2em] text-slate-400 uppercase group-hover:text-teal-500 transition-colors mt-0.5">
                            Beach Resort
                        </span>
                    </div>
                </a>
            </div>

            <div class="hidden xl:flex items-center gap-8 text-sm font-medium text-slate-500 mx-auto whitespace-nowrap">
                <a href="{{ url('/') }}" class="hover:text-teal-700 transition-colors relative group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/rooms') }}" class="hover:text-teal-700 transition-colors relative group">
                    Rooms
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/gallery') }}" class="hover:text-teal-700 transition-colors relative group">
                    Gallery
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ url('/about-us') }}" class="hover:text-teal-700 transition-colors relative group">
                    About Us
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('contact') }}" class="hover:text-teal-700 transition-colors relative group">
                    Contact
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('search.index') }}" class="hover:text-teal-700 transition-colors relative group">
                    My Reservations
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>

            <div class="hidden lg:flex items-center gap-6 flex-shrink-0">
                
                <div class="flex items-center gap-4 text-slate-400">
                    <a href="https://www.facebook.com/laiyagrande" target="_blank" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i class="fa-brands fa-facebook text-lg"></i></a>
                    <a href="https://www.instagram.com/laiyagrande" target="_blank" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i class="fa-brands fa-instagram text-lg"></i></a>
                    <a href="https://www.tiktok.com/@laiyagrandebeachresort" target="_blank" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i class="fa-brands fa-tiktok text-lg"></i></a>
                    <a href="mailto:laiyagrandebr22@gmail.com" class="hover:text-teal-600 transition-transform hover:-translate-y-0.5"><i class="fa-solid fa-envelope text-lg"></i></a>
                </div>

                <div class="h-6 w-px bg-slate-200"></div>

                <div class="flex items-center gap-5">
                    
                    <div x-data="{ open: false }" class="relative z-50" @click.outside="open = false" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="relative group p-1 text-slate-500 hover:text-teal-600 transition-colors flex items-center">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                            <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-yellow-400 text-[10px] font-bold text-teal-900 border border-white">3</span>
                        </button>
                        
                        <div x-show="open" 
                             class="absolute right-0 top-full mt-4 w-[400px] origin-top-right z-40"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                             style="display: none;"> 
                             <div class="absolute -top-2 right-[6px] w-4 h-4 bg-white transform rotate-45 border-l border-t border-slate-200 z-50"></div>
                    
                            <div class="relative bg-white border border-slate-200 rounded-2xl shadow-2xl shadow-slate-300/50 z-40">
                                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white rounded-t-2xl relative z-10">
                                    <div>
                                        <h4 class="text-slate-900 font-bold text-lg tracking-tight">Your Stay</h4>
                                        <p class="text-xs text-slate-400 font-medium">Order #TR-8839</p>
                                    </div>
                                    <button @click="open = false; $event.stopPropagation();" class="p-2 rounded-full hover:bg-slate-50 text-slate-300 hover:text-slate-500 transition-colors">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>
                    
                                <div class="max-h-[60vh] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200 scrollbar-track-transparent">
                                    <div class="group relative flex gap-4 p-5 hover:bg-slate-50 transition-colors duration-200 border-b border-slate-50 last:border-0">
                                        <div class="pt-1 flex-shrink-0">
                                             <input type="checkbox" checked class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500 cursor-pointer accent-teal-600">
                                        </div>
                                        <div class="relative w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 border border-slate-100 shadow-sm">
                                            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Room" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                                            <div>
                                                <div class="flex justify-between items-start">
                                                    <h5 class="text-sm font-bold text-slate-800 leading-tight truncate pr-2">Hermosa Suite</h5>
                                                    <span class="text-sm font-bold text-slate-900">₱2,800</span>
                                                </div>
                                                <p class="text-xs text-slate-500 mt-1">2 nights · 5–6 Guests</p>
                                            </div>
                                            <div class="flex items-center gap-2 mt-2">
                                                 <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    Instant Confirm
                                                 </span>
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                    
                                <div class="bg-white border-t border-slate-100 p-5 rounded-b-2xl">
                                    <div class="flex justify-between items-end mb-4">
                                        <div>
                                                <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mb-1">Total Due</p>
                                                <p class="text-xs text-emerald-600 font-medium">Incl. taxes and fees</p>
                                        </div>
                                        <span class="text-2xl font-bold text-slate-800 tracking-tight">₱4,999</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button class="px-4 py-3 rounded-xl border-2 border-slate-200 text-slate-600 text-sm font-bold hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800 transition focus:ring-2 focus:ring-slate-200 focus:outline-none">
                                            Reserve
                                        </button>
                                        <button class="px-4 py-3 rounded-xl bg-teal-600 text-white text-sm font-bold hover:bg-teal-700 transition shadow-md shadow-teal-600/20 focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 focus:outline-none items-center justify-center flex">
                                            Book Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('user.rooms.index')}}" class="flex items-center justify-center bg-teal-900 text-white px-7 py-2.5 rounded-full text-base font-medium hover:bg-teal-800 transition-all shadow-sm hover:shadow-md whitespace-nowrap">
                        Book Now
                    </a>
                </div>
            </div>

            <div class="lg:hidden flex items-center gap-4 ml-auto">
                 <a href="#" class="relative p-2 text-slate-600">
                    <i class="fa-solid fa-cart-shopping text-xl"></i>
                    <span class="absolute top-0 right-0 h-3 w-3 rounded-full bg-teal-500 ring-2 ring-white"></span>
                </a>
                <button id="nav-toggle" class="p-2 text-slate-800 hover:text-teal-600 transition-colors focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>

        </div>
    </div>

    <div id="nav-menu" class="hidden lg:hidden absolute top-full left-0 w-full bg-white border-b border-slate-100 shadow-xl overflow-hidden transition-all duration-300 origin-top">
        <div class="p-6 space-y-6">
            <div class="flex flex-col space-y-4 font-medium text-slate-600 text-lg">
                <a href="{{ url('/') }}" class="flex items-center justify-between group">
                    Home <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 text-teal-500 transition-opacity"></i>
                </a>
                 <a href="{{ url('/rooms') }}" class="flex items-center justify-between group">
                    Rooms <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 text-teal-500 transition-opacity"></i>
                </a>
                <a href="{{ url('/gallery') }}" class="flex items-center justify-between group">
                    Gallery <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 text-teal-500 transition-opacity"></i>
                </a>
                <a href="{{ url('/about-us') }}" class="flex items-center justify-between group">
                    About Us <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 text-teal-500 transition-opacity"></i>
                </a>
                <a href="{{ route('contact') }}" class="flex items-center justify-between group">
                    Contact <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 text-teal-500 transition-opacity"></i>
                </a>
                <a href="{{ route('search.index') }}" class="flex items-center justify-between group">
                    My Reservations <i class="fa-solid fa-chevron-right text-xs opacity-0 group-hover:opacity-100 text-teal-500 transition-opacity"></i>
                </a>
            </div>
            
            <div class="pt-6 border-t border-slate-100">
                <a href="{{ route('user.rooms.index')}}" class="block w-full py-3 bg-teal-900 text-white text-center rounded-lg font-medium shadow-lg shadow-teal-900/20 text-base">
                    Book Your Stay
                </a>
            </div>

             <div class="flex justify-center gap-6 pt-2">
                <a href="#" class="text-slate-400 hover:text-teal-600 text-2xl"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="text-slate-400 hover:text-teal-600 text-2xl"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="text-slate-400 hover:text-teal-600 text-2xl"><i class="fa-brands fa-tiktok"></i></a>
                <a href="#" class="text-slate-400 hover:text-teal-600 text-2xl"><i class="fa-solid fa-envelope"></i></a>
            </div>
        </div>
    </div>
</nav>

<script>
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    const navIcon = navToggle.querySelector('i');

    navToggle.addEventListener('click', () => {
        navMenu.classList.toggle('hidden');
        if (navMenu.classList.contains('hidden')) {
            navIcon.classList.remove('fa-xmark');
            navIcon.classList.add('fa-bars');
        } else {
            navIcon.classList.remove('fa-bars');
            navIcon.classList.add('fa-xmark');
        }
    });

    const nav = document.getElementById('site-nav');
    const inner = document.getElementById('nav-inner');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {

            nav.classList.add('shadow-lg', 'bg-white/98');

            nav.classList.remove('shadow-sm');

            inner.classList.remove('h-16');
            inner.classList.add('h-14'); 
        } else {
            nav.classList.remove('shadow-lg', 'bg-white/98');
            nav.classList.add('shadow-sm'); 
            inner.classList.remove('h-14');
            inner.classList.add('h-16'); 
        }
    });
</script>