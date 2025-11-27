<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Notification;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard page.
     */
    public function index()
    {
        // Fetch real data from database
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $totalRooms = Room::count();
        $occupiedRooms = \App\Models\GuestStay::where('status', 'checked-in')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
        $pendingBookings = Booking::where('status', 'pending')->count();

        $today = Carbon::today();

        // Today snapshot KPIs
        $totalGuestsToday = Booking::whereDate('check_in', $today)
            ->whereIn('status', ['confirmed', 'checked-in'])
            ->sum('number_of_guests');

        $checkInsToday = Booking::whereDate('check_in', $today)
            ->whereIn('status', ['confirmed', 'checked-in'])
            ->count();

        $pendingCheckInsToday = Booking::whereDate('check_in', $today)
            ->where('status', 'pending')
            ->count();

        $revenueToday = Booking::where('status', 'confirmed')
            ->whereDate('created_at', $today)
            ->sum('total_price');

        // Recent bookings
        $recentBookings = Booking::with('room')->latest()->take(3)->get();

        // Recent activities (simulate from bookings and reservations)
        $recentActivities = collect();

        // Add recent bookings as activities
        $recentBookingsForActivity = Booking::latest()->take(2)->get();
        foreach ($recentBookingsForActivity as $booking) {
            $recentActivities->push([
                'type' => 'booking',
                'icon' => 'fa-user-plus',
                'color' => '#2C5F5F',
                'title' => 'New booking received',
                'description' => "Room {$booking->room->name} - {$booking->number_of_guests} guests - {$booking->firstname} {$booking->lastname}",
                'time' => $booking->created_at->diffForHumans(),
            ]);
        }

        // Add recent reservations as activities
        $recentReservations = Reservation::latest()->take(2)->get();
        foreach ($recentReservations as $reservation) {
            $recentActivities->push([
                'type' => 'reservation',
                'icon' => 'fa-calendar-plus',
                'color' => '#1E3A5F',
                'title' => 'New reservation created',
                'description' => "Room {$reservation->room->name} - {$reservation->number_of_guests} guests - {$reservation->firstname} {$reservation->lastname}",
                'time' => $reservation->created_at->diffForHumans(),
            ]);
        }

        // Sort activities by created_at
        $recentActivities = $recentActivities->sortByDesc(function ($activity) {
            return $activity['time']; // This is a string, might need adjustment
        })->take(4);

        // Revenue data for chart (daily, starting from first booking, up to 30 days)
        $firstBooking = Booking::where('status', 'confirmed')->orderBy('created_at')->first();
        $startDate = $firstBooking ? Carbon::parse($firstBooking->created_at)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Limit to 30 days if more
        $maxDays = 30;
        $daysDiff = $startDate->diffInDays($endDate);
        if ($daysDiff > $maxDays) {
            $startDate = $endDate->copy()->subDays($maxDays - 1)->startOfDay();
        }

        $revenueData = [];
        $labels = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $labels[] = $currentDate->format('M d');

            $dailyRevenue = Booking::where('status', 'confirmed')
                ->whereDate('created_at', $currentDate->toDateString())
                ->sum('total_price');

            $revenueData[] = $dailyRevenue;

            $currentDate->addDay();
        }

        $revenueData = [
            'labels' => $labels,
            'data' => $revenueData
        ];

        // Notifications
        $unreadNotifications = Notification::unread()->count();
        $recentNotifications = Notification::latest()->take(5)->get();


        $occupancyLabels = [];
        $occupancyData = [];

        $days = 30;
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $occupancyLabels[] = $date->format('M d');

            $occupied = \App\Models\GuestStay::where('status', 'checked-in')
                ->whereDate('check_in_time', '<=', $date)
                ->whereDate('check_out_time', '>=', $date)
                ->count();

            $occupancyData[] = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100) : 0;
        }

        // Guest type distribution (based on number_of_guests on confirmed / checked-in bookings)
        $guestTypeCounts = [
            'families' => 0,
            'couples' => 0,
            'solo' => 0,
            'groups' => 0,
        ];

        Booking::whereIn('status', ['confirmed', 'checked-in'])
            ->select('number_of_guests')
            ->chunk(200, function ($bookings) use (&$guestTypeCounts) {
                foreach ($bookings as $booking) {
                    $guests = (int) $booking->number_of_guests;
                    if ($guests <= 0) {
                        continue;
                    } elseif ($guests === 1) {
                        $guestTypeCounts['solo']++;
                    } elseif ($guests === 2) {
                        $guestTypeCounts['couples']++;
                    } elseif ($guests >= 3 && $guests <= 5) {
                        $guestTypeCounts['families']++;
                    } else {
                        $guestTypeCounts['groups']++;
                    }
                }
            });

        $guestTypeChart = [
            'labels' => ['Families', 'Couples', 'Solo', 'Groups'],
            'data' => [
                $guestTypeCounts['families'],
                $guestTypeCounts['couples'],
                $guestTypeCounts['solo'],
                $guestTypeCounts['groups'],
            ],
        ];

        // Booking status breakdown
        $bookingStatusChart = [
            'labels' => ['Confirmed', 'Pending', 'Cancelled'],
            'data' => [
                Booking::where('status', 'confirmed')->count(),
                Booking::where('status', 'pending')->count(),
                Booking::where('status', 'cancelled')->count(),
            ],
        ];

        // Room status overview
        $maintenanceRooms = Room::where('status', 'maintenance')->count();
        $cleaningRooms = Room::where('status', 'cleaning')->count();
        $availableRooms = max($totalRooms - $occupiedRooms - $maintenanceRooms - $cleaningRooms, 0);

        $roomStatusOverview = [
            'occupied' => $occupiedRooms,
            'available' => $availableRooms,
            'cleaning' => $cleaningRooms,
            'maintenance' => $maintenanceRooms,
        ];

        // Guest insights
        $totalDistinctGuests = Booking::distinct('email')->count('email');

        $returningGuestsCount = Booking::select('email')
            ->whereNotNull('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        $returningGuestsPercent = $totalDistinctGuests > 0
            ? round(($returningGuestsCount / $totalDistinctGuests) * 100)
            : 0;

        $avgStayDuration = Booking::where('status', 'confirmed')
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->get()
            ->map(function ($booking) {
                return $booking->check_in->diffInDays($booking->check_out) ?: 1;
            })->avg() ?? 0;

        $totalGuestNights = Booking::where('status', 'confirmed')
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->get()
            ->reduce(function ($carry, $booking) {
                $nights = $booking->check_in->diffInDays($booking->check_out) ?: 1;
                return $carry + ($nights * max((int) $booking->number_of_guests, 1));
            }, 0);

        $totalRevenueForAvg = Booking::where('status', 'confirmed')->sum('total_price');

        $avgSpendPerGuest = $totalGuestNights > 0
            ? round($totalRevenueForAvg / $totalGuestNights, 2)
            : 0;

        // Peak check-in window (group by hour for today)
        $hourBuckets = [
            '08:00' => 0,
            '10:00' => 0,
            '12:00' => 0,
            '14:00' => 0,
            '16:00' => 0,
            '18:00' => 0,
        ];

        Booking::whereDate('check_in', $today)
            ->whereNotNull('actual_check_in_time')
            ->get()
            ->each(function ($booking) use (&$hourBuckets) {
                $hour = (int) $booking->actual_check_in_time->format('H');
                if ($hour < 9) {
                    $hourBuckets['08:00']++;
                } elseif ($hour < 11) {
                    $hourBuckets['10:00']++;
                } elseif ($hour < 13) {
                    $hourBuckets['12:00']++;
                } elseif ($hour < 15) {
                    $hourBuckets['14:00']++;
                } elseif ($hour < 17) {
                    $hourBuckets['16:00']++;
                } else {
                    $hourBuckets['18:00']++;
                }
            });

        arsort($hourBuckets);
        $peakCheckInWindow = count($hourBuckets)
            ? array_key_first($hourBuckets)
            : null;

        $guestInsights = [
            'returningGuestsPercent' => $returningGuestsPercent,
            'avgStayDuration' => round($avgStayDuration, 1),
            'avgSpendPerGuest' => $avgSpendPerGuest,
            'peakCheckInWindow' => $peakCheckInWindow,
        ];

        // Check-in / out activity for today (hourly)
        $activityLabels = ['8AM', '10AM', '12PM', '2PM', '4PM', '6PM'];
        $checkInSeries = [];
        $checkOutSeries = [];

        foreach ($activityLabels as $label) {
            $checkInSeries[] = 0;
            $checkOutSeries[] = 0;
        }

        $timeRanges = [
            ['start' => 8, 'end' => 10],
            ['start' => 10, 'end' => 12],
            ['start' => 12, 'end' => 14],
            ['start' => 14, 'end' => 16],
            ['start' => 16, 'end' => 18],
            ['start' => 18, 'end' => 20],
        ];

        foreach ($timeRanges as $index => $range) {
            $checkInSeries[$index] = Booking::whereDate('actual_check_in_time', $today)
                ->whereBetween(\DB::raw('HOUR(actual_check_in_time)'), [$range['start'], $range['end'] - 1])
                ->count();

            $checkOutSeries[$index] = Booking::whereDate('actual_check_out_time', $today)
                ->whereBetween(\DB::raw('HOUR(actual_check_out_time)'), [$range['start'], $range['end'] - 1])
                ->count();
        }

        $checkInOutActivity = [
            'labels' => $activityLabels,
            'checkins' => $checkInSeries,
            'checkouts' => $checkOutSeries,
        ];

        // Revenue by service (basic: room revenue + POS total)
        $roomsRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $posRevenue = \App\Models\PosTransaction::sum('total');

        $revenueByService = [
            'labels' => ['Rooms', 'F&B / POS'],
            'data' => [$roomsRevenue, $posRevenue],
        ];

        // Calendar bookings data
        $calendarBookings = Booking::with('room')
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('check_out', '>=', Carbon::now()->subMonths(1))
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'room_name' => $booking->room->name ?? 'N/A',
                    'guest_name' => $booking->firstname . ' ' . $booking->lastname,
                    'check_in' => $booking->check_in->format('Y-m-d'),
                    'check_out' => $booking->check_out->format('Y-m-d'),
                    'number_of_guests' => $booking->number_of_guests,
                    'status' => $booking->status,
                    'phone_number' => $booking->phone_number,
                    'email' => $booking->email,
                ];
            });

        return view('admin.dashboard.index', compact(
            'totalBookings',
            'totalRevenue',
            'occupancyRate',
            'pendingBookings',
            'totalGuestsToday',
            'checkInsToday',
            'pendingCheckInsToday',
            'revenueToday',
            'recentBookings',
            'recentActivities',
            'revenueData',
            'occupancyData',
            'occupancyLabels',
            'unreadNotifications',
            'recentNotifications',
            'calendarBookings',
            'guestTypeChart',
            'bookingStatusChart',
            'roomStatusOverview',
            'guestInsights',
            'checkInOutActivity',
            'revenueByService'
        ));
    }
}
