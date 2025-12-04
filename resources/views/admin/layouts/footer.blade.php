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
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')?.getAttribute(
                                'content') || ''
                        }
                    }).then(() => {
                        // Remove unread class from all items
                        document.querySelectorAll('.notification-item.unread').forEach(item => {
                            item.classList.remove('unread');
                            item.querySelector('.bg-red-500')?.remove();
                        });
                        // Remove badge
                        document.querySelector('.notification-badge')?.remove();
                        // Update unread count
                        const unreadCount = document.querySelector('.notification-badge');
                        if (unreadCount) {
                            unreadCount.remove();
                        }
                    }).catch(console.error);
                });
            }
        }
    });


    //search functions

    const navItems = @json(config('admin_nav'));

    const searchInput = document.querySelector('.search-input');
    const resultsContainer = document.querySelector('.search-results');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        const filtered = navItems.filter(item => item.label.toLowerCase().includes(query));

        resultsContainer.innerHTML = '';

        if (filtered.length > 0 && query.length > 0) {
            filtered.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.label;
                li.classList.add('px-4', 'py-2', 'hover:bg-gray-100', 'cursor-pointer');
                li.addEventListener('click', () => {
                    if (item.route !== '#') {
                        window.location.href = '{{ url('/') }}/' + item.route.replace(
                            /\./g, '/');
                    }
                });
                resultsContainer.appendChild(li);
            });
            resultsContainer.classList.remove('hidden');
        } else {
            resultsContainer.classList.add('hidden');
        }
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.add('hidden');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/@zxing/library@latest"></script>

@stack('scripts')
