<header class="bg-white shadow-sm border-b border-gray-200 px-6 py-3">
    <div class="flex items-center justify-between">

        {{-- Search Bar --}}
        <div class="flex-1 max-w-md">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text" 
                    placeholder="Search"
                    class="search-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg 
                           focus:outline-none focus:ring-2 focus:ring-[var(--teal-primary)] focus:border-transparent"
                >
            </div>
        </div>

        {{-- Right Buttons --}}
        <div class="flex items-center space-x-4 ml-6">

            {{-- New Button --}}
            <button 
                class="flex items-center gap-2 bg-[var(--teal-primary)] hover:bg-[var(--teal-dark)] 
                       text-white px-4 py-2 rounded-lg font-medium transition"
            >
                <i class="fas fa-plus text-sm"></i>
                <span>New</span>
            </button>

            {{-- Notification Button --}}
            <button 
                class="relative flex items-center justify-center w-10 h-10 
                       text-gray-600 hover:text-[var(--teal-primary)] transition-colors"
            >
                <i class="fas fa-bell text-xl"></i>
                <span 
                    class="notification-badge absolute -top-1 -right-1 bg-[var(--accent-red)] text-white 
                           text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold"
                >
                    3
                </span>
            </button>
        </div>
    </div>
</header>
