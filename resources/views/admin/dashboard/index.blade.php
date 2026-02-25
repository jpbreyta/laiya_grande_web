@extends('admin.layouts.app')

@section('content')
    @push('styles')
        <style>
            .dashboard-card {
                transition: all 0.3s ease;
            }

            .dashboard-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .fc .fc-button-primary {
                background-color: #2C5F5F;
                border-color: #2C5F5F;
            }

            .fc .fc-button-primary:hover {
                background-color: #1A4A4A;
                border-color: #1A4A4A;
            }
        </style>
    @endpush

    <div class="p-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
                <p class="text-gray-600 mt-2">Monitor daily resort operations</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm text-gray-500">Last updated</p>
                    <p class="text-sm font-medium text-gray-700">{{ now()->format('M d, Y H:i') }}</p>
                </div>

                <button onclick="location.reload()"
                    class="bg-[#2C5F5F] hover:bg-[#1A4A4A] text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
        </div>

        {{-- Time Filter --}}
        <div class="flex items-center space-x-3 mb-6">
            <label class="text-sm font-medium text-gray-700">Time Period:</label>
            <select id="timeFilter" onchange="updateDashboard(this.value)"
                class="px-4 py-2 text-sm rounded-lg border border-gray-300 bg-white text-gray-700">
                <option value="today">Today</option>
                <option value="weekly">This Week</option>
                <option value="monthly" selected>This Month</option>
            </select>

            <a href="{{ route('admin.dashboard.analytics') }}"
                class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-[#2C5F5F] bg-white border-2 border-[#2C5F5F] rounded-lg hover:bg-[#2C5F5F] hover:text-white transition-all duration-300 shadow-sm">
                <i class="fas fa-chart-line mr-2"></i>
                Detailed Analytics
            </a>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="dashboard-card bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-sm text-gray-500">Total Guests</h3>
                <p class="text-3xl font-bold text-[#2C5F5F]" id="kpi-guests">
                    {{ $initialKPI['guests'] ?? 0 }}
                </p>
            </div>

            <div class="dashboard-card bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-sm text-gray-500">Check-ins</h3>
                <p class="text-3xl font-bold text-[#2C5F5F]" id="kpi-checkins">
                    {{ $initialKPI['checkins'] ?? 0 }}
                </p>
            </div>

            <div class="dashboard-card bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-sm text-gray-500">Occupancy Rate</h3>
                <p class="text-3xl font-bold text-[#2C5F5F]" id="kpi-occupancy">
                    {{ $initialKPI['occupancy'] ?? 0 }}%
                </p>
            </div>

            <div class="dashboard-card bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-sm text-gray-500">Revenue</h3>
                <p class="text-3xl font-bold text-[#2C5F5F]" id="kpi-revenue-period">
                    {{ $initialKPI['revenue'] ?? '₱0.00' }}
                </p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-bold mb-4">Revenue Overview</h2>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-bold mb-4">Booking Status Breakdown</h2>
                <div class="h-64">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>

        </div>

        {{-- Booking Calendar --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-lg font-bold mb-4">Booking Calendar</h2>
            <div id="bookingCalendar"></div>
        </div>

        {{-- Recent Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Recent Bookings --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-bold mb-4">Recent Bookings</h2>

                @forelse($recentBookings ?? [] as $booking)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded mb-3">
                        <div>
                            <p class="font-medium">
                                {{ $booking->firstname }} {{ $booking->lastname }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $booking->room?->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-[#2C5F5F]">
                                ₱{{ number_format($booking->total_price, 2) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">No recent bookings.</p>
                @endforelse
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-bold mb-4">Recent Activity</h2>

                @forelse($recentActivities ?? [] as $activity)
                    <div class="p-3 bg-gray-50 rounded mb-3">
                        <p class="font-medium">{{ $activity['title'] }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['description'] }}</p>
                        <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">No recent activities.</p>
                @endforelse
            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            let revenueChart = null;
            let statusChart = null;
            let calendar = null;

            document.addEventListener('DOMContentLoaded', function() {
                initCalendar();
                updateDashboard('monthly');
            });

            function initCalendar() {
                const calendarEl = document.getElementById('bookingCalendar');

                calendar = new Calendar(calendarEl, {
                    plugins: [dayGridPlugin, interactionPlugin],
                    initialView: 'dayGridMonth',
                    events: '{{ route('admin.dashboard.calendar-events') }}',
                });

                calendar.render();
            }

            function updateDashboard(filter) {
                fetch(`{{ route('admin.dashboard.filter') }}?filter=${filter}`)
                    .then(res => res.json())
                    .then(data => {
                        renderKPIs(data.kpi);
                        renderCharts(data.charts);
                    });
            }

            function renderKPIs(kpi) {
                document.getElementById('kpi-guests').innerText = kpi.guests;
                document.getElementById('kpi-checkins').innerText = kpi.checkins;
                document.getElementById('kpi-occupancy').innerText = kpi.occupancy + '%';
                document.getElementById('kpi-revenue-period').innerText = kpi.revenue;
            }

            function renderCharts(charts) {

                if (revenueChart) revenueChart.destroy();
                revenueChart = new Chart(document.getElementById('revenueChart'), {
                    type: 'line',
                    data: {
                        labels: charts.revenue.labels,
                        datasets: [{
                            label: 'Revenue',
                            data: charts.revenue.data,
                            borderColor: '#2C5F5F',
                            fill: true
                        }]
                    }
                });

                if (statusChart) statusChart.destroy();
                statusChart = new Chart(document.getElementById('bookingStatusChart'), {
                    type: 'pie',
                    data: {
                        labels: ['Confirmed', 'Pending', 'Cancelled'],
                        datasets: [{
                            data: charts.status,
                            backgroundColor: ['#22c55e', '#fbbf24', '#ef4444']
                        }]
                    }
                });
            }
        </script>
    @endpush
@endsection
