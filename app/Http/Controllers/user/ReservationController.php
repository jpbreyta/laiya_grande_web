<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;

class ReservationController extends Controller
{
    /**
     * Display all reservations.
     */
    public function index()
    {
        $reservations = Reservation::with('room')->latest()->get();
        return view('user.reservation.index', compact('reservations'));
    }

    /**
     * Show form to create new reservation.
     */
    public function create()
    {
        $rooms = Room::all();
        return view('user.reservation.create', compact('rooms'));
    }

    /**
     * Store a new reservation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
            'payment' => 'nullable|image|max:2048',
        ]);

        // Handle payment image upload if provided
        if ($request->hasFile('payment')) {
            $validated['payment'] = $request->file('payment')->store('payments', 'public');
        }

        // You could compute total price here based on room price and stay duration
        $room = Room::findOrFail($validated['room_id']);
        $days = (strtotime($validated['check_out']) - strtotime($validated['check_in'])) / 86400;
        $validated['total_price'] = $room->price_per_night * max($days, 1);

        Reservation::create($validated);

        return redirect()->route('user.reservations.index')->with('success', 'Reservation created successfully!');
    }

    /**
     * Show a specific reservation.
     */
    public function show($id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        return view('user.reservation.show', compact('reservation'));
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $rooms = Room::all();
        return view('user.reservation.edit', compact('reservation', 'rooms'));
    }

    /**
     * Update reservation data.
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|email',
            'phone_number' => 'required|numeric',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_of_guests' => 'required|integer|min:1',
            'special_request' => 'nullable|string',
            'payment' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('payment')) {
            $validated['payment'] = $request->file('payment')->store('payments', 'public');
        }

        $room = Room::findOrFail($validated['room_id']);
        $days = (strtotime($validated['check_out']) - strtotime($validated['check_in'])) / 86400;
        $validated['total_price'] = $room->price_per_night * max($days, 1);

        $reservation->update($validated);

        return redirect()->route('user.reservations.index')->with('success', 'Reservation updated successfully!');
    }

    /**
     * Delete a reservation.
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('user.reservations.index')->with('success', 'Reservation deleted successfully!');
    }
}
