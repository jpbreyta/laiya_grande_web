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
        $occupiedRooms = Booking::where('status', 'confirmed')
            ->where('check_in', '<=', Carbon::now())
            ->where('check_out', '>', Carbon::now())
            ->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;
        $pendingBookings = Booking::where('status', 'pending')->count();

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

        return view('admin.dashboard.index', compact(
            'totalBookings',
            'totalRevenue',
            'occupancyRate',
            'pendingBookings',
            'recentBookings',
            'recentActivities',
            'revenueData',
            'unreadNotifications',
            'recentNotifications'
        ));
    }
}
