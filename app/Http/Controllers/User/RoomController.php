<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        // Filter by number of guests
        if ($request->filled('guests')) {
            $query->where('capacity', '>=', $request->guests);
        }

        // Filter by price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
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

        return view('user.rooms.index', compact('rooms'));
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
