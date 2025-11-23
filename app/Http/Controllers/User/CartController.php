<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $room = Room::findOrFail($request->room_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$room->id])) {

            if ($cart[$room->id]['quantity'] + 1 > $room->availability) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$room->availability} room(s) available."
                ]);
            }

            $cart[$room->id]['quantity'] += 1;

            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => "Room quantity updated."
            ]);
        }

        $cart[$room->id] = [
            'room_id'     => $room->id,
            'room_name'   => $room->name,
            'room_price'  => $room->price,
            'quantity'    => 1,
            'availability' => $room->availability,
        ];

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => "Room added to cart."
        ]);
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('user.cart.index', compact('cart'));
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Room removed from cart.');
    }
    public function getImagesAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }
    public function increment(Request $request)
    {
        $cart = session()->get('cart', []);
        $roomId = $request->room_id;

        if (isset($cart[$roomId])) {
            $cart[$roomId]['quantity'] += 1;
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true, 'quantity' => $cart[$roomId]['quantity']]);
    }

    public function decrement(Request $request)
    {
        $cart = session()->get('cart', []);
        $roomId = $request->room_id;

        if (isset($cart[$roomId]) && $cart[$roomId]['quantity'] > 1) {
            $cart[$roomId]['quantity'] -= 1;
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true, 'quantity' => $cart[$roomId]['quantity']]);
    }
}
