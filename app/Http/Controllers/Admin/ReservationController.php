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
     * Optional: Approve or cancel reservation
     */
    public function approve($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->status === 'confirmed') {
            return back()->with('info', 'Reservation already confirmed.');
        }
        $reservation->update(['status' => 'confirmed']);
        return back()->with('success', 'Reservation confirmed.');
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->status === 'cancelled') {
            return back()->with('info', 'Reservation already cancelled.');
        }
        $reservation->update(['status' => 'cancelled']);
        // Optional: restore room availability
        if ($reservation->room) {
            $reservation->room->availability += 1;
            $reservation->room->save();
        }
        return back()->with('success', 'Reservation cancelled.');
    }
}
