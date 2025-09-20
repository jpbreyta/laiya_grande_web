<nav class="bg-gradient-to-r from-teal-500 via-cyan-500 to-sky-500 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/laiyagrande.png') }}" alt="Resort Logo" class="h-10 w-10 rounded-full shadow-md">
                    <span class="text-white font-bold text-xl tracking-wide">Laiya Grande</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ url('/') }}" class="text-white font-medium hover:text-yellow-200 transition">Home</a>
                <a href="{{ url('/rooms') }}" class="text-white font-medium hover:text-yellow-200 transition">Rooms</a>
                <a href="{{ url('/gallery') }}" class="text-white font-medium hover:text-yellow-200 transition">Gallery</a>
                <a href="{{ url('/contact') }}" class="text-white font-medium hover:text-yellow-200 transition">Contact</a>
                <a href="{{ url('/register') }}" 
                class="ml-4 inline-block bg-blue-600 text-white font-medium px-6 py-2 rounded-lg hover:bg-blue-700 hover:text-yellow-200 transition">
                    Reserve Now!
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="nav-toggle" class="text-white focus:outline-none">
                    ☰
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown -->
    <div id="nav-menu" class="hidden md:hidden bg-teal-600 px-4 pb-4 space-y-2">
        <a href="{{ url('/') }}" class="block text-white font-medium hover:text-yellow-200 transition">Home</a>
        <a href="{{ url('/rooms') }}" class="block text-white font-medium hover:text-yellow-200 transition">Rooms</a>
        <a href="{{ url('/amenities') }}" class="block text-white font-medium hover:text-yellow-200 transition">Amenities</a>
        <a href="{{ url('/gallery') }}" class="block text-white font-medium hover:text-yellow-200 transition">Gallery</a>
        <a href="{{ url('/contact') }}" class="block text-white font-medium hover:text-yellow-200 transition">Contact</a>
    </div>
</nav>

<script>
    // Simple toggle for mobile menu
    document.getElementById('nav-toggle').addEventListener('click', function () {
        document.getElementById('nav-menu').classList.toggle('hidden');
    });
</script>