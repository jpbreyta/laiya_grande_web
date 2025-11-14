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
                <button class="btn-primary text-white px-4 py-2 rounded-lg flex items-center" onclick="location.reload()">
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
                        <p class="text-3xl font-bold">₱{{ number_format($totalRevenue ?? 0, 0, ',', ',') }}</p>
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
                <h2 class="text-xl font-bold text-gray-800 mb-4">Revenue Overview</h2>
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Chart will be rendered here</p>
                    </div>
                </div>
            </div>

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
                                    <p class="font-medium text-gray-800">{{ $booking->firstname }} {{ $booking->lastname }}
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $booking->room->name ?? 'N/A' }} -
                                        {{ $booking->number_of_guests }} guests</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-800">
                                    ₱{{ number_format($booking->total_price, 0, ',', ',') }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('M d') }}-{{ \Carbon\Carbon::parse($booking->check_out)->format('M d') }}
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const searchInput = document.querySelector('.search-input');
                if (searchInput) {
                    searchInput.addEventListener('input', e => {
                        console.log('Searching for:', e.target.value);
                    });
                }

                const notificationBtn = document.querySelector('.notification-badge')?.parentElement;
                if (notificationBtn) {
                    notificationBtn.addEventListener('click', () => {
                        alert('Notifications clicked!');
                    });
                }
            });
        </script>
    @endpush
@endsection
