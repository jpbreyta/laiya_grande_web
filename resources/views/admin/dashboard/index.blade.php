@extends('admin.layouts.app')

@section('content')
    @push('styles')
        <style>
            /* Scrollbar styling */
            .main-content::-webkit-scrollbar {
                width: 6px;
            }

            .main-content::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .main-content::-webkit-scrollbar-thumb {
                background: #2C5F5F;
                border-radius: 3px;
            }

            .main-content::-webkit-scrollbar-thumb:hover {
                background: #1A4A4A;
            }

            /* Notification badge pulse */
            .notification-badge {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.1);
                }
            }

            /* Button style */
            .btn-primary {
                background: #2C5F5F;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background: #1A4A4A;
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            /* Search focus shadow */
            .search-input:focus {
                box-shadow: 0 0 0 3px rgba(44, 95, 95, 0.1);
            }

            /* Spinner */
            .loading-spinner {
                border: 2px solid #f3f3f3;
                border-top: 2px solid #2C5F5F;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            /* Card hover effect */
            .dashboard-card {
                transition: all 0.3s ease;
            }

            .dashboard-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            /* Calendar styling */
            #bookingCalendar {
                max-width: 100%;
            }

            .fc-event {
                cursor: pointer;
            }

            .fc-daygrid-day.booked-date {
                background-color: rgba(44, 95, 95, 0.15);
            }

            .fc-daygrid-day.available-date {
                background-color: rgba(173, 216, 230, 0.1);
            }

            .fc .fc-button-primary {
                background-color: #2C5F5F;
                border-color: #2C5F5F;
            }

            .fc .fc-button-primary:hover {
                background-color: #1A4A4A;
                border-color: #1A4A4A;
            }

            .fc .fc-button-primary:not(:disabled).fc-button-active {
                background-color: #1A4A4A;
                border-color: #1A4A4A;
            }
        </style>
    @endpush


    {{-- Main Dashboard --}}
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Welcome to Laiya Grande Admin</h1>
                <p class="text-gray-600 mt-2">Manage your resort operations efficiently</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm text-gray-500">Last updated</p>
                    <p class="text-sm font-medium text-gray-700">{{ now()->format('M d, Y H:i') }}</p>
                </div>
                <button
                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-4 py-2 rounded-lg flex items-center"
                    onclick="location.reload()">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
        </div>

        {{-- Statistic Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#99e6b3] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">Total Bookings</h3>
                        <p class="text-3xl font-bold">{{ $totalBookings ?? 0 }}</p>
                        <p class="text-sm opacity-80">All time</p>
                    </div>
                    <i class="fas fa-calendar-check text-3xl opacity-80"></i>
                </div>
            </div>

            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#00ced1] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">Revenue</h3>
                        <p class="text-3xl font-bold">₱{{ number_format($totalRevenue ?? 0, 2, '.', ',') }}</p>
                        <p class="text-sm opacity-80">From confirmed bookings</p>
                    </div>
                    <i class="fas fa-dollar-sign text-3xl opacity-80"></i>
                </div>
            </div>

            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#3eb489] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium">Occupancy Rate</h3>
                        <p class="text-3xl font-bold">{{ $occupancyRate ?? 0 }}%</p>
                        <p class="text-sm">Current occupancy</p>
                    </div>
                    <i class="fas fa-chart-pie text-3xl"></i>
                </div>
            </div>

            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#00fa9a] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">Pending</h3>
                        <p class="text-3xl font-bold">{{ $pendingBookings ?? 0 }}</p>
                        <p class="text-sm opacity-80">Requires attention</p>
                    </div>
                    <i class="fas fa-clock text-3xl opacity-80"></i>
                </div>
            </div>
        </div>

        {{-- Content Sections --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Revenue Overview (Daily)</h2>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Occupancy Chart</h2>
                <div class="h-64">
                    <canvas id="occupancyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Booking Calendar --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Booking Calendar</h2>
                <div class="flex items-center space-x-2">
                    <span class="flex items-center text-sm">
                        <span class="w-3 h-3 bg-[#2C5F5F] rounded-full mr-2"></span>
                        Booked
                    </span>
                    <span class="flex items-center text-sm ml-4">
                        <span class="w-3 h-3 bg-cyan-400 rounded-full mr-2"></span>
                        Available
                    </span>
                </div>
            </div>
            <div id="bookingCalendar"></div>
        </div>

        {{-- Content Sections Continued --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Recent Bookings</h2>
                    <a href="{{ route('admin.booking.index') }}"
                        class="text-[#2C5F5F] hover:text-[#1A4A4A] text-sm font-medium">View All</a>
                </div>

                <div class="space-y-4">
                    @forelse($recentBookings ?? [] as $booking)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-[#2C5F5F] rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $booking->firstname }}
                                        {{ $booking->lastname }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $booking->room?->name ?? 'N/A' }} -
                                        {{ $booking->number_of_guests }} guests</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-800">
                                    ₱{{ number_format($booking->total_price, 2, '.', ',') }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('M d') }} -
                                    {{ \Carbon\Carbon::parse($booking->check_out)->format('M d') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">No recent bookings</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Activity</h2>
                <div class="space-y-4">
                    @forelse($recentActivities ?? [] as $activity)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                style="background-color: {{ $activity['color'] }}">
                                <i class="fas {{ $activity['icon'] }} text-white"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ $activity['title'] }}</p>
                                <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                            </div>
                            <span class="text-sm text-gray-500">{{ $activity['time'] }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-history text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">No recent activities</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Advanced Insights --}}
        <div class="space-y-6 mb-8">
            {{-- KPI Row (Today / Snapshot) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="dashboard-card bg-white border border-gray-100 rounded-lg p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Total Guests (Sample)</p>
                            <p class="text-2xl font-bold text-gray-800">324</p>
                        </div>
                        <span
                            class="inline-flex items-center text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                            <i class="fas fa-arrow-up mr-1"></i>12% vs yesterday
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">Demo metric for layout only</p>
                </div>

                <div class="dashboard-card bg-white border border-gray-100 rounded-lg p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Occupancy Rate</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $occupancyRate ?? 0 }}%
                            </p>
                        </div>
                        <i class="fas fa-bed text-2xl text-[#2C5F5F]"></i>
                    </div>
                    <p class="text-xs text-gray-500">Based on current checked-in guests</p>
                </div>

                <div class="dashboard-card bg-white border border-gray-100 rounded-lg p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Check-ins Today (Sample)</p>
                            <p class="text-2xl font-bold text-gray-800">37</p>
                        </div>
                        <i class="fas fa-sign-in-alt text-2xl text-[#2C5F5F]"></i>
                    </div>
                    <p class="text-xs text-gray-500">23 pending check-ins (demo)</p>
                </div>

                <div class="dashboard-card bg-white border border-gray-100 rounded-lg p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Revenue Today (Sample)</p>
                            <p class="text-2xl font-bold text-gray-800">₱76.7K</p>
                        </div>
                        <i class="fas fa-coins text-2xl text-[#2C5F5F]"></i>
                    </div>
                    <p class="text-xs text-gray-500">Across all services (demo)</p>
                </div>
            </div>

            {{-- Guest / Room / Amenities Insights --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Left: Guest & Booking Charts --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Guest Type Distribution (Sample)</h2>
                        <div class="h-60">
                            <canvas id="guestTypeChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Booking Status Breakdown (Sample)</h2>
                        <div class="h-60">
                            <canvas id="bookingStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Right: Room Status, Guest Insights, Amenities --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Room Status Overview (Sample)</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Occupied</span>
                                <span class="font-semibold text-emerald-600">158 rooms</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Available</span>
                                <span class="font-semibold text-blue-600">24 rooms</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Cleaning</span>
                                <span class="font-semibold text-amber-500">12 rooms</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Maintenance</span>
                                <span class="font-semibold text-red-500">8 rooms</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Guest Insights (Sample)</h2>
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Returning Guests</dt>
                                <dd class="font-semibold text-emerald-600">42%</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Avg Stay Duration</dt>
                                <dd class="font-semibold text-blue-600">3.2 days</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Avg Spend / Guest</dt>
                                <dd class="font-semibold text-violet-600">₱8,450</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Peak Check-in Window</dt>
                                <dd class="font-semibold text-amber-500">2–4 PM</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Popular Amenities (Sample)</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pool Access</span>
                                <span class="font-semibold text-emerald-600">89%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Spa Services</span>
                                <span class="font-semibold text-blue-600">67%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Beach Activities</span>
                                <span class="font-semibold text-violet-600">54%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Revenue by Service & Check-in/out Activity --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Revenue by Service (Sample)</h2>
                    <div class="h-64">
                        <canvas id="revenueByServiceChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-gray-800">Check-in / Check-out Activity (Sample)</h2>
                        <span class="text-xs text-gray-500">Today</span>
                    </div>
                    <div class="h-64">
                        <canvas id="checkInOutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Revenue Chart
                const ctx = document.getElementById('revenueChart');
                if (ctx) {
                    const revenueData = @json($revenueData ?? ['labels' => [], 'data' => []]);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: revenueData.labels,
                            datasets: [{
                                label: 'Revenue (₱)',
                                data: revenueData.data,
                                borderColor: '#2C5F5F',
                                backgroundColor: 'rgba(44, 95, 95, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#2C5F5F',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 12,
                                            family: 'inherit'
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    titleFont: {
                                        size: 14
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    callbacks: {
                                        label: function(context) {
                                            return 'Revenue: ₱' + context.parsed.y.toLocaleString('en-PH', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '₱' + value.toLocaleString('en-PH');
                                        },
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }
                const occupancyCtx = document.getElementById('occupancyChart');
                new Chart(occupancyCtx, {
                    type: 'line',
                    data: {
                        labels: @json($occupancyLabels),
                        datasets: [{
                            label: 'Occupancy Rate (%)',
                            data: @json($occupancyData),
                            borderColor: '#3eb489',
                            backgroundColor: 'rgba(62,180,137,0.1)',
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });


                // Search input handler
                const searchInput = document.querySelector('.search-input');
                if (searchInput) {
                    searchInput.addEventListener('input', e => {
                        console.log('Searching for:', e.target.value);
                    });
                }

                // Booking Calendar
                const calendarEl = document.getElementById('bookingCalendar');
                if (calendarEl) {
                    const bookings = @json($calendarBookings ?? []);
                    
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek'
                        },
                        height: 'auto',
                        events: bookings.map(booking => ({
                            id: booking.id,
                            title: `${booking.room_name} - ${booking.guest_name}`,
                            start: booking.check_in,
                            end: booking.check_out,
                            backgroundColor: booking.status === 'confirmed' ? '#2C5F5F' : '#00CED1',
                            borderColor: booking.status === 'confirmed' ? '#1A4A4A' : '#00B8BB',
                            extendedProps: {
                                guests: booking.number_of_guests,
                                status: booking.status,
                                phone: booking.phone_number,
                                email: booking.email
                            }
                        })),
                        eventClick: function(info) {
                            const event = info.event;
                            const props = event.extendedProps;
                            
                            alert(
                                `Booking Details:\n\n` +
                                `Guest: ${event.title}\n` +
                                `Check-in: ${event.start.toLocaleDateString()}\n` +
                                `Check-out: ${event.end ? event.end.toLocaleDateString() : 'N/A'}\n` +
                                `Guests: ${props.guests}\n` +
                                `Status: ${props.status}\n` +
                                `Phone: ${props.phone}\n` +
                                `Email: ${props.email}`
                            );
                        },
                        dayCellDidMount: function(info) {
                            const dateStr = info.date.toISOString().split('T')[0];
                            const hasBooking = bookings.some(booking => {
                                const checkIn = new Date(booking.check_in).toISOString().split('T')[0];
                                const checkOut = new Date(booking.check_out).toISOString().split('T')[0];
                                return dateStr >= checkIn && dateStr <= checkOut;
                            });
                            
                            if (hasBooking) {
                                info.el.classList.add('booked-date');
                            } else {
                                info.el.classList.add('available-date');
                            }
                        },
                        dateClick: function(info) {
                            const dateStr = info.dateStr;
                            const hasBooking = bookings.some(booking => {
                                const checkIn = new Date(booking.check_in).toISOString().split('T')[0];
                                const checkOut = new Date(booking.check_out).toISOString().split('T')[0];
                                return dateStr >= checkIn && dateStr <= checkOut;
                            });
                            
                            if (hasBooking) {
                                alert('This date is already booked and cannot be selected.');
                            } else {
                                // You can add functionality here to create a new booking
                                console.log('Available date clicked:', dateStr);
                            }
                        }
                    });

                    calendar.render();
                }

                // Guest Type Distribution (Sample)
                const guestTypeCtx = document.getElementById('guestTypeChart');
                if (guestTypeCtx) {
                    new Chart(guestTypeCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Families', 'Couples', 'Solo', 'Groups'],
                            datasets: [{
                                data: [45, 35, 15, 5],
                                backgroundColor: ['#3b82f6', '#a855f7', '#f97316', '#22c55e'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }

                // Booking Status Breakdown (Sample)
                const bookingStatusCtx = document.getElementById('bookingStatusChart');
                if (bookingStatusCtx) {
                    new Chart(bookingStatusCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Confirmed', 'Pending', 'Cancelled'],
                            datasets: [{
                                data: [74, 16, 9],
                                backgroundColor: ['#22c55e', '#fbbf24', '#ef4444'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }

                // Revenue by Service (Sample)
                const revenueByServiceCtx = document.getElementById('revenueByServiceChart');
                if (revenueByServiceCtx) {
                    new Chart(revenueByServiceCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Rooms', 'F&B', 'Spa', 'Activities'],
                            datasets: [{
                                label: 'Revenue (₱)',
                                data: [48000, 21000, 12000, 8000],
                                backgroundColor: '#10b981'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                // Check-in / Check-out Activity (Sample)
                const checkInOutCtx = document.getElementById('checkInOutChart');
                if (checkInOutCtx) {
                    new Chart(checkInOutCtx, {
                        type: 'line',
                        data: {
                            labels: ['8AM', '10AM', '12PM', '2PM', '4PM', '6PM'],
                            datasets: [{
                                    label: 'Check-ins',
                                    data: [1, 3, 6, 9, 5, 2],
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59,130,246,0.15)',
                                    tension: 0.4,
                                    fill: true
                                },
                                {
                                    label: 'Check-outs',
                                    data: [0, 1, 2, 3, 4, 5],
                                    borderColor: '#ef4444',
                                    backgroundColor: 'rgba(239,68,68,0.1)',
                                    tension: 0.4,
                                    fill: true
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
