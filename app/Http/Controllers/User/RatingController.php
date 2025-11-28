<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomRating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guest_email' => 'required|email',
            'guest_name' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already rated this room
        $existingRating = RoomRating::where('room_id', $request->room_id)
            ->where('guest_email', $request->guest_email)
            ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this room. You can only rate once per room.',
            ], 422);
        }

        $rating = RoomRating::create([
            'room_id' => $request->room_id,
            'guest_email' => $request->guest_email,
            'guest_name' => $request->guest_name,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'ip_address' => $request->ip(),
            'is_verified' => false,
        ]);

        $room = Room::find($request->room_id);
        $avgRating = $room->averageRating();
        $totalRatings = $room->totalRatings();

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your rating!',
            'average_rating' => round($avgRating, 1),
            'total_ratings' => $totalRatings,
        ]);
    }

    public function getRoomRatings($roomId)
    {
        $room = Room::findOrFail($roomId);
        
        return response()->json([
            'average_rating' => round($room->averageRating(), 1),
            'total_ratings' => $room->totalRatings(),
            'ratings' => $room->ratings()->latest()->take(10)->get(),
        ]);
    }
}
