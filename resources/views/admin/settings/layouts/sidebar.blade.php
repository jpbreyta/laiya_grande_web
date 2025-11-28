<nav id="sidebar"
    class="bg-[#2C5F5F] w-64 min-h-screen flex flex-col py-6 shadow-lg transition-all duration-300 ease-in-out overflow-hidden">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-6 mb-8">
        <a href="{{ route('admin.settings.index') }}">
            <h1 class="text-white text-lg font-semibold nav-text transition-opacity duration-300">Settings</h1>
        </a>
        <button id="toggleSettingsSidebarBtn" class="text-white hover:text-yellow-400 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Settings Tabs -->
    <div class="flex flex-col space-y-6 px-6 flex-1 text-white">

        <a href="{{ route('admin.settings.general') }}"
            class="flex items-center space-x-3 hover:text-yellow-400 transition">
            <i class="fas fa-cog text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">General Settings</span>
        </a>

        <a href="{{ route('admin.settings.communication') }}"
            class="flex items-center space-x-3 hover:text-yellow-400 transition">
            <i class="fas fa-comments text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Communication</span>
        </a>

    </div>
</nav>

<!-- Sidebar Toggle Script -->
<script>
    const toggleBtn = document.getElementById('toggleSettingsSidebarBtn');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        const isCollapsed = sidebar.classList.contains('w-64');

        if (isCollapsed) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');

            document.querySelectorAll('.nav-text').forEach(el => {
                el.classList.add('opacity-0', 'pointer-events-none');
            });
        } else {
            sidebar.classList.add('w-64');
            sidebar.classList.remove('w-20');

            document.querySelectorAll('.nav-text').forEach(el => {
                el.classList.remove('opacity-0', 'pointer-events-none');
            });
        }
    });
</script>
