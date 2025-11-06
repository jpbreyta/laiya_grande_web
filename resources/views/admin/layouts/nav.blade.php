<nav id="sidebar"
    class="bg-[#2C5F5F] w-64 min-h-screen flex flex-col py-6 shadow-lg transition-all duration-300 ease-in-out overflow-hidden">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-6 mb-8">
        <h1 class="text-white text-lg font-semibold nav-text transition-opacity duration-300">Laiya Admin</h1>
        <button id="toggleSidebarBtn" class="text-white hover:text-yellow-400 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
<div class="px-6 mb-6">
        <a href="{{ route('admin.new.index')}}" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-plus text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">New</span>
        </a>
</div>

    <!-- Navigation Links -->
    <div class="flex flex-col space-y-6 px-6 flex-1">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-home text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Dashboard</span>
        </a>
        <a href="{{ route('admin.booking.index') }}"
            class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-calendar-check text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Manage Bookings</span>
        </a>
        <a href="{{ route('admin.reservation.index') }}"
            class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-calendar-check text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Manage Reservation</span>
        </a>
        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-user-check text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Guest & Booking</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-seedling text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Foods</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-bed text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Room & Cottage</span>
        </a>

        <a href="{{ route('admin.packages.index') }}"
            class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-box text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Packages</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-credit-card text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Payments</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-chart-pie text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Analytics & Reports</span>
        </a>

        <a href="{{ route('admin.qr.scanner')}}" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-qrcode text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Documents & QR</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-percentage text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Promos & Discounts</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-cog text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">System Settings</span>
        </a>
    </div>

    <!-- Divider -->
    <div class="border-t border-white/20 my-6 mx-6"></div>

    <!-- Footer -->
    <div class="px-6">
        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
            @csrf
            <button type="submit"
                class="flex items-center space-x-3 text-white hover:text-yellow-400 transition w-full text-left">
                <i class="fas fa-sign-out-alt text-xl w-6 shrink-0"></i>
                <span class="nav-text text-sm font-medium transition-opacity duration-300">Logout</span>
            </button>
        </form>
    </div>
</nav>

<script>
    const toggleBtn = document.getElementById('toggleSidebarBtn');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        const isExpanded = sidebar.classList.contains('w-64');
        const texts = sidebar.querySelectorAll('.nav-text');

        if (isExpanded) {
            // Collapse: fade text out first to avoid jitter, then shrink width
            texts.forEach(text => text.classList.add('opacity-0'));
            // next frame to ensure opacity transition begins before width change
            requestAnimationFrame(() => {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
            });
        } else {
            // Expand: grow width first, then fade text back in after width transition ends
            const onTransitionEnd = (e) => {
                if (e.propertyName === 'width') {
                    texts.forEach(text => text.classList.remove('opacity-0'));
                    sidebar.removeEventListener('transitionend', onTransitionEnd);
                }
            };
            sidebar.addEventListener('transitionend', onTransitionEnd);
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
        }
    });
</script>

<style>
    /* Smooth transition for sidebar width and text visibility */
    #sidebar {
        transition: width 0.3s ease-in-out;
        will-change: width;
    }
    /* Ensure nav text fades smoothly and doesn't wrap during collapse */
    #sidebar .nav-text {
        transition: opacity 0.2s ease-in-out;
        white-space: nowrap;
        overflow: hidden;
    }
</style>
