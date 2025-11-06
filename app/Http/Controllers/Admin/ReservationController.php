<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;

class ReservationController extends Controller
{
    /**
     * Display all reservations
     */
    public function index()
    {
        $reservations = Reservation::with('room')->latest()->get();
        return view('admin.reservation.index', compact('reservations'));
    }

    /**
     * Show single reservation details
     */
    public function show($id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        return view('admin.reservation.show', compact('reservation'));
    }

    /**
     * Show the form for editing a reservation
     */
    public function edit($id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        $rooms = Room::all();
        return view('admin.reservation.edit', compact('reservation', 'rooms'));
    }

    /**
     * Update a reservation
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'room_id' => 'required|exists:rooms,id',
            'number_of_guests' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,paid,cancelled',
            'total_price' => 'required|numeric|min:0',
            'special_request' => 'nullable|string',
        ]);

        $reservation->update($validated);

        return redirect()->route('admin.reservation.show', $reservation->id)
            ->with('success', 'Reservation updated successfully.');
    }

    /**
     * Approve reservation
     */
    public function approve($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status === 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'This reservation is already confirmed.'
            ]);
        }

        $reservation->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'Reservation confirmed successfully.'
        ]);
    }

    /**
     * Cancel reservation
     */
    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'This reservation is already cancelled.'
            ]);
        }

        $reservation->update(['status' => 'cancelled']);

        // Restore room availability
        if ($reservation->room) {
            $reservation->room->availability += 1;
            $reservation->room->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Reservation has been cancelled successfully.'
        ]);
    }
}
