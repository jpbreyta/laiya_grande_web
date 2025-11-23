<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Booking;

class SearchController extends Controller
{
    /**
     * Display the search page.
     */
    public function index()
    {
        return view('user.search.index');
    }

    /**
     * Search for reservation or booking by code.
     */
    public function searchByCode(Request $request)
    {
        $request->validate([
            'reservation_code' => 'required|string'
        ]);

        $reservationCode = $request->reservation_code;

        // First check reservations table
        $reservation = Reservation::with('room')
            ->where('reservation_number', $reservationCode)
            ->first();

        if ($reservation) {
            return response()->json([
                'success' => true,
                'type' => 'reservation',
                'data' => $reservation
            ]);
        }

        // If not found in reservations, check bookings table
        $booking = Booking::with('room')
            ->where('reservation_number', $reservationCode)
            ->first();

        if ($booking) {
            return response()->json([
                'success' => true,
                'type' => 'booking',
                'data' => $booking
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No reservation or booking found with the provided code.'
        ]);
    }

    /**
     * Show detailed view of a reservation or booking.
     */
    public function show($id, $type)
    {
        if ($type === 'reservation') {
            $data = Reservation::with('room', 'payments')->findOrFail($id);
        } elseif ($type === 'booking') {
            $data = Booking::with('room', 'payments')->findOrFail($id);
        } else {
            abort(404);
        }

        return view('user.search.view', compact('data', 'type'));
    }

    /**
     * Show continue payment page for reservation.
     */
    public function continuePayment($id, $type)
    {
        if ($type !== 'reservation') {
            abort(404, 'Only reservations can continue payment');
        }

        $reservation = Reservation::with('room')->findOrFail($id);

        // Check if reservation is still pending (not paid or confirmed)
        if ($reservation->status !== 'pending') {
            return redirect()->route('search.show', [$id, $type])
                ->with('error', 'This reservation has already been paid or confirmed.');
        }

        return view('user.search.continue', compact('reservation'));
    }
}
