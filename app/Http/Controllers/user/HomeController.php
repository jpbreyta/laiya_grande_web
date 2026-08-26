<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display available rooms without running one query per room.
     */
    public function index(): View
    {
        $cartRoomIds = array_map('intval', array_keys(session('cart', [])));

        $rooms = Room::query()
            ->available()
            ->whereNotIn('id', $cartRoomIds)
            ->with([
                'activeRates' => fn ($query) => $query->effectiveOn(today())->orderBy('price'),
                'roomImages',
            ])
            ->withAvg([
                'ratings as average_rating' => fn (Builder $query) => $query->where('is_verified', true),
            ], 'rating')
            ->withCount([
                'ratings as total_ratings' => fn (Builder $query) => $query->where('is_verified', true),
            ])
            ->orderBy('name')
            ->get()
            ->each(function (Room $room): void {
                $room->setAttribute('image_url', $room->image
                    ? asset($room->image)
                    : asset('images/user/luxury-ocean-view-suite-hotel-room.jpg'));
                $room->setAttribute('average_rating', round((float) $room->average_rating, 1));
            });

        return view('user.home.index', [
            'settings' => GeneralSetting::instance(),
            'rooms' => $rooms,
        ]);
    }

    public function cart(): View
    {
        return view('user.cart.index', [
            'cart' => session('cart', []),
        ]);
    }
}
