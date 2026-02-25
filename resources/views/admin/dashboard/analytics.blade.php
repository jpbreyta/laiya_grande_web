@extends('admin.layouts.app')

@section('content')

@push('styles')
<style>
    .analytics-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .analytics-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
</style>
@endpush

<div class="p-6">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ url('/admin/dashboard') }}" 
               class="group flex items-center justify-center w-10 h-10 rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-[#2C5F5F] hover:text-white hover:border-[#2C5F5F] transition-all duration-300 shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-chart-pie mr-2 text-[#2C5F5F]"></i>Analytics Dashboard
                </h1>
                <p class="text-gray-600">Business insights & performance metrics</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="location.reload()"
                class="bg-white border-2 border-[#2C5F5F] text-[#2C5F5F] hover:bg-[#2C5F5F] hover:text-white px-4 py-2 rounded-lg flex items-center font-medium transition-all shadow-sm">
                <i class="fas fa-sync-alt mr-2"></i>Refresh
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="analytics-card bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-calendar-check mr-2 text-[#2C5F5F]"></i>Peak Booking Months
            </h2>
            <div class="h-64">
                <canvas id="peakMonthsChart"></canvas>
            </div>
        </div>

        <div class="analytics-card bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-chart-line mr-2 text-[#2C5F5F]"></i>Bookings vs Revenue
            </h2>
            <div class="h-64">
                <canvas id="bookingRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="analytics-card bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-users-cog mr-2 text-[#2C5F5F]"></i>Booking Source Distribution
            </h2>
            <div class="h-64 flex justify-center">
                <canvas id="bookingSourceChart"></canvas>
            </div>
        </div>

        <div class="analytics-card bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-hand-holding-usd mr-2 text-[#2C5F5F]"></i>Revenue by Service
            </h2>
            <div class="h-64">
                <canvas id="revenueServiceChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="analytics-card bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-medal text-yellow-400 mr-2"></i> Top Rated Rooms
            </h2>

            @forelse($topRatedRooms ?? [] as $room)
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl mb-3 border border-transparent hover:border-gray-200 transition-all">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center mr-3">
                            <i class="fas fa-door-open text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $room->name }}</p>
                            <div class="flex items-center mt-1">
                                <span class="text-sm font-bold text-[#2C5F5F] bg-[#2C5F5F]/10 px-2 py-0.5 rounded">
                                    {{ number_format($room->average_rating, 1) }} ★
                                </span>
                            </div>
                        </div>
                    </div>
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                        <i class="fas fa-comment-dots mr-1"></i>{{ $room->reviews_count }} reviews
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-sm py-4 text-center">No rating data available.</p>
            @endforelse
        </div>

        <div class="analytics-card bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-heart text-red-400 mr-2"></i> Guest Favorites by Source
            </h2>

            @forelse($favoriteRooms ?? [] as $fav)
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl mb-3 border border-transparent hover:border-gray-200 transition-all">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $fav->room_name }}</p>
                            <p class="text-xs text-gray-500 mt-1 uppercase tracking-tight">
                                <i class="fas fa-globe mr-1"></i>Via: <span class="font-bold text-gray-700">{{ $fav->booking_source }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-bold text-[#2C5F5F]">{{ $fav->total_bookings }}</span>
                        <p class="text-[10px] text-gray-400 uppercase">Bookings</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm py-4 text-center">No booking source data.</p>
            @endforelse
        </div>
    </div>

    <div class="analytics-card bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold mb-6 text-gray-800 flex items-center">
            <i class="fas fa-comments mr-2 text-[#2C5F5F]"></i>Recent Ratings & Reviews
        </h2>

        <div class="space-y-4">
            @forelse($recentRatings ?? [] as $rating)
                <div class="p-4 bg-gray-50 rounded-xl border-l-4 border-[#2C5F5F]">
                    <div class="flex justify-between items-start mb-2">
                        <p class="font-bold text-gray-800">
                            <i class="fas fa-bed mr-2 text-gray-400"></i>{{ $rating->room->name ?? 'Room' }}
                        </p>
                        <div class="flex text-yellow-400 text-xs">
                            @for($i = 0; $i < 5; $i++)
                                <i class="{{ $i < $rating->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 italic">"{{ Str::limit($rating->review, 150) }}"</p>
                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            <i class="far fa-clock mr-1"></i>{{ $rating->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm text-center py-4">No recent ratings.</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('peakMonthsChart'), {
        type: 'bar',
        data: {
            labels: @json($charts['months']['labels'] ?? []),
            datasets: [{
                label: 'Bookings',
                data: @json($charts['months']['data'] ?? []),
                backgroundColor: '#2C5F5F',
                borderRadius: 6
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('bookingRevenueChart'), {
        type: 'line',
        data: {
            labels: @json($charts['comparison']['labels'] ?? []),
            datasets: [
                {
                    label: 'Bookings',
                    data: @json($charts['comparison']['bookings'] ?? []),
                    borderColor: '#2C5F5F',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Revenue',
                    data: @json($charts['comparison']['revenue'] ?? []),
                    borderColor: '#10B981',
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('bookingSourceChart'), {
        type: 'doughnut',
        data: {
            labels: @json($charts['source']['labels'] ?? []),
            datasets: [{
                data: @json($charts['source']['data'] ?? []),
                backgroundColor: ['#2C5F5F','#3B82F6','#F59E0B','#EF4444'],
                borderWidth: 0
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    new Chart(document.getElementById('revenueServiceChart'), {
        type: 'bar',
        data: {
            labels: @json($charts['service']['labels'] ?? []),
            datasets: [{
                label: 'Revenue',
                data: @json($charts['service']['data'] ?? []),
                backgroundColor: '#6366F1',
                borderRadius: 6
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
@endpush

@endsection