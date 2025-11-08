<nav id="sidebar"
    class="bg-[#2C5F5F] w-64 min-h-screen flex flex-col py-6 shadow-lg transition-all duration-300 ease-in-out overflow-hidden">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-6 mb-8">
        <h1 class="text-white text-lg font-semibold nav-text transition-opacity duration-300">Mailbox</h1>
        <button id="toggleSidebarBtn" class="text-white hover:text-yellow-400 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Compose Button -->
    <div class="px-6 mb-6">
        <a href="{{ route('admin.inbox.compose') }}"
            class="w-full bg-blue-600 text-white rounded-md py-2 font-semibold hover:bg-blue-700 transition inline-block text-center">
            <i class="fas fa-pen nav-text"></i>
            <span class="ml-2 nav-text text-sm">Compose</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex flex-col space-y-4 px-6 flex-1 text-white">
        <a href="{{ route('admin.inbox.index') }}" class="flex items-center space-x-3 hover:text-yellow-400 transition">
            <i class="fas fa-inbox text-xl w-6 shrink-0"></i>
            <span class=" gap-2 nav-text text-sm font-medium transition-opacity duration-300 flex items-center">
                Inbox
                <span class="ml-auto bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">12</span>
            </span>
        </a>

        <a href="{{ route('admin.inbox.sent') }}" class="flex items-center space-x-3 hover:text-yellow-400 transition">
            <i class="fas fa-paper-plane text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Sent</span>
        </a>

        <a href="#" class="flex items-center space-x-3 hover:text-yellow-400 transition">
            <i class="fas fa-envelope-open-text text-xl w-6 shrink-0"></i>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">All Mail</span>
        </a>

        <!-- Labels Section -->
        <div class="border-t border-white/20 my-4"></div>
        <div class="text-xs text-gray-300 uppercase tracking-wide nav-text">Labels</div>

        <a href="#" class="flex items-center space-x-3 hover:text-yellow-400 transition">
            <span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Reservations</span>
        </a>

        <a href="#" class="flex items-center space-x-3 hover:text-yellow-400 transition">
            <span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Bookings</span>
        </a>

        <a href="#" class="flex items-center space-x-3 hover:text-yellow-400 transition">
            <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
            <span class="nav-text text-sm font-medium transition-opacity duration-300">Urgent</span>
        </a>
    </div>
</nav>

<!-- Toggle Script -->
<script>
    const toggleBtn = document.getElementById('toggleSidebarBtn');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        const isExpanded = sidebar.classList.contains('w-64');
        const texts = sidebar.querySelectorAll('.nav-text');

        if (isExpanded) {
            texts.forEach(text => text.classList.add('opacity-0'));
            requestAnimationFrame(() => {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
            });
        } else {
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

<!-- Styles -->
<style>
    #sidebar {
        transition: width 0.3s ease-in-out;
        will-change: width;
    }

    #sidebar .nav-text {
        transition: opacity 0.2s ease-in-out;
        white-space: nowrap;
        overflow: hidden;
    }

    #sidebar .fa {
        min-width: 24px;
        text-align: center;
    }
</style>
