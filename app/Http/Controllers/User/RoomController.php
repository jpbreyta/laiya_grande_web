<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\RoomSearchRequest;
use App\Models\Room;
use App\Services\Booking\RoomAvailabilityService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(
        private readonly RoomAvailabilityService $availability
    ) {
    }

    /**
     * List rooms using normalized rates and inventory-aware availability.
     */
    public function index(RoomSearchRequest $request): View
    {
        if ($request->filled(['check_in', 'check_out'])) {
            session([
                'booking_check_in' => $request->date('check_in')->toDateString(),
                'booking_check_out' => $request->date('check_out')->toDateString(),
            ]);
        }

        $checkInValue = session('booking_check_in');
        $checkOutValue = session('booking_check_out');
        $checkIn = $checkInValue ? Carbon::parse($checkInValue) : null;
        $checkOut = $checkOutValue ? Carbon::parse($checkOutValue) : null;
        $nights = $checkIn && $checkOut ? max(1, $checkIn->diffInDays($checkOut)) : 1;

        $query = Room::query()
            ->available()
            ->with([
                'activeRates' => function ($rates) use ($checkIn): void {
                    $rates->when($checkIn, fn ($query) => $query->effectiveOn($checkIn))
                        ->orderBy('price');
                },
                'roomImages',
                'amenities',
            ])
            ->withAvg([
                'ratings as average_rating' => fn (Builder $ratings) => $ratings->where('is_verified', true),
            ], 'rating')
            ->withCount([
                'ratings as total_ratings' => fn (Builder $ratings) => $ratings->where('is_verified', true),
            ]);

        if ($request->filled('guests')) {
            $query->where('capacity', '>=', $request->integer('guests'));
        }

        if ($request->filled('min_price') || $request->filled('max_price') || $request->filled('rate_type')) {
            $query->whereHas('activeRates', function (Builder $rates) use ($request, $checkIn): void {
                $rates->when($checkIn, fn (Builder $query) => $query->effectiveOn($checkIn))
                    ->when($request->filled('min_price'), fn (Builder $query) => $query->where('price', '>=', $request->input('min_price')))
                    ->when($request->filled('max_price'), fn (Builder $query) => $query->where('price', '<=', $request->input('max_price')))
                    ->when($request->filled('rate_type'), fn (Builder $query) => $query->where('rate_type', $request->string('rate_type')->toString()));
            });
        }

        if ($checkIn && $checkOut) {
            $this->availability->applyAvailableBetween($query, $checkIn, $checkOut);
        }

        $cartRoomIds = array_map('intval', array_keys(session('cart', [])));

        $rooms = $query
            ->whereNotIn('rooms.id', $cartRoomIds)
            ->orderBy('name')
            ->get()
            ->each(function (Room $room): void {
                $room->setAttribute('average_rating', round((float) $room->average_rating, 1));
            });

        return view('user.rooms.index', compact('rooms', 'checkInValue', 'checkOutValue', 'nights'))
            ->with('checkIn', $checkInValue)
            ->with('checkOut', $checkOutValue);
    }

    /**
     * Show one room with active rates and approved ratings only.
     */
    public function show(int $id): View
    {
        $room = Room::query()
            ->available()
            ->with([
                'activeRates' => fn ($query) => $query->effectiveOn(today())->orderBy('price'),
                'roomImages',
                'amenities',
            ])
            ->findOrFail($id);

        $room->setAttribute('average_rating', round($room->averageRating(), 1));
        $room->setAttribute('total_ratings', $room->totalRatings());
        $approvedRatings = $room->ratings()
            ->where('is_verified', true)
            ->latest('room_ratings.created_at')
            ->limit(10)
            ->get(['room_ratings.id', 'room_ratings.booking_id', 'rating', 'comment', 'room_ratings.created_at']);

        // Preserve the old $room->ratings view property with approved records only.
        $room->setRelation('ratings', $approvedRatings);
        $room->setAttribute('approved_ratings', $approvedRatings);

        return view('user.rooms.show', compact('room'));
    }
}
