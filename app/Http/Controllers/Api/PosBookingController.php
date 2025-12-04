<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Customer;
use Carbon\Carbon;

class PosBookingController extends Controller
{
    /**
     * GET /api/rooms
     * List all available rooms, optionally filtered by guests.
     */
    public function rooms(Request $request)
    {
        $query = Room::query()->where('availability', '>', 0);

        if ($request->filled('guests')) {
            $query->where('capacity', '>=', $request->guests);
        }

        $rooms = $query->get(['id', 'name', 'capacity', 'price', 'availability']);

        return response()->json([
            'success' => true,
            'rooms' => $rooms
        ]);
    }

    /**
     * POST /api/bookings
     * Create a POS booking (walk-in)
     */
    public function create(Request $request)
    {
        // Validate input
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'payment_method' => 'nullable|string|in:cash,gcash,paymaya,bank_transfer',
            'special_request' => 'nullable|string|max:255',
        ]);

        $room = Room::findOrFail($request->room_id);

        // Check availability
        if ($room->availability < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Selected room is not available.'
            ], 400);
        }

        // Calculate total price
        $nights = Carbon::parse($request->check_in)->diffInDays(Carbon::parse($request->check_out));
        $totalPrice = $nights * $room->price;

        // Create or find customer
        $customer = Customer::firstOrCreate(
            ['email' => $request->email ?? 'walkin_' . time() . '@pos.local'],
            [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phone_number' => $request->phone_number,
            ]
        );

        // Create booking
        $booking = Booking::create([
            'reservation_number' => $this->generateReservationNumber(),
            'room_id' => $room->id,
            'customer_id' => $customer->id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'number_of_guests' => $request->number_of_guests,
            'special_request' => $request->special_request ?? null,
            'payment_method' => $request->payment_method ?? 'cash',
            'payment' => 'full',
            'total_price' => $totalPrice,
            'source' => 'pos',
            'status' => 'confirmed',
        ]);

        // Reduce room availability
        $room->decrement('availability');

        // Create payment record
        Payment::create([
            'booking_id' => $booking->id,
            'customer_name' => $customer->firstname . ' ' . $customer->lastname,
            'contact_number' => $customer->phone_number,
            'payment_date' => now(),
            'amount_paid' => $totalPrice,
            'payment_stage' => 'full',
            'status' => 'verified',
            'payment_method' => $request->payment_method ?? 'cash',
            'notes' => 'POS walk-in booking',
        ]);

        // Optional: notify admin
        Notification::create([
            'type' => 'booking',
            'title' => 'New POS Booking',
            'message' => "New walk-in booking from {$customer->firstname} {$customer->lastname}",
            'data' => ['booking_id' => $booking->id],
            'read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Walk-in booking confirmed!',
            'booking' => $booking->load('customer', 'room')
        ]);
    }
    private function generateReservationNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $reservationNumber = 'POS-' . $date . '-' . $random;
        } while (
            Booking::where('reservation_number', $reservationNumber)->exists()
        );

        return $reservationNumber;
    }
}
