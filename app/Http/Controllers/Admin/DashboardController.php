<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\GuestStay;
use App\Models\PointOfSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard page
     */
    public function index()
    {
        // Overall statistics (all-time)
        $totalBookings = Booking::count() + Reservation::count();
        $totalRooms = Room::count();
        $pendingBookings = Booking::where('status', 'pending')->count() + 
                          Reservation::where('status', 'pending')->count();
        
        // Calculate total revenue (confirmed bookings + POS)
        $bookingRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $reservationRevenue = Reservation::whereIn('status', ['confirmed', 'paid'])->sum('total_price');
        $posRevenue = PointOfSale::sum('total_amount');
        $totalRevenue = $bookingRevenue + $reservationRevenue + $posRevenue;
        
        // Current occupancy rate
        $occupancyRate = $this->calculateOccupancyRate();
        
        // Recent bookings (last 10, combining bookings and reservations)
        $recentBookings = Booking::with('room')
            ->latest()
            ->take(5)
            ->get();
        
        $recentReservations = Reservation::with('room')
            ->latest()
            ->take(5)
            ->get();
        
        $recentBookings = $recentBookings->merge($recentReservations)
            ->sortByDesc('created_at')
            ->take(10);
        
        // Recent activities
        $recentActivities = $this->getRecentActivities();
        
        return view('admin.dashboard.index', compact(
            'totalBookings',
            'totalRooms',
            'totalRevenue',
            'pendingBookings',
            'occupancyRate',
            'recentBookings',
            'recentActivities'
        ));
    }

    /**
     * Handle AJAX filter requests
     */
    public function filter(Request $request)
    {
        $filter = $request->get('filter', 'monthly');
        
        // Determine date range
        $dateRange = $this->getDateRange($filter);
        
        // Get KPI data
        $kpi = $this->getKPIData($dateRange, $filter);
        
        // Get chart data
        $charts = $this->getChartData($dateRange, $filter);
        
        return response()->json([
            'kpi' => $kpi,
            'charts' => $charts
        ]);
    }

    /**
     * Get calendar events for bookings
     */
    public function calendarEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        
        $events = [];
        
        // Get bookings
        $bookings = Booking::with('room')
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('check_in', [$start, $end])
                      ->orWhereBetween('check_out', [$start, $end])
                      ->orWhere(function($q) use ($start, $end) {
                          $q->where('check_in', '<=', $start)
                            ->where('check_out', '>=', $end);
                      });
            })
            ->get();
        
        foreach ($bookings as $booking) {
            $color = $this->getStatusColor($booking->status);
            $events[] = [
                'id' => 'booking-' . $booking->id,
                'title' => $booking->room->name . ' - ' . $booking->firstname . ' ' . $booking->lastname,
                'start' => $booking->check_in,
                'end' => $booking->check_out,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'type' => 'booking',
                    'guest' => $booking->firstname . ' ' . $booking->lastname,
                    'room' => $booking->room->name,
                    'guests' => $booking->number_of_guests,
                    'status' => $booking->status,
                    'price' => '₱' . number_format($booking->total_price, 2)
                ]
            ];
        }
        
        // Get reservations
        $reservations = Reservation::with('room')
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('check_in', [$start, $end])
                      ->orWhereBetween('check_out', [$start, $end])
                      ->orWhere(function($q) use ($start, $end) {
                          $q->where('check_in', '<=', $start)
                            ->where('check_out', '>=', $end);
                      });
            })
            ->get();
        
        foreach ($reservations as $reservation) {
            $color = $this->getStatusColor($reservation->status);
            $events[] = [
                'id' => 'reservation-' . $reservation->id,
                'title' => $reservation->room->name . ' - ' . $reservation->firstname . ' ' . $reservation->lastname,
                'start' => $reservation->check_in,
                'end' => $reservation->check_out,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'type' => 'reservation',
                    'guest' => $reservation->firstname . ' ' . $reservation->lastname,
                    'room' => $reservation->room->name,
                    'guests' => $reservation->number_of_guests,
                    'status' => $reservation->status,
                    'price' => '₱' . number_format($reservation->total_price, 2)
                ]
            ];
        }
        
        return response()->json($events);
    }

    /**
     * Get date range based on filter type
     */
    private function getDateRange($filter)
    {
        switch ($filter) {
            case 'today':
            default:
                return [
                    'start' => Carbon::today(),
                    'end' => Carbon::today()->endOfDay()
                ];
            case 'weekly':
                return [
                    'start' => Carbon::now()->startOfWeek(),
                    'end' => Carbon::now()->endOfWeek()
                ];
            case 'monthly':
                return [
                    'start' => Carbon::now()->startOfMonth(),
                    'end' => Carbon::now()->endOfMonth()
                ];
        }
    }

    /**
     * Get KPI data
     */
    private function getKPIData($dateRange, $filter)
    {
        // Revenue calculation
        $bookingRevenue = Booking::where('status', 'confirmed')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total_price');
        
        $reservationRevenue = Reservation::whereIn('status', ['confirmed', 'paid'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total_price');
        
        $posRevenue = PointOfSale::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total_amount');
        
        $totalRevenue = $bookingRevenue + $reservationRevenue + $posRevenue;
        
        // Guest count
        $bookingGuests = Booking::whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->sum('number_of_guests');
        
        $reservationGuests = Reservation::whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->sum('number_of_guests');
        
        $totalGuests = $bookingGuests + $reservationGuests;
        
        // Check-ins
        $checkIns = Booking::whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->count() + 
            Reservation::whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        // Occupancy rate (current)
        $occupancyRate = $this->calculateOccupancyRate();
        
        return [
            'revenue' => '₱' . number_format($totalRevenue, 2, '.', ','),
            'guests' => number_format($totalGuests),
            'checkins' => number_format($checkIns),
            'occupancy' => $occupancyRate
        ];
    }

    /**
     * Get chart data
     */
    private function getChartData($dateRange, $filter)
    {
        return [
            'revenue' => $this->getRevenueChartData($dateRange, $filter),
            'status' => $this->getBookingStatusData($dateRange),
            'guest_type' => $this->getGuestTypeData($dateRange),
            'services' => $this->getServiceRevenueData($dateRange),
            'peak_hours' => $this->getPeakBookingHours($dateRange)
        ];
    }

    /**
     * Get revenue chart data with proper date formatting
     */
    private function getRevenueChartData($dateRange, $filter)
    {
        $labels = [];
        $data = [];
        
        if ($filter === 'today') {
            // Hourly breakdown
            for ($hour = 0; $hour < 24; $hour++) {
                $hourStart = Carbon::today()->setHour($hour);
                $hourEnd = $hourStart->copy()->addHour();
                
                $labels[] = $hourStart->format('h A');
                
                $bookingRev = Booking::where('status', 'confirmed')
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->sum('total_price');
                
                $reservationRev = Reservation::whereIn('status', ['confirmed', 'paid'])
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->sum('total_price');
                
                $posRev = PointOfSale::whereBetween('created_at', [$hourStart, $hourEnd])
                    ->sum('total_amount');
                
                $data[] = $bookingRev + $reservationRev + $posRev;
            }
        } elseif ($filter === 'weekly') {
            // Daily breakdown for this week
            $start = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $labels[] = $date->format('D');
                
                $bookingRev = Booking::where('status', 'confirmed')
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
                
                $reservationRev = Reservation::whereIn('status', ['confirmed', 'paid'])
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
                
                $posRev = PointOfSale::whereDate('created_at', $date)
                    ->sum('total_amount');
                
                $data[] = $bookingRev + $reservationRev + $posRev;
            }
        } else {
            // Daily breakdown for this month
            $start = Carbon::now()->startOfMonth();
            $daysInMonth = $start->daysInMonth;
            
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = $start->copy()->setDay($i);
                $labels[] = $date->format('M d');
                
                $bookingRev = Booking::where('status', 'confirmed')
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
                
                $reservationRev = Reservation::whereIn('status', ['confirmed', 'paid'])
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
                
                $posRev = PointOfSale::whereDate('created_at', $date)
                    ->sum('total_amount');
                
                $data[] = $bookingRev + $reservationRev + $posRev;
            }
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get booking status distribution
     */
    private function getBookingStatusData($dateRange)
    {
        $bookingConfirmed = Booking::where('status', 'confirmed')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        $reservationConfirmed = Reservation::whereIn('status', ['confirmed', 'paid'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        $confirmed = $bookingConfirmed + $reservationConfirmed;
        
        $pending = Booking::where('status', 'pending')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count() +
            Reservation::where('status', 'pending')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        $cancelled = Booking::where('status', 'cancelled')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count() +
            Reservation::where('status', 'cancelled')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        return [$confirmed, $pending, $cancelled];
    }

    /**
     * Get guest type distribution
     */
    private function getGuestTypeData($dateRange)
    {
        $bookings = Booking::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->get();
        $reservations = Reservation::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->get();
        
        $allBookings = $bookings->merge($reservations);
        
        $solo = $allBookings->where('number_of_guests', 1)->count();
        $couple = $allBookings->where('number_of_guests', 2)->count();
        $group = $allBookings->where('number_of_guests', '>', 2)->count();
        
        return [$solo, $couple, $group];
    }

    /**
     * Get revenue by service type
     */
    private function getServiceRevenueData($dateRange)
    {
        $bookingRevenue = Booking::where('status', 'confirmed')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total_price');
        
        $reservationRevenue = Reservation::whereIn('status', ['confirmed', 'paid'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total_price');
        
        $roomRevenue = $bookingRevenue + $reservationRevenue;
        
        $posRevenue = PointOfSale::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total_amount');
        
        return [
            'labels' => ['Room Bookings', 'Rentals & Services'],
            'data' => [$roomRevenue, $posRevenue]
        ];
    }

    /**
     * Get peak booking hours
     */
    private function getPeakBookingHours($dateRange)
    {
        $hourlyData = DB::table('bookings')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('hour')
            ->get()
            ->keyBy('hour');
        
        $reservationHourly = DB::table('reservations')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('hour')
            ->get()
            ->keyBy('hour');
        
        $labels = [];
        $data = [];
        
        for ($hour = 0; $hour < 24; $hour++) {
            $labels[] = sprintf('%02d:00', $hour);
            $bookingCount = $hourlyData[$hour]->count ?? 0;
            $reservationCount = $reservationHourly[$hour]->count ?? 0;
            $data[] = $bookingCount + $reservationCount;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Calculate current occupancy rate
     */
    private function calculateOccupancyRate()
    {
        $totalRooms = Room::count();
        
        if ($totalRooms === 0) {
            return 0;
        }
        
        $today = Carbon::today();
        
        // Count rooms currently occupied by bookings
        $occupiedByBookings = Booking::where('status', 'confirmed')
            ->where('check_in', '<=', $today)
            ->where('check_out', '>', $today)
            ->distinct('room_id')
            ->count('room_id');
        
        // Count rooms currently occupied by reservations
        $occupiedByReservations = Reservation::whereIn('status', ['confirmed', 'paid'])
            ->where('check_in', '<=', $today)
            ->where('check_out', '>', $today)
            ->distinct('room_id')
            ->count('room_id');
        
        $occupiedRooms = $occupiedByBookings + $occupiedByReservations;
        
        return round(($occupiedRooms / $totalRooms) * 100, 1);
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $activities = [];
        
        // Get recent bookings
        $recentBookings = Booking::with('room')
            ->latest()
            ->take(3)
            ->get();
        
        foreach ($recentBookings as $booking) {
            $activities[] = [
                'title' => $this->getActivityTitle($booking->status, 'Booking'),
                'description' => "{$booking->firstname} {$booking->lastname} - {$booking->room->name}",
                'time' => $booking->created_at->diffForHumans(),
                'icon' => $this->getActivityIcon($booking->status),
                'color' => $this->getStatusColor($booking->status)
            ];
        }
        
        // Get recent reservations
        $recentReservations = Reservation::with('room')
            ->latest()
            ->take(2)
            ->get();
        
        foreach ($recentReservations as $reservation) {
            $activities[] = [
                'title' => $this->getActivityTitle($reservation->status, 'Reservation'),
                'description' => "{$reservation->firstname} {$reservation->lastname} - {$reservation->room->name}",
                'time' => $reservation->created_at->diffForHumans(),
                'icon' => $this->getActivityIcon($reservation->status),
                'color' => $this->getStatusColor($reservation->status)
            ];
        }
        
        return collect($activities)->sortByDesc('time')->take(5)->values()->all();
    }

    /**
     * Get activity title based on status
     */
    private function getActivityTitle($status, $type)
    {
        $titles = [
            'confirmed' => "New {$type} Confirmed",
            'pending' => "Pending {$type}",
            'cancelled' => "{$type} Cancelled",
            'paid' => "{$type} Payment Received",
            'active' => "{$type} Active",
            'completed' => "{$type} Completed"
        ];
        
        return $titles[$status] ?? "New {$type}";
    }

    /**
     * Get activity icon based on status
     */
    private function getActivityIcon($status)
    {
        $icons = [
            'confirmed' => 'fa-check-circle',
            'pending' => 'fa-clock',
            'cancelled' => 'fa-times-circle',
            'paid' => 'fa-money-bill-wave',
            'active' => 'fa-user-check',
            'completed' => 'fa-flag-checkered'
        ];
        
        return $icons[$status] ?? 'fa-calendar';
    }

    /**
     * Get status color
     */
    private function getStatusColor($status)
    {
        $colors = [
            'confirmed' => '#22c55e',
            'pending' => '#fbbf24',
            'cancelled' => '#ef4444',
            'paid' => '#10b981',
            'active' => '#3b82f6',
            'completed' => '#8b5cf6'
        ];
        
        return $colors[$status] ?? '#2C5F5F';
    }
}