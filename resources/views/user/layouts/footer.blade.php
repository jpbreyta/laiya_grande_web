<footer class="bg-white border-t border-slate-100 pt-20 pb-12 font-poppins">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 mb-16">
            
            <div class="lg:col-span-5 flex flex-col justify-between h-full">
                <div class="space-y-6">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                        <img src="{{ asset('images/laiyagrande-logo.png') }}" alt="Laiya Grande" class="h-12 w-auto object-contain transition-transform duration-500 group-hover:scale-105">
                        <span class="font-playfair text-2xl font-bold text-teal-900">Laiya Grande</span>
                    </a>
                    <p class="text-slate-500 text-sm leading-7 font-light max-w-sm">
                        Escape to paradise. We provide a sanctuary where nature's beauty meets modern luxury, ensuring your stay is nothing short of extraordinary.
                    </p>
                </div>

                <div class="flex gap-4 mt-8 lg:mt-0">
                    <a href="https://web.facebook.com/laiyagrande" target="_blank" class="w-11 h-11 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-teal-600 hover:text-white hover:-translate-y-1 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-teal-600/30">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-11 h-11 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-teal-600 hover:text-white hover:-translate-y-1 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-teal-600/30">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-11 h-11 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-teal-600 hover:text-white hover:-translate-y-1 transition-all duration-300 shadow-sm hover:shadow-lg hover:shadow-teal-600/30">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>

            <div class="hidden lg:block lg:col-span-1"></div>

            <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-10">
                <div>
                    <h4 class="text-sm font-bold text-teal-900 uppercase tracking-widest mb-6 relative inline-block after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-8 after:h-0.5 after:bg-teal-200">
                        Explore
                    </h4>
                    <ul class="space-y-4">
                        <li>
                            <a href="{{ url('/') }}" class="text-slate-500 text-sm hover:text-teal-600 transition-colors flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full bg-slate-300 group-hover:bg-teal-500 transition-colors"></span>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/rooms') }}" class="text-slate-500 text-sm hover:text-teal-600 transition-colors flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full bg-slate-300 group-hover:bg-teal-500 transition-colors"></span>
                                Accommodations
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/gallery') }}" class="text-slate-500 text-sm hover:text-teal-600 transition-colors flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full bg-slate-300 group-hover:bg-teal-500 transition-colors"></span>
                                Gallery
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/location') }}" class="text-slate-500 text-sm hover:text-teal-600 transition-colors flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full bg-slate-300 group-hover:bg-teal-500 transition-colors"></span>
                                Location
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-teal-900 uppercase tracking-widest mb-6 relative inline-block after:content-[''] after:absolute after:-bottom-2 after:left-0 after:w-8 after:h-0.5 after:bg-teal-200">
                        Contact Us
                    </h4>
                    <ul class="space-y-5 text-sm text-slate-500">
                        <li class="flex items-start gap-3">
                            <div class="mt-1 min-w-[20px] text-teal-500">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <span class="leading-relaxed">Laiya, San Juan, Batangas,<br>Philippines 4226</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="min-w-[20px] text-teal-500">
                                <i class="fas fa-phone"></i>
                            </div>
                            <a href="tel:09630337629" class="hover:text-teal-600 font-medium transition-colors">0963 033 7629</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="min-w-[20px] text-teal-500">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <a href="mailto:laiyagrandebr22@gmail.com" class="hover:text-teal-600 transition-colors break-all">laiyagrandebr22@gmail.com</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-slate-400 font-light">
                &copy; {{ date('Y') }} Laiya Grande Beach Resort. All rights reserved.
            </p>
            <div class="flex gap-6">
                <a href="#" class="text-xs text-slate-400 hover:text-teal-600 transition-colors">Privacy Policy</a>
                <a href="#" class="text-xs text-slate-400 hover:text-teal-600 transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<button id="backToTopBtn" class="fixed bottom-8 right-8 z-50 translate-y-20 opacity-0 transition-all duration-500 ease-out w-12 h-12 rounded-full bg-white text-teal-600 shadow-xl border border-slate-50 flex items-center justify-center hover:bg-teal-600 hover:text-white hover:-translate-y-1 hover:shadow-2xl focus:outline-none" aria-label="Back to top">
    <i class="fas fa-chevron-up text-sm"></i>
</button>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const backToTopBtn = document.getElementById('backToTopBtn');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.remove('translate-y-20', 'opacity-0');
            } else {
                backToTopBtn.classList.add('translate-y-20', 'opacity-0');
            }
        });
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>