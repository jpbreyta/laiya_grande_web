@extends('admin.layouts.app')

@section('content')
    @push('styles')
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
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

    <div class="p-6">
        {{-- Header --}}
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

        {{-- Time Filter Buttons --}}
        <div class="flex items-center space-x-2 bg-white p-1 rounded-lg border border-gray-200 shadow-sm mb-6 w-fit">
            <button onclick="updateDashboard('today')"
                class="filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-600 hover:bg-gray-50"
                id="btn-today">
                Today
            </button>
            <button onclick="updateDashboard('weekly')"
                class="filter-btn px-4 py-2 text-sm font-medium rounded-md transition-colors text-gray-600 hover:bg-gray-50"
                id="btn-weekly">
                This Week
            </button>
            <button onclick="updateDashboard('monthly')"
                class="filter-btn px-4 py-2 text-sm font-medium rounded-md bg-[#2C5F5F] text-white shadow-sm"
                id="btn-monthly">
                This Month
            </button>
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
                        <h3 class="text-sm font-medium opacity-90">Total Revenue</h3>
                        <p class="text-3xl font-bold" id="kpi-revenue">₱{{ number_format($totalRevenue ?? 0, 2, '.', ',') }}</p>
                        <p class="text-sm opacity-80">All sources</p>
                    </div>
                    <i class="fas fa-dollar-sign text-3xl opacity-80"></i>
                </div>
            </div>

            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#3eb489] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium">Occupancy Rate</h3>
                        <p class="text-3xl font-bold" id="kpi-occupancy">{{ $occupancyRate ?? 0 }}%</p>
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

        {{-- Dynamic KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="dashboard-card bg-white border border-gray-100 rounded-lg p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Total Guests</p>
                        <p class="text-2xl font-bold text-gray-800" id="kpi-guests">0</p>
                    </div>
                    <i class="fas fa-users text-2xl text-[#2C5F5F]"></i>
                </div>
                <p class="text-xs text-gray-500">For selected period</p>
            </div>

            <div class="dashboard-card bg-white border border-gray-100 rounded-lg p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Check-ins</p>
                        <p class="text-2xl font-bold text-gray-800" id="kpi-checkins">0</p>
                    </div>
                    <i class="fas fa-sign-in-alt text-2xl text-[#2C5F5F]"></i>
                </div>
                <p class="text-xs text-gray-500">For selected period</p>
            </div>

            <div class="dashboard-card bg-white border border-gray-100 rounded-lg p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Available Rooms</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalRooms }}</p>
                    </div>
                    <i class="fas fa-bed text-2xl text-[#2C5F5F]"></i>
                </div>
                <p class="text-xs text-gray-500">Total rooms in resort</p>
            </div>

            <div class="dashboard-card bg-white border border-gray-100 rounded-lg p-5">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Revenue (Period)</p>
                        <p class="text-2xl font-bold text-gray-800" id="kpi-revenue-period">₱0.00</p>
                    </div>
                    <i class="fas fa-coins text-2xl text-[#2C5F5F]"></i>
                </div>
                <p class="text-xs text-gray-500">For selected period</p>
            </div>
        </div>

        {{-- Charts Row 1 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Revenue Overview</h2>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Booking Status Breakdown</h2>
                <div class="h-64">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Booking Calendar --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">Booking Calendar</h2>
                <div class="flex items-center space-x-4">
                    <span class="flex items-center text-sm">
                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: #22c55e;"></span>
                        Confirmed
                    </span>
                    <span class="flex items-center text-sm">
                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: #fbbf24;"></span>
                        Pending
                    </span>
                    <span class="flex items-center text-sm">
                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: #ef4444;"></span>
                        Cancelled
                    </span>
                </div>
            </div>
            <div id="bookingCalendar"></div>
        </div>

        {{-- Charts Row 2 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Guest Type Distribution</h2>
                <div class="h-64">
                    <canvas id="guestTypeChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Revenue by Service</h2>
                <div class="h-64">
                    <canvas id="revenueByServiceChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Peak Hours Chart --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Peak Booking Hours</h2>
            <p class="text-sm text-gray-600 mb-4">Shows when guests typically make bookings and reservations</p>
            <div class="h-64">
                <canvas id="peakHoursChart"></canvas>
            </div>
        </div>

        {{-- Recent Activity and Bookings --}}
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
    </div>

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <script>
            // Global Chart Instances
            let revenueChart = null;
            let statusChart = null;
            let guestTypeChart = null;
            let servicesChart = null;
            let peakHoursChart = null;
            let calendar = null;

            document.addEventListener('DOMContentLoaded', () => {
                // Initialize Calendar
                initCalendar();
                
                // Load default (Monthly) data
                updateDashboard('today');
            });

            function initCalendar() {
                const calendarEl = document.getElementById('bookingCalendar');
                if (!calendarEl) return;

                calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: '{{ route("admin.dashboard.calendar-events") }}',
                    eventClick: function(info) {
                        const props = info.event.extendedProps;
                        alert(
                            'Guest: ' + props.guest + '\n' +
                            'Room: ' + props.room + '\n' +
                            'Guests: ' + props.guests + '\n' +
                            'Status: ' + props.status + '\n' +
                            'Price: ' + props.price
                        );
                    },
                    height: 'auto'
                });
                
                calendar.render();
            }

            function updateDashboard(filter) {
                // Update button styles
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('bg-[#2C5F5F]', 'text-white', 'shadow-sm');
                    btn.classList.add('text-gray-600', 'hover:bg-gray-50');
                });
                
                const activeBtn = document.getElementById(`btn-${filter}`);
                if (activeBtn) {
                    activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-50');
                    activeBtn.classList.add('bg-[#2C5F5F]', 'text-white', 'shadow-sm');
                }

                // Fetch data
                fetch(`{{ route('admin.dashboard.filter') }}?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        renderKPIs(data.kpi);
                        renderCharts(data.charts);
                    })
                    .catch(error => console.error('Error loading dashboard data:', error));
            }

            function renderKPIs(kpi) {
                if (document.getElementById('kpi-revenue-period')) {
                    document.getElementById('kpi-revenue-period').innerText = kpi.revenue;
                }
                if (document.getElementById('kpi-guests')) {
                    document.getElementById('kpi-guests').innerText = kpi.guests;
                }
                if (document.getElementById('kpi-checkins')) {
                    document.getElementById('kpi-checkins').innerText = kpi.checkins;
                }
                if (document.getElementById('kpi-occupancy')) {
                    document.getElementById('kpi-occupancy').innerText = kpi.occupancy + '%';
                }
            }

            function renderCharts(charts) {
                // Revenue Chart
                const ctxRev = document.getElementById('revenueChart');
                if (ctxRev) {
                    if (revenueChart) revenueChart.destroy();
                    revenueChart = new Chart(ctxRev, {
                        type: 'line',
                        data: {
                            labels: charts.revenue.labels,
                            datasets: [{
                                label: 'Revenue (₱)',
                                data: charts.revenue.data,
                                borderColor: '#2C5F5F',
                                backgroundColor: 'rgba(44, 95, 95, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                // Booking Status Chart
                const ctxStatus = document.getElementById('bookingStatusChart');
                if (ctxStatus) {
                    if (statusChart) statusChart.destroy();
                    statusChart = new Chart(ctxStatus, {
                        type: 'pie',
                        data: {
                            labels: ['Confirmed', 'Pending', 'Cancelled'],
                            datasets: [{
                                data: charts.status,
                                backgroundColor: ['#22c55e', '#fbbf24', '#ef4444'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }

                // Guest Type Chart
                const ctxGuest = document.getElementById('guestTypeChart');
                if (ctxGuest) {
                    if (guestTypeChart) guestTypeChart.destroy();
                    guestTypeChart = new Chart(ctxGuest, {
                        type: 'doughnut',
                        data: {
                            labels: ['Solo', 'Couple', 'Family/Group'],
                            datasets: [{
                                data: charts.guest_type,
                                backgroundColor: ['#3b82f6', '#a855f7', '#f97316'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }

                // Revenue by Service Chart
                const ctxService = document.getElementById('revenueByServiceChart');
                if (ctxService) {
                    if (servicesChart) servicesChart.destroy();
                    servicesChart = new Chart(ctxService, {
                        type: 'bar',
                        data: {
                            labels: charts.services.labels,
                            datasets: [{
                                label: 'Revenue (₱)',
                                data: charts.services.data,
                                backgroundColor: ['#10b981', '#f59e0b'],
                                borderRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                // Peak Hours Chart
                const ctxPeak = document.getElementById('peakHoursChart');
                if (ctxPeak && charts.peak_hours) {
                    if (peakHoursChart) peakHoursChart.destroy();
                    peakHoursChart = new Chart(ctxPeak, {
                        type: 'bar',
                        data: {
                            labels: charts.peak_hours.labels,
                            datasets: [{
                                label: 'Number of Bookings',
                                data: charts.peak_hours.data,
                                backgroundColor: '#2C5F5F',
                                borderRadius: 5
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            }
        </script>
    @endpush
@endsection