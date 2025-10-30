<nav id="sidebar"
    class="bg-[#2C5F5F] w-64 min-h-screen flex flex-col py-6 shadow-lg transition-all duration-300 ease-in-out">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-6 mb-8">
        <h1 class="text-white text-lg font-semibold nav-text">Laiya Admin</h1>
        <button id="toggleSidebarBtn" class="text-white hover:text-yellow-400 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-col space-y-6 px-6 flex-1">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-home text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Dashboard</span>
        </a>
        <a href="{{ route('admin.booking.index') }}"
            class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-calendar-check text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Manage Bookings</span>
        </a>
        <a href="{{ route('admin.reservation.index') }}"
            class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-calendar-check text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Manage Reservation</span>
        </a>
        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-user-check text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Guest & Booking</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-bed text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Room & Cottage</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-box text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Packages</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-credit-card text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Payments</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-chart-pie text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Analytics & Reports</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-qrcode text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Documents & QR</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-percentage text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Promos & Discounts</span>
        </a>

        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-cog text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">System Settings</span>
        </a>
    </div>

    <!-- Divider -->
    <div class="border-t border-white/20 my-6 mx-6"></div>

    <!-- Footer -->
    <div class="px-6">
        <a href="#" class="flex items-center space-x-3 text-white hover:text-yellow-400 transition">
            <i class="fas fa-user text-xl w-6"></i>
            <span class="nav-text text-sm font-medium">Profile / Logout</span>
        </a>
    </div>
</nav>

<script>
    const toggleBtn = document.getElementById('toggleSidebarBtn');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('w-64'); // expanded
        sidebar.classList.toggle('w-20'); // collapsed

        const texts = sidebar.querySelectorAll('.nav-text');
        texts.forEach(text => {
            text.classList.toggle('hidden');
        });
    });
</script>

<style>
    /* Smooth transition for sidebar width and text visibility */
    #sidebar {
        transition: width 0.3s ease-in-out;
    }
</style>
