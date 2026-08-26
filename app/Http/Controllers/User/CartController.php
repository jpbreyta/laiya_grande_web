<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CartAddRequest;
use App\Services\Booking\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cart
    ) {
    }

    /**
     * Add a server-validated room rate to the cart.
     */
    public function add(CartAddRequest $request): JsonResponse
    {
        $items = $this->cart->add(
            roomId: $request->integer('room_id'),
            roomRateId: $request->filled('room_rate_id') ? $request->integer('room_rate_id') : null,
            quantity: $request->integer('quantity', 1)
        );

        return response()->json([
            'success' => true,
            'message' => 'Room added to cart.',
            'cart' => $items,
        ]);
    }

    public function index(): View
    {
        return view('user.cart.index', [
            'cart' => $this->cart->all(),
        ]);
    }

    public function remove(int $roomId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Room removed from cart.',
            'cart' => $this->cart->remove($roomId),
        ]);
    }

    public function increment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer'],
        ]);

        $roomId = (int) $validated['room_id'];
        $current = (int) ($this->cart->all()[$roomId]['quantity'] ?? 0);
        $items = $this->cart->setQuantity($roomId, $current + 1);

        return response()->json([
            'success' => true,
            'quantity' => $items[$roomId]['quantity'] ?? 0,
        ]);
    }

    public function decrement(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer'],
        ]);

        $roomId = (int) $validated['room_id'];
        $current = (int) ($this->cart->all()[$roomId]['quantity'] ?? 0);
        $items = $this->cart->setQuantity($roomId, $current - 1);

        return response()->json([
            'success' => true,
            'quantity' => $items[$roomId]['quantity'] ?? 0,
        ]);
    }

    public function getCartDetails(): JsonResponse
    {
        return response()->json($this->cart->summary());
    }
}
