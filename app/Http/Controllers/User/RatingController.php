<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RatingStoreRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    /**
     * Accept one rating from the customer who completed the booking.
     */
    public function store(RatingStoreRequest $request): JsonResponse
    {
        $booking = Booking::query()
            ->with('customer')
            ->where('booking_number', $request->string('booking_number')->toString())
            ->where('status', 'completed')
            ->firstOrFail();

        $emailMatches = mb_strtolower($booking->customer->email)
            === mb_strtolower($request->string('guest_email')->toString());

        $phoneMatches = ! $request->filled('phone')
            || $this->normalizePhone($booking->customer->phone_number)
                === $this->normalizePhone($request->string('phone')->toString());

        if (! $emailMatches || ! $phoneMatches) {
            throw ValidationException::withMessages([
                'guest_email' => 'The booking contact information does not match.',
            ]);
        }

        $rateKey = 'rating:' . $booking->id . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            throw ValidationException::withMessages([
                'rating' => 'Too many rating attempts were made. Please try again later.',
            ]);
        }
        RateLimiter::hit($rateKey, 3600);

        if (RoomRating::query()->where('booking_id', $booking->id)->exists()) {
            throw ValidationException::withMessages([
                'booking_number' => 'This booking already has a rating.',
            ]);
        }

        RoomRating::create([
            'booking_id' => $booking->id,
            'rating' => $request->integer('rating'),
            'comment' => $request->filled('comment')
                ? trim($request->string('comment')->toString())
                : null,
            'is_verified' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you. Your rating is awaiting moderation.',
            'average_rating' => round($booking->room->averageRating(), 1),
            'total_ratings' => $booking->room->totalRatings(),
        ]);
    }

    /**
     * Return approved ratings without exposing customer contact details.
     */
    public function getRoomRatings(int $roomId): JsonResponse
    {
        $room = Room::query()->available()->findOrFail($roomId);

        $ratings = $room->ratings()
            ->where('is_verified', true)
            ->latest('room_ratings.created_at')
            ->limit(10)
            ->get(['room_ratings.id', 'rating', 'comment', 'room_ratings.created_at']);

        return response()->json([
            'average_rating' => round($room->averageRating(), 1),
            'total_ratings' => $room->totalRatings(),
            'ratings' => $ratings,
        ]);
    }

    private function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D+/', '', (string) $phone) ?? '';
    }
}
