<?php

namespace App\Services\Booking;

use App\Models\Room;
use App\Models\RoomRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function __construct(
        private readonly RoomAvailabilityService $availability
    ) {
    }

    /**
     * Add or replace a room rate in the session cart.
     */
    public function add(int $roomId, ?int $roomRateId = null, int $quantity = 1): array
    {
        $room = Room::query()
            ->available()
            ->with(['activeRates', 'roomImages'])
            ->findOrFail($roomId);

        $rate = $this->resolveRate($room, $roomRateId);
        $quantity = max(1, min(20, $quantity));
        $cart = $this->all();
        $currentQuantity = (int) ($cart[$room->id]['quantity'] ?? 0);
        $newQuantity = $currentQuantity + $quantity;

        $this->validateQuantity($room, $newQuantity);

        $cart[$room->id] = [
            'room_id' => $room->id,
            'room_rate_id' => $rate->id,
            'room_name' => $room->name,
            'rate_name' => $rate->name,
            'room_price' => (float) $rate->price,
            'room_image' => $room->image
                ? asset($room->image)
                : asset('images/user/luxury-ocean-view-suite-hotel-room.jpg'),
            'quantity' => $newQuantity,
            'inventory_count' => $room->inventory_count,
        ];

        Session::put('cart', $cart);

        return $cart;
    }

    /**
     * Change a cart quantity after checking current room inventory.
     */
    public function setQuantity(int $roomId, int $quantity): array
    {
        $cart = $this->all();

        if (! isset($cart[$roomId])) {
            throw ValidationException::withMessages([
                'room_id' => 'The selected room is not in the cart.',
            ]);
        }

        if ($quantity <= 0) {
            unset($cart[$roomId]);
            Session::put('cart', $cart);

            return $cart;
        }

        $room = Room::query()->available()->findOrFail($roomId);
        $this->validateQuantity($room, min(20, $quantity));

        $cart[$roomId]['quantity'] = min(20, $quantity);
        Session::put('cart', $cart);

        return $cart;
    }

    /**
     * Remove a room from the session cart.
     */
    public function remove(int $roomId): array
    {
        $cart = $this->all();
        unset($cart[$roomId]);
        Session::put('cart', $cart);

        return $cart;
    }

    public function clear(): void
    {
        Session::forget('cart');
    }

    public function all(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Return totals calculated from server-controlled prices.
     */
    public function summary(): array
    {
        $cart = $this->all();
        $totalQuantity = 0;
        $subtotal = 0.0;

        foreach ($cart as $item) {
            $totalQuantity += (int) $item['quantity'];
            $subtotal += (float) $item['room_price'] * (int) $item['quantity'];
        }

        return [
            'cart' => $cart,
            'total_count' => $totalQuantity,
            'total_price' => $subtotal,
            'count' => count($cart),
        ];
    }

    private function resolveRate(Room $room, ?int $roomRateId): RoomRate
    {
        $query = $room->activeRates()->effectiveOn(today());

        $rate = $roomRateId !== null
            ? $query->whereKey($roomRateId)->first()
            : $query->orderBy('price')->first();

        if (! $rate) {
            throw ValidationException::withMessages([
                'room_rate_id' => 'No active room rate is available for the selected room.',
            ]);
        }

        return $rate;
    }

    private function validateQuantity(Room $room, int $quantity): void
    {
        $checkIn = Session::get('booking_check_in');
        $checkOut = Session::get('booking_check_out');

        $availableUnits = $room->inventory_count;

        if ($checkIn && $checkOut) {
            $availableUnits = $this->availability->availableUnits(
                $room,
                Carbon::parse($checkIn),
                Carbon::parse($checkOut)
            );
        }

        if ($quantity > $availableUnits) {
            throw ValidationException::withMessages([
                'quantity' => "Only {$availableUnits} room unit(s) are available.",
            ]);
        }
    }
}
