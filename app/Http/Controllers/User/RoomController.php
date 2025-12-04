<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        // Store dates in session if provided
        if ($request->filled('check_in') && $request->filled('check_out')) {
            session([
                'booking_check_in' => $request->check_in,
                'booking_check_out' => $request->check_out
            ]);
        }

        // Get dates from session
        $checkIn = session('booking_check_in');
        $checkOut = session('booking_check_out');
        $nights = 1;

        // Calculate nights if dates are set
        if ($checkIn && $checkOut) {
            $nights = max(1, \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut)));
        }

        $query = Room::query();

        // Filter by number of guests
        if ($request->filled('guests')) {
            $query->where('capacity', '>=', $request->guests);
        }

        // Filter by price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        // Filter by availability based on date overlap if dates are set
        if ($checkIn && $checkOut) {
            $query->whereDoesntHave('bookings', function ($bookingQuery) use ($checkIn, $checkOut) {
                $bookingQuery->whereIn('status', ['confirmed', 'pending'])
                    ->where(function ($dateFilter) use ($checkIn, $checkOut) {
                        $dateFilter
                            // Booking starts during our stay
                            ->whereBetween('check_in', [$checkIn, $checkOut])
                            // Booking ends during our stay
                            ->orWhereBetween('check_out', [$checkIn, $checkOut])
                            // Booking completely overlaps our stay
                            ->orWhere(function ($q) use ($checkIn, $checkOut) {
                                $q->where('check_in', '<=', $checkIn)
                                    ->where('check_out', '>=', $checkOut);
                            });
                    });
            });
        }

        // Get rooms with availability > 0
        $rooms = $query->where('availability', '>', 0)->get();

        // Add rating information to each room
        $rooms = $rooms->map(function ($room) {
            $room->average_rating = round($room->averageRating(), 1);
            $room->total_ratings = $room->totalRatings();
            return $room;
        });

        // Remove rooms that are already in cart
        $cart = session('cart', []);
        if (!empty($cart)) {
            $rooms = $rooms->filter(function ($room) use ($cart) {
                return !isset($cart[$room->id]); // hide rooms already in cart
            });
        }

        return view('user.rooms.index', compact('rooms', 'checkIn', 'checkOut', 'nights'));
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        $room->average_rating = round($room->averageRating(), 1);
        $room->total_ratings = $room->totalRatings();
        $room->ratings = $room->ratings()->latest()->take(10)->get();
        return view('user.rooms.show', compact('room'));
    }
}
