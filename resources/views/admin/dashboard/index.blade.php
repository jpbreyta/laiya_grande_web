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

        {{-- Time Filter Dropdown --}}
        <div class="flex items-center space-x-3 mb-6">
            <label for="timeFilter" class="text-sm font-medium text-gray-700">Time Period:</label>
            <select id="timeFilter" onchange="updateDashboard(this.value)"
                class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 shadow-sm hover:border-[#2C5F5F] focus:outline-none focus:ring-2 focus:ring-[#2C5F5F] focus:border-transparent transition-colors cursor-pointer">
                <option value="today">Today</option>
                <option value="weekly">This Week</option>
                <option value="monthly" selected>This Month</option>
            </select>
        </div>

        {{-- Dynamic KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#99e6b3] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">Total Guests</h3>
                        <p class="text-3xl font-bold" id="kpi-guests">{{ $initialKPI['guests'] ?? '0' }}</p>
                        <p class="text-sm opacity-80">For selected period</p>
                    </div>
                    <i class="fas fa-users text-3xl opacity-80"></i>
                </div>
            </div>

            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#00ced1] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">Check-ins</h3>
                        <p class="text-3xl font-bold" id="kpi-checkins">{{ $initialKPI['checkins'] ?? '0' }}</p>
                        <p class="text-sm opacity-80">For selected period</p>
                    </div>
                    <i class="fas fa-sign-in-alt text-3xl opacity-80"></i>
                </div>
            </div>

            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#3eb489] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">Occupancy Rate</h3>
                        <p class="text-3xl font-bold" id="kpi-occupancy">{{ $initialKPI['occupancy'] ?? '0' }}%</p>
                        <p class="text-sm opacity-80">Current occupancy</p>
                    </div>
                    <i class="fas fa-bed text-3xl opacity-80"></i>
                </div>
            </div>

            <div class="dashboard-card bg-gradient-to-r from-[#add8e6] to-[#00fa9a] text-gray-800 p-6 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium opacity-90">Revenue (Period)</h3>
                        <p class="text-3xl font-bold" id="kpi-revenue-period">{{ $initialKPI['revenue'] ?? '₱0.00' }}</p>
                        <p class="text-sm opacity-80">For selected period</p>
                    </div>
                    <i class="fas fa-coins text-3xl opacity-80"></i>
                </div>
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

        {{-- Booking Details Modal --}}
        <div id="bookingModal"
            class="hidden fixed inset-0 backdrop-blur-sm bg-white/10 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-800">Booking Details</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Status Badge --}}
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Reservation Number</p>
                            <p class="text-lg font-mono font-semibold text-gray-800" id="modal-reservation-number">-</p>
                        </div>
                        <span id="modal-status-badge" class="px-4 py-2 rounded-full text-sm font-semibold">-</span>
                    </div>

                    {{-- Guest Information --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-user mr-2 text-[#2C5F5F]"></i>
                            Guest Information
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Name</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-guest-name">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Email</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-guest-email">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Phone</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-guest-phone">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Number of Guests</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-guests-count">-</p>
                            </div>
                        </div>
                    </div>

                    {{-- Booking Information --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-[#2C5F5F]"></i>
                            Booking Information
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Room</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-room-name">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Booking Source</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-booking-source">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Check-in</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-check-in">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Check-out</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-check-out">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Duration</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-duration">-</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Created At</p>
                                <p class="text-sm font-medium text-gray-800" id="modal-created-at">-</p>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Information --}}
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-money-bill-wave mr-2 text-[#2C5F5F]"></i>
                            Payment Information
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Price</span>
                                <span class="text-lg font-bold text-[#2C5F5F]" id="modal-total-price">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- Special Requests --}}
                    <div id="modal-special-requests-section" class="bg-gray-50 rounded-lg p-4 hidden">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-comment-dots mr-2 text-[#2C5F5F]"></i>
                            Special Requests
                        </h4>
                        <p class="text-sm text-gray-700" id="modal-special-requests">-</p>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                    <button onclick="closeBookingModal()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                        Close
                    </button>
                    <a id="modal-view-full-btn" href="#"
                        class="px-4 py-2 bg-[#2C5F5F] hover:bg-[#1A4A4A] text-white rounded-lg font-medium transition">
                        View Full Details
                    </a>
                </div>
            </div>
        </div>

        {{-- Charts Row 2 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Booking Source Distribution</h2>
                <p class="text-sm text-gray-600 mb-4">Website bookings vs Walk-in bookings</p>
                <div class="h-64">
                    <canvas id="bookingSourceChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Revenue by Service</h2>
                <p class="text-sm text-gray-600 mb-4">Breakdown of revenue sources</p>
                <div class="h-64">
                    <canvas id="revenueByServiceChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Room Ratings Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Rated Rooms -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Top Rated Rooms</h2>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="text-sm font-semibold text-gray-600">{{ $averageRating ?? 0 }} Avg</span>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($topRatedRooms ?? [] as $room)
                        <div
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $room->name }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($room->average_rating))
                                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                            @elseif($i - 0.5 <= $room->average_rating)
                                                <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                            @else
                                                <i class="far fa-star text-gray-300 text-xs"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">{{ $room->average_rating }}</span>
                                    <span class="text-xs text-gray-500">({{ $room->ratings_count }} reviews)</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-star text-4xl mb-2"></i>
                            <p class="text-sm">No ratings yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Ratings -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Recent Ratings</h2>
                    <span class="text-sm text-gray-600">{{ $totalRatings ?? 0 }} Total</span>
                </div>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($recentRatings ?? [] as $rating)
                        <div class="p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm">
                                        {{ $rating->room->name ?? 'Unknown Room' }}</h4>
                                    <p class="text-xs text-gray-500">{{ $rating->guest_name ?? $rating->guest_email }}</p>
                                </div>
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                                    @endfor
                                </div>
                            </div>
                            @if ($rating->comment)
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $rating->comment }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $rating->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-comments text-4xl mb-2"></i>
                            <p class="text-sm">No ratings yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Daily Comparison Chart --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Bookings vs Revenue Comparison</h2>
            <p class="text-sm text-gray-600 mb-4">Compare booking volume with revenue trends</p>
            <div class="h-80">
                <canvas id="dailyComparisonChart"></canvas>
            </div>
        </div>

        {{-- Most Booked Rooms Chart (Combined) --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Guest-Favorite Rooms by Source</h2>
                    <p class="text-sm text-gray-600 mt-1">Comparison of website vs walk-in bookings for guest favorite rooms</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="flex items-center text-sm">
                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: #2C5F5F;"></span>
                        Website
                    </span>
                    <span class="flex items-center text-sm">
                        <span class="w-3 h-3 rounded-full mr-2" style="background-color: #f97316;"></span>
                        Walk-in
                    </span>
                </div>
            </div>
            <div class="h-80">
                <canvas id="topRoomsChart"></canvas>
            </div>
        </div>

        {{-- Peak Months Chart --}}
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Peak Booking Months</h2>
            <p class="text-sm text-gray-600 mb-4">Shows which months have the most bookings throughout the year ({{ now()->year }})</p>
            <div class="h-64">
                <canvas id="peakMonthsChart"></canvas>
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Global Chart Instances
            let revenueChart = null;
            let statusChart = null;
            let bookingSourceChart = null;
            let servicesChart = null;
            let peakMonthsChart = null;
            let dailyComparisonChart = null;
            let topRoomsChart = null;
            let calendar = null;

            document.addEventListener('DOMContentLoaded', () => {
                // Initialize Calendar
                initCalendar();

                // Load default (monthly) data
                updateDashboard('monthly');
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
                    events: '{{ route('admin.dashboard.calendar-events') }}',
                    eventClick: function(info) {
                        showBookingModal(info.event);
                    },
                    height: 'auto'
                });

                calendar.render();
            }

            function showBookingModal(event) {
                const props = event.extendedProps;

                // Set reservation number
                document.getElementById('modal-reservation-number').textContent = props.reservation_number || '-';

                // Set status badge
                const statusBadge = document.getElementById('modal-status-badge');
                statusBadge.textContent = props.status || '-';
                statusBadge.className = 'px-4 py-2 rounded-full text-sm font-semibold';

                if (props.status === 'confirmed') {
                    statusBadge.classList.add('bg-green-100', 'text-green-800');
                } else if (props.status === 'pending') {
                    statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                } else if (props.status === 'cancelled') {
                    statusBadge.classList.add('bg-red-100', 'text-red-800');
                } else {
                    statusBadge.classList.add('bg-gray-100', 'text-gray-800');
                }

                // Set guest information
                document.getElementById('modal-guest-name').textContent = props.guest || '-';
                document.getElementById('modal-guest-email').textContent = props.email || '-';
                document.getElementById('modal-guest-phone').textContent = props.phone || '-';
                document.getElementById('modal-guests-count').textContent = props.guests || '-';

                // Set booking information
                document.getElementById('modal-room-name').textContent = props.room || '-';
                document.getElementById('modal-booking-source').textContent = props.booking_source || '-';
                document.getElementById('modal-check-in').textContent = props.check_in || '-';
                document.getElementById('modal-check-out').textContent = props.check_out || '-';
                document.getElementById('modal-duration').textContent = props.duration || '-';
                document.getElementById('modal-created-at').textContent = props.created_at || '-';

                // Set payment information
                document.getElementById('modal-total-price').textContent = props.price || '-';

                // Set special requests
                const specialRequestsSection = document.getElementById('modal-special-requests-section');
                if (props.special_requests) {
                    document.getElementById('modal-special-requests').textContent = props.special_requests;
                    specialRequestsSection.classList.remove('hidden');
                } else {
                    specialRequestsSection.classList.add('hidden');
                }

                // Set view full details button
                if (props.booking_id) {
                    document.getElementById('modal-view-full-btn').href = `/admin/booking/${props.booking_id}`;
                }

                // Show modal
                document.getElementById('bookingModal').classList.remove('hidden');
            }

            function closeBookingModal() {
                document.getElementById('bookingModal').classList.add('hidden');
            }

            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                const modal = document.getElementById('bookingModal');
                if (event.target === modal) {
                    closeBookingModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeBookingModal();
                }
            });

            function updateDashboard(filter) {
                // Update dropdown value
                const dropdown = document.getElementById('timeFilter');
                if (dropdown && dropdown.value !== filter) {
                    dropdown.value = filter;
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

                // Booking Source Chart
                const ctxBookingSource = document.getElementById('bookingSourceChart');
                if (ctxBookingSource && charts.booking_source) {
                    if (bookingSourceChart) bookingSourceChart.destroy();
                    bookingSourceChart = new Chart(ctxBookingSource, {
                        type: 'doughnut',
                        data: {
                            labels: charts.booking_source.labels,
                            datasets: [{
                                data: charts.booking_source.data,
                                backgroundColor: ['#2C5F5F', '#f97316'],
                                borderWidth: 3,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = charts.booking_source.counts.total;
                                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                            return label + ': ' + value + ' (' + percentage + '%)';
                                        }
                                    }
                                }
                            }
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

                // Peak Months Chart
                const ctxPeak = document.getElementById('peakMonthsChart');
                if (ctxPeak && charts.peak_months) {
                    if (peakMonthsChart) peakMonthsChart.destroy();
                    peakMonthsChart = new Chart(ctxPeak, {
                        type: 'bar',
                        data: {
                            labels: charts.peak_months.labels,
                            datasets: [{
                                label: 'Number of Bookings',
                                data: charts.peak_months.data,
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
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        title: function(context) {
                                            return context[0].label + ' {{ now()->year }}';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Daily Comparison Chart
                const ctxComparison = document.getElementById('dailyComparisonChart');
                if (ctxComparison && charts.daily_comparison) {
                    if (dailyComparisonChart) dailyComparisonChart.destroy();
                    dailyComparisonChart = new Chart(ctxComparison, {
                        type: 'line',
                        data: {
                            labels: charts.daily_comparison.labels,
                            datasets: [{
                                    label: 'Number of Bookings',
                                    data: charts.daily_comparison.bookings,
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Revenue (₱)',
                                    data: charts.daily_comparison.revenue,
                                    borderColor: '#10b981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    yAxisID: 'y1'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Bookings'
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Revenue (₱)'
                                    },
                                    grid: {
                                        drawOnChartArea: false
                                    }
                                }
                            }
                        }
                    });
                }

                // Top Rooms Chart (Combined)
                const ctxTopRooms = document.getElementById('topRoomsChart');
                if (ctxTopRooms && charts.most_booked_rooms) {
                    if (topRoomsChart) topRoomsChart.destroy();
                    topRoomsChart = new Chart(ctxTopRooms, {
                        type: 'bar',
                        data: {
                            labels: charts.most_booked_rooms.labels,
                            datasets: [
                                {
                                    label: 'Website Bookings',
                                    data: charts.most_booked_rooms.website,
                                    backgroundColor: '#2C5F5F',
                                    borderRadius: 5,
                                    borderSkipped: false
                                },
                                {
                                    label: 'Walk-in Bookings',
                                    data: charts.most_booked_rooms.walkin,
                                    backgroundColor: '#f97316',
                                    borderRadius: 5,
                                    borderSkipped: false
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    stacked: false,
                                    ticks: {
                                        stepSize: 1
                                    },
                                    title: {
                                        display: true,
                                        text: 'Number of Bookings'
                                    }
                                },
                                y: {
                                    stacked: false
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    labels: {
                                        padding: 15,
                                        font: {
                                            size: 12
                                        },
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.dataset.label || '';
                                            const value = context.parsed.x || 0;
                                            return label + ': ' + value + ' booking' + (value !== 1 ? 's' : '');
                                        }
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
