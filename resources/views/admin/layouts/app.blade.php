<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Admin Dashboard' }} - Laiya Grande</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --teal-primary: #2C5F5F;
            --teal-dark: #1A4A4A;
            --blue-dark: #1E3A5F;
            --accent-yellow: #F4D03F;
            --accent-red: #E74C3C;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .main-content::-webkit-scrollbar {
            width: 6px;
        }

        .main-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .main-content::-webkit-scrollbar-thumb {
            background: var(--teal-primary);
            border-radius: 3px;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background: var(--teal-dark);
        }

        .dashboard-card {
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Notification dropdown */
        .notification-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 1000;
            width: 320px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-dropdown.show {
            display: block;
        }

        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .notification-item:hover {
            background-color: #f9fafb;
        }

        .notification-item.unread {
            background-color: #fef3f2;
        }

        .notification-item:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar (fixed width) --}}
        @include('admin.layouts.nav')

        {{-- Main Section --}}
        <div class="flex flex-col flex-1 h-full">

            {{-- Header (now beside nav, top of content) --}}
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" placeholder="Search"
                            class="search-input w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--teal-primary)] focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex items-center space-x-4">

                    <div class="relative">
                        <button
                            class="relative text-gray-600 hover:text-[var(--teal-primary)] transition-colors notification-btn">
                            <i class="fas fa-bell text-xl"></i>
                            @if (isset($unreadNotifications) && $unreadNotifications > 0)
                                <span
                                    class="notification-badge absolute -top-1 -right-1 bg-[var(--accent-red)] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unreadNotifications }}</span>
                            @endif
                        </button>

                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
                            </div>
                            <div id="notificationList">
                                @if (isset($recentNotifications) && $recentNotifications->count() > 0)
                                    @foreach ($recentNotifications as $notification)
                                        <div class="notification-item {{ $notification->read ? '' : 'unread' }}"
                                            data-id="{{ $notification->id }}">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                        style="background-color: {{ $notification->type === 'booking' ? '#2C5F5F' : ($notification->type === 'reservation' ? '#1E3A5F' : '#F4D03F') }}">
                                                        <i
                                                            class="fas {{ $notification->type === 'booking' ? 'fa-user-plus' : ($notification->type === 'reservation' ? 'fa-calendar-plus' : 'fa-info') }} text-white text-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-800">
                                                        {{ $notification->title }}</p>
                                                    <p class="text-sm text-gray-600 truncate">
                                                        {{ $notification->message }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                                @if (!$notification->read)
                                                    <div class="flex-shrink-0">
                                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="p-8 text-center">
                                        <i class="fas fa-bell-slash text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-500">No notifications</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Body --}}
            <main class="main-content flex-1 bg-gray-50 p-6 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', e => {
                    console.log('Searching for:', e.target.value);
                });
            }

            // Notification dropdown functionality
            const notificationBtn = document.querySelector('.notification-btn');
            const notificationDropdown = document.getElementById('notificationDropdown');

            if (notificationBtn && notificationDropdown) {
                notificationBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    notificationDropdown.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                        notificationDropdown.classList.remove('show');
                    }
                });

                // Mark notification as read when clicked
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const notificationId = this.dataset.id;
                        if (notificationId) {
                            fetch(`/admin/notifications/${notificationId}/mark-read`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')?.getAttribute(
                                        'content') || ''
                                }
                            }).then(() => {
                                this.classList.remove('unread');
                                this.querySelector('.bg-red-500')?.remove();
                                // Update badge count
                                const badge = document.querySelector('.notification-badge');
                                if (badge) {
                                    const currentCount = parseInt(badge.textContent) - 1;
                                    if (currentCount > 0) {
                                        badge.textContent = currentCount;
                                    } else {
                                        badge.remove();
                                    }
                                }
                            }).catch(console.error);
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>
