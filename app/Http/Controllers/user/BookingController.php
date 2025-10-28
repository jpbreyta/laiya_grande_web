<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Show all available rooms and handle filters/search.
     */
    public function index(Request $request)
    {
        $query = Room::query();

        /* Filter by guest capacity */
        if ($request->filled('guests')) {
            $query->where('capacity', '>=', $request->guests);
        }

        /* Filter by price range */
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }

        /* Filter by availability based on date overlap */
        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            $query->whereDoesntHave('bookings', function ($bookingQuery) use ($checkIn, $checkOut) {
                $bookingQuery->where(function ($dateFilter) use ($checkIn, $checkOut) {
                    $dateFilter
                        ->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in', '<=', $checkIn)
                              ->where('check_out', '>=', $checkOut);
                        });
                });
            });
        }

        $rooms = $query->where('availability', '>', 0)->get();
        return view('user.booking.index', compact('rooms'));
    }

    /**
     * Add room to booking cart (session-based)
     */
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $roomId = $request->room_id;

        // If room already exists in cart, increase quantity
        if(isset($cart[$roomId])) {
            $cart[$roomId]['quantity'] += $request->quantity;
        } else {
            $cart[$roomId] = [
                'room_id' => $roomId,
                'room_name' => $request->room_name,
                'room_price' => $request->room_price,
                'quantity' => $request->quantity,
            ];
        }

        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart' => $cart]);
    }



    /**
     * Remove item from cart
     */
    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->room_id])) {
            unset($cart[$request->room_id]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    /**
     * Show confirm booking page (Step 2)
     */
    public function showConfirmBooking(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255', 
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('booking.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $cart_total = 0;
        foreach ($cart as $item) {
            $cart_total += $item['room_price'] * $item['quantity'];
        }
        $tax = $cart_total * 0.12;
        $total = $cart_total + $tax;

        // Calculate nights
        $check_in_date = new \DateTime($request->check_in);
        $check_out_date = new \DateTime($request->check_out);
        $nights = $check_out_date->diff($check_in_date)->days;
        if ($nights <= 0) $nights = 1;

        return view('user.booking.confirmbooking', compact(
            'request', 'cart', 'cart_total', 'tax', 'total', 'nights'
        ));
    }

    /**
     * Process and finalize booking (Step 3)
     */
    public function processBooking(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'agree_terms' => 'required|accepted',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('booking.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cart as $item) {
            // Save booking to database
            Booking::create([
                'room_id' => $item['room_id'],
                'user_id' => Auth::check() ? Auth::id() : 1,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'total_price' => $item['room_price'] * $item['quantity'],
            ]);

            /** @var \App\Models\Room $room */
            $room = Room::findOrFail($item['room_id']);

            // Reduce availability
            $room->availability -= $item['quantity'];
            $room->save();
        }

        session()->forget('cart'); // Clear cart after booking
        return response()->json(['success' => true, 'message' => 'Booking confirmed!']);
    }

/**
 * Finalize booking and save to DB
 */
public function confirmBooking(Request $request)
{
    $cart = session()->get('cart', []);

    if (empty($cart)) {
        return response()->json(['success' => false, 'message' => 'Cart is empty.']);
    }

    foreach ($cart as $item) {

        // Save booking to database
        Booking::create([
            'room_id' => $item['room_id'],
            'user_id' => Auth::check() ? Auth::id() : 1,
            'check_in' => $request->check_in ?? now(),
            'check_out' => $request->check_out ?? now()->addDays(2),
            'total_price' => $item['room_price'] * $item['quantity'],
        ]);

        /** @var \App\Models\Room $room */
        $room = Room::findOrFail($item['room_id']);

        // Reduce availability
        $room->availability -= $item['quantity'];
        $room->save();
    }

    session()->forget('cart'); // Clear cart after booking
    return response()->json(['success' => true, 'message' => 'Booking confirmed!']);
}
}
