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
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        // Get initial KPI data for monthly (default view)
        $dateRange = $this->getDateRange('monthly');
        $initialKPI = $this->getKPIData($dateRange, 'monthly');
        
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
        
        // Get ratings statistics
        $totalRatings = \App\Models\RoomRating::count();
        $averageRating = \App\Models\RoomRating::avg('rating');
        $recentRatings = \App\Models\RoomRating::with('room')
            ->latest()
            ->take(10)
            ->get();
        
        // Get top rated rooms
        $topRatedRooms = \App\Models\Room::withCount('ratings')
            ->with('ratings')
            ->having('ratings_count', '>', 0)
            ->get()
            ->map(function($room) {
                $room->average_rating = round($room->averageRating(), 1);
                return $room;
            })
            ->sortByDesc('average_rating')
            ->take(5);
        
        return view('admin.dashboard.index', compact(
            'totalBookings',
            'totalRooms',
            'totalRevenue',
            'pendingBookings',
            'occupancyRate',
            'recentBookings',
            'recentActivities',
            'initialKPI',
            'totalRatings',
            'averageRating',
            'recentRatings',
            'topRatedRooms'
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
            $checkIn = Carbon::parse($booking->check_in);
            $checkOut = Carbon::parse($booking->check_out);
            $duration = $checkIn->diffInDays($checkOut) . ' night(s)';
            
            $events[] = [
                'id' => 'booking-' . $booking->id,
                'title' => $booking->room->name . ' - ' . $booking->firstname . ' ' . $booking->lastname,
                'start' => $booking->check_in,
                'end' => $booking->check_out,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'type' => 'booking',
                    'booking_id' => $booking->id,
                    'reservation_number' => $booking->reservation_number ?? 'N/A',
                    'guest' => $booking->firstname . ' ' . $booking->lastname,
                    'email' => $booking->email ?? 'N/A',
                    'phone' => $booking->phone ?? 'N/A',
                    'room' => $booking->room->name,
                    'guests' => $booking->number_of_guests,
                    'status' => $booking->status,
                    'price' => '₱' . number_format($booking->total_price, 2),
                    'booking_source' => $booking->source === 'pos' ? 'Walk-in (POS)' : 'Website',
                    'check_in' => $checkIn->format('M d, Y'),
                    'check_out' => $checkOut->format('M d, Y'),
                    'duration' => $duration,
                    'special_requests' => $booking->special_requests ?? null,
                    'created_at' => $booking->created_at->format('M d, Y h:i A')
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
            $checkIn = Carbon::parse($reservation->check_in);
            $checkOut = Carbon::parse($reservation->check_out);
            $duration = $checkIn->diffInDays($checkOut) . ' night(s)';
            
            $events[] = [
                'id' => 'reservation-' . $reservation->id,
                'title' => $reservation->room->name . ' - ' . $reservation->firstname . ' ' . $reservation->lastname,
                'start' => $reservation->check_in,
                'end' => $reservation->check_out,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'type' => 'reservation',
                    'booking_id' => $reservation->id,
                    'reservation_number' => $reservation->reservation_number ?? 'N/A',
                    'guest' => $reservation->firstname . ' ' . $reservation->lastname,
                    'email' => $reservation->email ?? 'N/A',
                    'phone' => $reservation->phone ?? 'N/A',
                    'room' => $reservation->room->name,
                    'guests' => $reservation->number_of_guests,
                    'status' => $reservation->status,
                    'price' => '₱' . number_format($reservation->total_price, 2),
                    'booking_source' => 'Website',
                    'check_in' => $checkIn->format('M d, Y'),
                    'check_out' => $checkOut->format('M d, Y'),
                    'duration' => $duration,
                    'special_requests' => $reservation->special_requests ?? null,
                    'created_at' => $reservation->created_at->format('M d, Y h:i A')
                ]
            ];
        }
        
        return response()->json($events);
    }

    /**
     * Auto-cancel bookings/reservations where check-in date has passed but no guest_stay exists
     */
    private function autoCancelExpiredBookings()
    {
        $now = Carbon::now();
        
        // Find bookings that should have checked in but didn't
        $expiredBookings = Booking::whereIn('status', ['confirmed', 'pending'])
            ->where('check_in', '<', $now->toDateString())
            ->whereDoesntHave('guestStay')
            ->get();
        
        foreach ($expiredBookings as $booking) {
            $booking->update(['status' => 'cancelled']);
        }
        
        // Find reservations that should have checked in but didn't
        $expiredReservations = Reservation::whereIn('status', ['confirmed', 'paid', 'pending'])
            ->where('check_in', '<', $now->toDateString())
            ->whereDoesntHave('guestStay')
            ->get();
        
        foreach ($expiredReservations as $reservation) {
            $reservation->update(['status' => 'cancelled']);
        }
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
        // Auto-cancel expired bookings/reservations without check-ins
        $this->autoCancelExpiredBookings();
        
        // Revenue calculation - ONLY from checked-in guests
        $guestStays = GuestStay::whereBetween('check_in_time', [$dateRange['start'], $dateRange['end']])
            ->get();
        
        $bookingIds = $guestStays->whereNotNull('booking_id')->pluck('booking_id')->unique();
        $reservationIds = $guestStays->whereNotNull('reservation_id')->pluck('reservation_id')->unique();
        
        $bookingRevenue = $bookingIds->isNotEmpty() 
            ? Booking::whereIn('id', $bookingIds)->sum('total_price')
            : 0;
        
        $reservationRevenue = $reservationIds->isNotEmpty()
            ? Reservation::whereIn('id', $reservationIds)->sum('total_price')
            : 0;
        
        // POS revenue (rentals & services)
        $posRevenue = PointOfSale::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total_amount');
        
        $totalRevenue = $bookingRevenue + $reservationRevenue + $posRevenue;
        
        // Guest count - from actual check-ins only
        $bookingGuests = GuestStay::whereNotNull('booking_id')
            ->whereBetween('check_in_time', [$dateRange['start'], $dateRange['end']])
            ->with('booking')
            ->get()
            ->sum(function($stay) {
                return $stay->booking->number_of_guests ?? 0;
            });
        
        $reservationGuests = GuestStay::whereNotNull('reservation_id')
            ->whereBetween('check_in_time', [$dateRange['start'], $dateRange['end']])
            ->with('reservation')
            ->get()
            ->sum(function($stay) {
                return $stay->reservation->number_of_guests ?? 0;
            });
        
        $totalGuests = $bookingGuests + $reservationGuests;
        
        // Check-ins - count actual guest_stays records
        $checkIns = GuestStay::whereBetween('check_in_time', [$dateRange['start'], $dateRange['end']])
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
            'services' => $this->getServiceRevenueData($dateRange),
            'peak_months' => $this->getPeakBookingMonths(),
            'booking_source' => $this->getBookingSourceData($dateRange),
            'daily_comparison' => $this->getDailyComparison($dateRange, $filter),
            'most_booked_rooms' => $this->getMostBookedRooms($dateRange)
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
     * Get booking status distribution - based on check-in dates within the period
     */
    private function getBookingStatusData($dateRange)
    {
        // Count bookings that have check-in dates within the selected period
        $bookingConfirmed = Booking::where('status', 'confirmed')
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        $reservationConfirmed = Reservation::whereIn('status', ['confirmed', 'paid'])
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        $confirmed = $bookingConfirmed + $reservationConfirmed;
        
        $pending = Booking::where('status', 'pending')
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->count() +
            Reservation::where('status', 'pending')
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        $cancelled = Booking::where('status', 'cancelled')
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->count() +
            Reservation::where('status', 'cancelled')
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        return [$confirmed, $pending, $cancelled];
    }

    /**
     * Get booking source distribution (Website vs Walk-in)
     */
    private function getBookingSourceData($dateRange)
    {
        // Count online bookings (website)
        $onlineBookings = Booking::where('source', 'online')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        // Count POS bookings (walk-in)
        $walkInBookings = Booking::where('source', 'pos')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        // All reservations are considered online/website bookings
        $reservationCount = Reservation::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
        
        $totalOnline = $onlineBookings + $reservationCount;
        $totalWalkIn = $walkInBookings;
        
        return [
            'labels' => ['Website Bookings', 'Walk-in Bookings'],
            'data' => [$totalOnline, $totalWalkIn],
            'counts' => [
                'online' => $totalOnline,
                'walkin' => $totalWalkIn,
                'total' => $totalOnline + $totalWalkIn
            ]
        ];
    }

    /**
     * Get daily comparison data (bookings vs revenue)
     */
    private function getDailyComparison($dateRange, $filter)
    {
        $labels = [];
        $bookingsData = [];
        $revenueData = [];
        
        if ($filter === 'today') {
            // Hourly for today
            for ($hour = 0; $hour < 24; $hour++) {
                $hourStart = Carbon::today()->setHour($hour);
                $hourEnd = $hourStart->copy()->addHour();
                
                $labels[] = $hourStart->format('h A');
                
                $bookingCount = Booking::whereBetween('created_at', [$hourStart, $hourEnd])->count() +
                               Reservation::whereBetween('created_at', [$hourStart, $hourEnd])->count();
                
                $revenue = Booking::where('status', 'confirmed')
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->sum('total_price') +
                    Reservation::whereIn('status', ['confirmed', 'paid'])
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->sum('total_price');
                
                $bookingsData[] = $bookingCount;
                $revenueData[] = $revenue;
            }
        } elseif ($filter === 'weekly') {
            // Daily for this week
            $start = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $labels[] = $date->format('D');
                
                $bookingCount = Booking::whereDate('created_at', $date)->count() +
                               Reservation::whereDate('created_at', $date)->count();
                
                $revenue = Booking::where('status', 'confirmed')
                    ->whereDate('created_at', $date)
                    ->sum('total_price') +
                    Reservation::whereIn('status', ['confirmed', 'paid'])
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
                
                $bookingsData[] = $bookingCount;
                $revenueData[] = $revenue;
            }
        } else {
            // Daily for this month
            $start = Carbon::now()->startOfMonth();
            $daysInMonth = $start->daysInMonth;
            
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = $start->copy()->setDay($i);
                $labels[] = $date->format('M d');
                
                $bookingCount = Booking::whereDate('created_at', $date)->count() +
                               Reservation::whereDate('created_at', $date)->count();
                
                $revenue = Booking::where('status', 'confirmed')
                    ->whereDate('created_at', $date)
                    ->sum('total_price') +
                    Reservation::whereIn('status', ['confirmed', 'paid'])
                    ->whereDate('created_at', $date)
                    ->sum('total_price');
                
                $bookingsData[] = $bookingCount;
                $revenueData[] = $revenue;
            }
        }
        
        return [
            'labels' => $labels,
            'bookings' => $bookingsData,
            'revenue' => $revenueData
        ];
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
     * Get peak booking months
     */
    private function getPeakBookingMonths()
    {
        // Get data for the entire current year
        $currentYear = Carbon::now()->year;
        $yearStart = Carbon::create($currentYear, 1, 1)->startOfDay();
        $yearEnd = Carbon::create($currentYear, 12, 31)->endOfDay();
        
        $monthlyData = DB::table('bookings')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$yearStart, $yearEnd])
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        $reservationMonthly = DB::table('reservations')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereBetween('created_at', [$yearStart, $yearEnd])
            ->groupBy('month')
            ->get()
            ->keyBy('month');
        
        $labels = [];
        $data = [];
        
        // Generate data for all 12 months
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::create($currentYear, $month, 1)->format('M');
            $bookingCount = $monthlyData[$month]->count ?? 0;
            $reservationCount = $reservationMonthly[$month]->count ?? 0;
            $data[] = $bookingCount + $reservationCount;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Calculate current occupancy rate - rooms occupied RIGHT NOW
     */
    private function calculateOccupancyRate()
    {
        $totalRooms = Room::count();
        
        if ($totalRooms === 0) {
            return 0;
        }
        
        $now = Carbon::now();
        
        // Get unique room IDs that are currently occupied
        $occupiedRoomIds = collect();
        
        // Rooms occupied by confirmed bookings (check-in <= now < check-out)
        $bookingRooms = Booking::where('status', 'confirmed')
            ->where('check_in', '<=', $now)
            ->where('check_out', '>', $now)
            ->pluck('room_id');
        
        // Rooms occupied by confirmed/paid reservations (check-in <= now < check-out)
        $reservationRooms = Reservation::whereIn('status', ['confirmed', 'paid'])
            ->where('check_in', '<=', $now)
            ->where('check_out', '>', $now)
            ->pluck('room_id');
        
        // Merge and get unique room IDs
        $occupiedRoomIds = $bookingRooms->merge($reservationRooms)->unique();
        $occupiedRooms = $occupiedRoomIds->count();
        
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

    /**
     * Get most booked rooms by source (Website vs Walk-in) - Combined
     */
    private function getMostBookedRooms($dateRange)
    {
        // Get all rooms with their booking counts by source
        $roomData = collect();
        
        // Get online bookings
        $onlineBookings = Booking::with('room')
            ->where('source', 'online')
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->get()
            ->groupBy('room_id');
        
        // Get reservations (count as website bookings)
        $reservations = Reservation::with('room')
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->get()
            ->groupBy('room_id');
        
        // Get walk-in bookings
        $walkinBookings = Booking::with('room')
            ->where('source', 'pos')
            ->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])
            ->get()
            ->groupBy('room_id');
        
        // Combine data by room
        $allRoomIds = collect()
            ->merge($onlineBookings->keys())
            ->merge($reservations->keys())
            ->merge($walkinBookings->keys())
            ->unique();
        
        foreach ($allRoomIds as $roomId) {
            $room = Room::find($roomId);
            if (!$room) continue;
            
            $websiteCount = ($onlineBookings[$roomId] ?? collect())->count() + 
                           ($reservations[$roomId] ?? collect())->count();
            $walkinCount = ($walkinBookings[$roomId] ?? collect())->count();
            $totalCount = $websiteCount + $walkinCount;
            
            $roomData[$room->name] = [
                'website' => $websiteCount,
                'walkin' => $walkinCount,
                'total' => $totalCount
            ];
        }
        
        // Sort by total bookings and take top 5
        $topRooms = $roomData->sortByDesc('total')->take(5);
        
        return [
            'labels' => $topRooms->keys()->toArray(),
            'website' => $topRooms->pluck('website')->toArray(),
            'walkin' => $topRooms->pluck('walkin')->toArray()
        ];
    }

    /**
     * Check if a room is available for booking
     * Prevents double booking by checking for overlapping confirmed bookings/reservations
     */
    public function checkRoomAvailability(Request $request)
    {
        $roomId = $request->get('room_id');
        $checkIn = Carbon::parse($request->get('check_in'));
        $checkOut = Carbon::parse($request->get('check_out'));
        
        // Check for overlapping bookings
        $overlappingBookings = Booking::where('room_id', $roomId)
            ->where('status', 'confirmed')
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out', [$checkIn, $checkOut])
                      ->orWhere(function($q) use ($checkIn, $checkOut) {
                          $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                      });
            })
            ->exists();
        
        // Check for overlapping reservations
        $overlappingReservations = Reservation::where('room_id', $roomId)
            ->whereIn('status', ['confirmed', 'paid'])
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                      ->orWhereBetween('check_out', [$checkIn, $checkOut])
                      ->orWhere(function($q) use ($checkIn, $checkOut) {
                          $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                      });
            })
            ->exists();
        
        $isAvailable = !$overlappingBookings && !$overlappingReservations;
        
        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable 
                ? 'Room is available for the selected dates' 
                : 'Room is already booked for the selected dates'
        ]);
    }

    /**
     * Export dashboard data to CSV
     */
    public function exportCsv()
    {
        try {
            $dateRange = $this->getDateRange('monthly');
            $kpiData = $this->getKPIData($dateRange, 'monthly');
        
        // Get bookings and reservations for the period
        $bookings = Booking::with(['room', 'customer'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();
        
        $reservations = Reservation::with(['room', 'customer'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();
        
        $filename = 'dashboard_report_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($kpiData, $bookings, $reservations) {
            $file = fopen('php://output', 'w');
            
            // Dashboard Summary
            fputcsv($file, ['Dashboard Summary Report']);
            fputcsv($file, ['Generated on', now()->format('M d, Y H:i')]);
            fputcsv($file, []);
            
            // KPI Data
            fputcsv($file, ['Key Performance Indicators']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Guests', $kpiData['guests']]);
            fputcsv($file, ['Total Bookings', $kpiData['bookings']]);
            fputcsv($file, ['Total Revenue', '₱' . number_format($kpiData['revenue'], 2)]);
            fputcsv($file, ['Occupancy Rate', $kpiData['occupancy'] . '%']);
            fputcsv($file, []);
            
            // Bookings
            fputcsv($file, ['Bookings']);
            fputcsv($file, ['ID', 'Ref #', 'Guest Name', 'Email', 'Room', 'Check-in', 'Check-out', 'Total', 'Status']);
            
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->reservation_number,
                    ($booking->customer->firstname ?? '') . ' ' . ($booking->customer->lastname ?? ''),
                    $booking->customer->email ?? '',
                    $booking->room->name ?? 'N/A',
                    $booking->check_in->format('M d, Y'),
                    $booking->check_out->format('M d, Y'),
                    '₱' . number_format($booking->total_price, 2),
                    ucfirst($booking->status)
                ]);
            }
            
            fputcsv($file, []);
            
            // Reservations
            fputcsv($file, ['Reservations']);
            fputcsv($file, ['ID', 'Ref #', 'Guest Name', 'Email', 'Room', 'Check-in', 'Check-out', 'Total', 'Status']);
            
            foreach ($reservations as $reservation) {
                fputcsv($file, [
                    $reservation->id,
                    $reservation->reservation_number ?? $reservation->id,
                    ($reservation->customer->firstname ?? '') . ' ' . ($reservation->customer->lastname ?? ''),
                    $reservation->customer->email ?? '',
                    $reservation->room->name ?? 'N/A',
                    Carbon::parse($reservation->check_in)->format('M d, Y'),
                    Carbon::parse($reservation->check_out)->format('M d, Y'),
                    '₱' . number_format($reservation->total_price, 2),
                    ucfirst($reservation->status)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export CSV: ' . $e->getMessage());
        }
    }

    /**
     * Export dashboard data to PDF
     */
    public function exportPdf()
    {
        try {
            $dateRange = $this->getDateRange('monthly');
            $kpiData = $this->getKPIData($dateRange, 'monthly');
        
        // Get bookings and reservations for the period
        $bookings = Booking::with(['room', 'customer'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();
        
        $reservations = Reservation::with(['room', 'customer'])
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->get();
        
        $pdf = Pdf::loadView('admin.dashboard.export-pdf', [
            'kpiData' => $kpiData,
            'bookings' => $bookings,
            'reservations' => $reservations,
            'dateRange' => $dateRange,
        ]);
        
        $filename = 'dashboard_report_' . now()->format('Y-m-d_His') . '.pdf';
        
        return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to export PDF: ' . $e->getMessage());
        }
    }
}