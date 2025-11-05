<nav id="site-nav"
    class="sticky top-0 z-50 bg-white/80 backdrop-blur supports-[backdrop-filter]:bg-white/60 border-b border-slate-200/60 transition-all duration-300 dark:bg-slate-900/70 dark:supports-[backdrop-filter]:bg-slate-900/60 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="nav-inner" class="flex justify-between h-14 items-center transition-all duration-300">

            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img id="brand-logo" src="{{ asset('images/laiyagrande-logo.png') }}" alt="Laiya Grande Beach Resort"
                        class="h-10 w-auto shadow-sm transition-all duration-300">
                    <span id="brand-text"
                        class="text-slate-900 font-semibold text-lg tracking-tight font-heading transition-all duration-300 dark:text-slate-100">Laiya
                        Grande</span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ url('/') }}"
                    class="text-slate-700 dark:text-slate-200 font-medium hover:text-teal-700 dark:hover:text-teal-400 hover:underline underline-offset-4 decoration-teal-400/60 transition">Home</a>
                <a href="{{ url('/gallery') }}"
                    class="text-slate-700 dark:text-slate-200 font-medium hover:text-teal-700 dark:hover:text-teal-400 hover:underline underline-offset-4 decoration-teal-400/60 transition">Gallery</a>
                <a href="{{ url('/location') }}"
                    class="text-slate-700 dark:text-slate-200 font-medium hover:text-teal-700 dark:hover:text-teal-400 hover:underline underline-offset-4 decoration-teal-400/60 transition">Location</a>
                <a href="{{ route('user.reservation.index') }}"
                    class="text-slate-700 dark:text-slate-200 font-medium hover:text-teal-700 dark:hover:text-teal-400 hover:underline underline-offset-4 decoration-teal-400/60 transition">My
                    Reservations</a>
                <a id="nav-cta" href="{{ route('booking.index') }}"
                    class="ml-2 inline-flex items-center rounded-full border border-transparent bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-4 py-1.5 font-medium shadow-sm hover:from-teal-600 hover:to-cyan-600 transition-all duration-300 dark:from-teal-500 dark:to-cyan-500 dark:hover:from-teal-400 dark:hover:to-cyan-400">
                    Reserve Now
                </a>
            </div>

            <div class="md:hidden">
                <button id="nav-toggle" class="text-slate-700 dark:text-slate-200 focus:outline-none"
                    aria-expanded="false" aria-controls="nav-menu">
                    Menu
                </button>
            </div>
        </div>
    </div>

    <div id="nav-menu"
        class="hidden md:hidden absolute inset-x-0 top-full bg-white/90 backdrop-blur supports-[backdrop-filter]:bg-white/80 border-b border-slate-200 shadow-sm px-4 pb-4 pt-3 space-y-2 dark:bg-slate-900/80 dark:supports-[backdrop-filter]:bg-slate-900/70 dark:border-slate-800">
        <a href="{{ url('/') }}"
            class="block text-slate-700 dark:text-slate-200 font-medium hover:text-slate-900 dark:hover:text-white transition">Home</a>
        <a href="{{ url('/gallery') }}"
            class="block text-slate-700 dark:text-slate-200 font-medium hover:text-slate-900 dark:hover:text-white transition">Gallery</a>
        <a href="{{ url('/contact') }}"
            class="block text-slate-700 dark:text-slate-200 font-medium hover:text-slate-900 dark:hover:text-white transition">Location</a>
        <a href="{{ route('user.reserve.index') }}"
            class="block text-slate-700 dark:text-slate-200 font-medium hover:text-slate-900 dark:hover:text-white transition">My
            Reservations</a>
    </div>
</nav>

<script>
    document.getElementById('nav-toggle').addEventListener('click', function() {
        const menu = document.getElementById('nav-menu');
        const isHidden = menu.classList.toggle('hidden');
        this.setAttribute('aria-expanded', String(!isHidden));
    });

    (function() {
        const nav = document.getElementById('site-nav');
        const inner = document.getElementById('nav-inner');
        const logo = document.getElementById('brand-logo');
        const text = document.getElementById('brand-text');
        const cta = document.getElementById('nav-cta');
        const activate = () => {
            nav.classList.add('bg-white/95', 'shadow-sm', 'border-slate-200');
            inner.classList.add('h-12');
            if (logo) logo.classList.add('h-7', 'w-7');
            if (text) text.classList.add('text-base');
            if (cta) cta.classList.add('from-teal-600', 'to-cyan-600');
        };
        const deactivate = () => {
            nav.classList.remove('bg-white/95', 'shadow-sm');
            inner.classList.remove('h-12');
            if (logo) logo.classList.remove('h-7', 'w-7');
            if (text) text.classList.remove('text-base');
            if (cta) cta.classList.remove('from-teal-600', 'to-cyan-600');
        };
        const onScroll = () => {
            if (window.scrollY > 8) {
                activate();
            } else {
                deactivate();
            }
        };
        window.addEventListener('scroll', onScroll, {
            passive: true
        });
        onScroll();
    })();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
