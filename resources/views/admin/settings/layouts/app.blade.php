<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Admin Dashboard' }} - Laiya Grande</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
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

        @include('admin.settings.layouts.sidebar')

        <!-- Main Section -->
        <div class="flex flex-col flex-1 h-full">

            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-end justify-between">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <input type="text" placeholder="Search Settings..."
                            class="border border-gray-300 rounded-md py-2 px-3 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-[var(--teal-primary)] focus:border-transparent" />
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <a href="{{ route('admin.dashboard') }}"
                            class="relative text-gray-600 hover:text-[var(--teal-primary)] transition-colors">
                            <i class="fas fa-home text-xl"></i>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main Page Body -->
            <main class="main-content px-4 py-4 flex-1 bg-gray-50 overflow-auto">
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

            const notificationBtn = document.querySelector('.notification-btn');
            const notificationDropdown = document.getElementById('notificationDropdown');

            if (notificationBtn && notificationDropdown) {
                notificationBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    notificationDropdown.classList.toggle('show');
                });

                document.addEventListener('click', (e) => {
                    if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                        notificationDropdown.classList.remove('show');
                    }
                });

                // Mark notification as read when clicked and redirect
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.addEventListener('click', function() {
                        const notificationId = this.dataset.id;
                        const notificationType = this.dataset.type;
                        const data = JSON.parse(this.dataset.data || '{}');

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

                                const badge = document.querySelector('.notification-badge');
                                if (badge) {
                                    const currentCount = parseInt(badge.textContent) - 1;
                                    if (currentCount > 0) {
                                        badge.textContent = currentCount;
                                    } else {
                                        badge.remove();
                                    }
                                }

                                // Redirect based on notification type
                                let redirectUrl = '';
                                if (notificationType === 'booking' && data.booking_id) {
                                    redirectUrl = `/admin/booking/${data.booking_id}`;
                                } else if (notificationType === 'reservation' && data
                                    .reservation_id) {
                                    redirectUrl =
                                        `/admin/reservation/${data.reservation_id}`;
                                } else if (notificationType === 'contact') {
                                    redirectUrl = '/admin/inbox/' + data.contact_id;
                                }

                                if (redirectUrl) {
                                    window.location.href = redirectUrl;
                                }
                            }).catch(console.error);
                        }
                    });
                });

                // Mark all as read functionality
                const markAllReadBtn = document.getElementById('markAllReadBtn');
                if (markAllReadBtn) {
                    markAllReadBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        fetch('/admin/notifications/mark-all-read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content') || ''
                            }
                        }).then(() => {
                            // Remove unread class from all items
                            document.querySelectorAll('.notification-item.unread').forEach(item => {
                                item.classList.remove('unread');
                                item.querySelector('.bg-red-500')?.remove();
                            });
                            // Remove badge
                            document.querySelector('.notification-badge')?.remove();
                        }).catch(console.error);
                    });
                }
            }
        });
    </script>
</body>

</html>
