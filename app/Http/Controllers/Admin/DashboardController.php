<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\GuestStay;
use App\Models\PointOfSale;
use App\Models\RoomRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function analytics()
    {
        $dateRange = $this->getDateRange('monthly');

        $charts = [
            'months' => $this->getPeakBookingMonths(),
            'comparison' => $this->getDailyComparison($dateRange, 'monthly'),
            'source' => $this->getBookingSourceData($dateRange),
            'service' => $this->getServiceRevenueData($dateRange),
        ];

        $topRatedRooms = Room::withCount('ratings')
            ->with('ratings')
            ->having('ratings_count', '>', 0)
            ->get()
            ->map(function ($room) {
                $room->average_rating = round($room->averageRating(), 1);
                return $room;
            })
            ->sortByDesc('average_rating')
            ->take(5);

        $recentRatings = RoomRating::with('room')
            ->latest()
            ->take(5)
            ->get();

        $favoriteRooms = $this->getFavoriteRoomsList($dateRange);

        return view('admin.dashboard.analytics', compact(
            'charts', 
            'topRatedRooms', 
            'recentRatings', 
            'favoriteRooms'
        ));
    }

    public function index()
    {
        $totalBookings = Booking::count() + Reservation::count();
        $totalRooms = Room::count();
        $pendingBookings = Booking::where('status', 'pending')->count() +
            Reservation::where('status', 'pending')->count();

        $bookingRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $reservationRevenue = Reservation::whereIn('status', ['confirmed', 'paid'])->sum('total_price');
        $posRevenue = PointOfSale::sum('total_amount');
        $totalRevenue = $bookingRevenue + $reservationRevenue + $posRevenue;

        $occupancyRate = $this->calculateOccupancyRate();

        $dateRange = $this->getDateRange('monthly');
        $initialKPI = $this->getKPIData($dateRange, 'monthly');

        $recentBookings = Booking::with('room')->latest()->take(5)->get();
        $recentReservations = Reservation::with('room')->latest()->take(5)->get();

        $recentBookings = $recentBookings->merge($recentReservations)
            ->sortByDesc('created_at')
            ->take(10);

        $recentActivities = $this->getRecentActivities();
        $totalRatings = RoomRating::count();
        $averageRating = RoomRating::avg('rating');
        
        $recentRatings = RoomRating::with('room')->latest()->take(10)->get();

        $topRatedRooms = Room::withCount('ratings')
            ->with('ratings')
            ->having('ratings_count', '>', 0)
            ->get()
            ->map(function ($room) {
                $room->average_rating = round($room->averageRating(), 1);
                return $room;
            })
            ->sortByDesc('average_rating')
            ->take(5);

        return view('admin.dashboard.index', compact(
            'totalBookings', 'totalRooms', 'totalRevenue', 'pendingBookings',
            'occupancyRate', 'recentBookings', 'recentActivities', 'initialKPI',
            'totalRatings', 'averageRating', 'recentRatings', 'topRatedRooms'
        ));
    }

    public function filter(Request $request)
    {
        $filter = $request->get('filter', 'monthly');
        $dateRange = $this->getDateRange($filter);
        $kpi = $this->getKPIData($dateRange, $filter);
        $charts = $this->getChartData($dateRange, $filter);

        return response()->json([
            'kpi' => $kpi,
            'charts' => $charts
        ]);
    }

    public function calendarEvents(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $events = [];

        $bookings = Booking::with('room')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('check_in', [$start, $end])
                    ->orWhereBetween('check_out', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('check_in', '<=', $start)->where('check_out', '>=', $end);
                    });
            })->get();

        foreach ($bookings as $booking) {
            $color = $this->getStatusColor($booking->status);
            $checkIn = Carbon::parse($booking->check_in);
            $checkOut = Carbon::parse($booking->check_out);

            $events[] = [
                'id' => 'booking-' . $booking->id,
                'title' => $booking->room->name . ' - ' . $booking->firstname . ' ' . $booking->lastname,
                'start' => $booking->check_in,
                'end' => $booking->check_out,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'type' => 'booking',
                    'status' => $booking->status,
                    'guest' => $booking->firstname . ' ' . $booking->lastname,
                    'room' => $booking->room->name,
                    'price' => '₱' . number_format($booking->total_price, 2)
                ]
            ];
        }

        $reservations = Reservation::with('room')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('check_in', [$start, $end])
                    ->orWhereBetween('check_out', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('check_in', '<=', $start)->where('check_out', '>=', $end);
                    });
            })->get();

        foreach ($reservations as $res) {
            $color = $this->getStatusColor($res->status);
            $events[] = [
                'id' => 'reservation-' . $res->id,
                'title' => $res->room->name . ' - ' . $res->firstname . ' ' . $res->lastname,
                'start' => $res->check_in,
                'end' => $res->check_out,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'type' => 'reservation',
                    'status' => $res->status,
                    'guest' => $res->firstname . ' ' . $res->lastname,
                    'room' => $res->room->name
                ]
            ];
        }

        return response()->json($events);
    }

    private function autoCancelExpiredBookings()
    {
        $now = Carbon::now()->toDateString();

        Booking::whereIn('status', ['confirmed', 'pending'])
            ->where('check_in', '<', $now)
            ->whereDoesntHave('guestStay')
            ->update(['status' => 'cancelled']);

        Reservation::whereIn('status', ['confirmed', 'paid', 'pending'])
            ->where('check_in', '<', $now)
            ->whereDoesntHave('guestStay')
            ->update(['status' => 'cancelled']);
    }

    private function getDateRange($filter)
    {
        return match ($filter) {
            'weekly' => ['start' => Carbon::now()->startOfWeek(), 'end' => Carbon::now()->endOfWeek()],
            'monthly' => ['start' => Carbon::now()->startOfMonth(), 'end' => Carbon::now()->endOfMonth()],
            default => ['start' => Carbon::today(), 'end' => Carbon::today()->endOfDay()],
        };
    }

    private function getKPIData($dateRange, $filter)
    {
        $this->autoCancelExpiredBookings();

        $guestStays = GuestStay::whereBetween('check_in_time', [$dateRange['start'], $dateRange['end']])->get();
        $bookingIds = $guestStays->whereNotNull('booking_id')->pluck('booking_id')->unique();
        $resIds = $guestStays->whereNotNull('reservation_id')->pluck('reservation_id')->unique();

        $rev = Booking::whereIn('id', $bookingIds)->sum('total_price') + 
               Reservation::whereIn('id', $resIds)->sum('total_price') +
               PointOfSale::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->sum('total_amount');

        $guests = GuestStay::whereBetween('check_in_time', [$dateRange['start'], $dateRange['end']])
            ->with(['booking', 'reservation'])
            ->get()
            ->sum(fn($s) => ($s->booking->number_of_guests ?? 0) + ($s->reservation->number_of_guests ?? 0));

        return [
            'revenue' => '₱' . number_format($rev, 2),
            'guests' => number_format($guests),
            'checkins' => number_format($guestStays->count()),
            'occupancy' => $this->calculateOccupancyRate()
        ];
    }

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

    private function getRevenueChartData($dateRange, $filter)
    {
        $labels = []; $data = [];
        $steps = $filter === 'today' ? 24 : ($filter === 'weekly' ? 7 : Carbon::now()->daysInMonth);

        for ($i = 0; $i < $steps; $i++) {
            $date = $filter === 'today' ? Carbon::today()->setHour($i) : 
                   ($filter === 'weekly' ? Carbon::now()->startOfWeek()->addDays($i) : 
                    Carbon::now()->startOfMonth()->addDays($i));

            $labels[] = $filter === 'today' ? $date->format('h A') : $date->format('M d');
            
            $queryDate = $filter === 'today' ? [$date, $date->copy()->addHour()] : $date;
            $method = $filter === 'today' ? 'whereBetween' : 'whereDate';

            $rev = Booking::where('status', 'confirmed')->$method('created_at', $queryDate)->sum('total_price') +
                   Reservation::whereIn('status', ['confirmed', 'paid'])->$method('created_at', $queryDate)->sum('total_price') +
                   PointOfSale::$method('created_at', $queryDate)->sum('total_amount');
            
            $data[] = $rev;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getBookingStatusData($dateRange)
    {
        $confirmed = Booking::where('status', 'confirmed')->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count() +
                    Reservation::whereIn('status', ['confirmed', 'paid'])->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count();

        $pending = Booking::where('status', 'pending')->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count() +
                  Reservation::where('status', 'pending')->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count();

        $cancelled = Booking::where('status', 'cancelled')->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count() +
                    Reservation::where('status', 'cancelled')->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count();

        return [$confirmed, $pending, $cancelled];
    }

    private function getBookingSourceData($dateRange)
    {
        $online = Booking::where('source', 'online')->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count() +
                 Reservation::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();
        
        $walkin = Booking::where('source', 'pos')->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count();

        return [
            'labels' => ['Website', 'Walk-in'],
            'data' => [$online, $walkin],
            'counts' => ['online' => $online, 'walkin' => $walkin, 'total' => $online + $walkin]
        ];
    }

    private function getDailyComparison($dateRange, $filter)
    {
        $labels = []; $bookings = []; $revenue = [];
        $steps = $filter === 'today' ? 24 : ($filter === 'weekly' ? 7 : Carbon::now()->daysInMonth);

        for ($i = 0; $i < $steps; $i++) {
            $date = $filter === 'today' ? Carbon::today()->setHour($i) : 
                   ($filter === 'weekly' ? Carbon::now()->startOfWeek()->addDays($i) : 
                    Carbon::now()->startOfMonth()->addDays($i));

            $labels[] = $filter === 'today' ? $date->format('h A') : $date->format('M d');
            $method = $filter === 'today' ? 'whereBetween' : 'whereDate';
            $val = $filter === 'today' ? [$date, $date->copy()->addHour()] : $date;

            $bookings[] = Booking::$method('created_at', $val)->count() + Reservation::$method('created_at', $val)->count();
            $revenue[] = Booking::where('status', 'confirmed')->$method('created_at', $val)->sum('total_price') +
                         Reservation::whereIn('status', ['confirmed', 'paid'])->$method('created_at', $val)->sum('total_price');
        }

        return ['labels' => $labels, 'bookings' => $bookings, 'revenue' => $revenue];
    }

    private function getServiceRevenueData($dateRange)
    {
        $roomRev = Booking::where('status', 'confirmed')->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->sum('total_price') +
                   Reservation::whereIn('status', ['confirmed', 'paid'])->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->sum('total_price');

        $posRev = PointOfSale::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->sum('total_amount');

        return ['labels' => ['Room Bookings', 'Services'], 'data' => [$roomRev, $posRev]];
    }

    private function getPeakBookingMonths()
    {
        $year = Carbon::now()->year;
        $labels = []; $data = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = Carbon::create($year, $m, 1)->format('M');
            $count = Booking::whereYear('created_at', $year)->whereMonth('created_at', $m)->count() +
                     Reservation::whereYear('created_at', $year)->whereMonth('created_at', $m)->count();
            $data[] = $count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function calculateOccupancyRate()
    {
        $total = Room::count();
        if ($total === 0) return 0;

        $now = Carbon::now();
        $occupied = Booking::where('status', 'confirmed')->where('check_in', '<=', $now)->where('check_out', '>', $now)->pluck('room_id')
            ->merge(Reservation::whereIn('status', ['confirmed', 'paid'])->where('check_in', '<=', $now)->where('check_out', '>', $now)->pluck('room_id'))
            ->unique()->count();

        return round(($occupied / $total) * 100, 1);
    }

    private function getRecentActivities()
    {
        $bookings = Booking::with('room')->latest()->take(3)->get()->map(fn($b) => [
            'title' => $this->getActivityTitle($b->status, 'Booking'),
            'description' => "{$b->firstname} {$b->lastname} - {$b->room->name}",
            'time' => $b->created_at->diffForHumans(),
            'icon' => $this->getActivityIcon($b->status),
            'color' => $this->getStatusColor($b->status)
        ]);

        $res = Reservation::with('room')->latest()->take(2)->get()->map(fn($r) => [
            'title' => $this->getActivityTitle($r->status, 'Reservation'),
            'description' => "{$r->firstname} {$r->lastname} - {$r->room->name}",
            'time' => $r->created_at->diffForHumans(),
            'icon' => $this->getActivityIcon($r->status),
            'color' => $this->getStatusColor($r->status)
        ]);

        return $bookings->merge($res)->sortByDesc('time')->take(5)->values()->all();
    }

    private function getActivityTitle($status, $type) {
        $titles = ['confirmed' => "New $type Confirmed", 'pending' => "Pending $type", 'cancelled' => "$type Cancelled", 'paid' => "$type Paid"];
        return $titles[$status] ?? "New $type";
    }

    private function getActivityIcon($status) {
        $icons = ['confirmed' => 'fa-check-circle', 'pending' => 'fa-clock', 'cancelled' => 'fa-times-circle', 'paid' => 'fa-money-bill-wave'];
        return $icons[$status] ?? 'fa-calendar';
    }

    private function getStatusColor($status) {
        $colors = ['confirmed' => '#22c55e', 'pending' => '#fbbf24', 'cancelled' => '#ef4444', 'paid' => '#10b981'];
        return $colors[$status] ?? '#2C5F5F';
    }

    private function getMostBookedRooms($dateRange)
    {
        $rooms = Room::all();
        $roomData = [];

        foreach ($rooms as $room) {
            $web = Booking::where('room_id', $room->id)->where('source', 'online')->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count() +
                  Reservation::where('room_id', $room->id)->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count();
            $walk = Booking::where('room_id', $room->id)->where('source', 'pos')->whereBetween('check_in', [$dateRange['start'], $dateRange['end']])->count();

            if ($web + $walk > 0) {
                $roomData[$room->name] = ['website' => $web, 'walkin' => $walk, 'total' => $web + $walk];
            }
        }

        $top = collect($roomData)->sortByDesc('total')->take(5);
        return ['labels' => $top->keys()->all(), 'website' => $top->pluck('website')->all(), 'walkin' => $top->pluck('walkin')->all()];
    }

    private function getFavoriteRoomsList($dateRange)
    {
        $data = $this->getMostBookedRooms($dateRange);
        return collect($data['labels'])->map(fn($name, $i) => (object)[
            'room_name' => $name,
            'booking_source' => $data['website'][$i] >= $data['walkin'][$i] ? 'Website' : 'Walk-in',
            'total_bookings' => $data['website'][$i] + $data['walkin'][$i]
        ]);
    }

    public function exportCsv()
    {
        $dateRange = $this->getDateRange('monthly');
        $kpi = $this->getKPIData($dateRange, 'monthly');
        $filename = 'report_' . now()->format('YmdHis') . '.csv';

        return response()->stream(function () use ($kpi) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Revenue', $kpi['revenue']]);
            fputcsv($file, ['Occupancy', $kpi['occupancy'] . '%']);
            fclose($file);
        }, 200, ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"$filename\""]);
    }

    public function exportPdf()
    {
        $dateRange = $this->getDateRange('monthly');
        $pdf = Pdf::loadView('admin.dashboard.export-pdf', [
            'kpiData' => $this->getKPIData($dateRange, 'monthly'),
            'bookings' => Booking::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->get(),
            'reservations' => Reservation::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->get(),
            'dateRange' => $dateRange,
        ]);
        return $pdf->download('report_' . now()->format('YmdHis') . '.pdf');
    }
}