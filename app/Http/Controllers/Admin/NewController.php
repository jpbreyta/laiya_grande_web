<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Booking;
use Carbon\Carbon;

class NewController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('admin.new.index', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:reservation,booking',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'room_id' => 'required|exists:rooms,id',
            'number_of_guests' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'special_request' => 'nullable|string',
        ]);

        // Validate status per type
        if ($validated['type'] === 'reservation') {
            if (!in_array($validated['status'], ['pending', 'confirmed', 'paid', 'cancelled'], true)) {
                return back()->withErrors(['status' => 'Invalid status for reservation.'])->withInput();
            }
        } else {
            if (!in_array($validated['status'], ['pending', 'confirmed', 'cancelled'], true)) {
                return back()->withErrors(['status' => 'Invalid status for booking.'])->withInput();
            }
        }

        // Adjust room availability (decrement by 1)
        $room = Room::find($validated['room_id']);
        if ($room && $room->availability !== null && $room->availability > 0) {
            $room->availability -= 1;
            $room->save();
        }

        if ($validated['type'] === 'reservation') {
            $reservation = Reservation::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'room_id' => $validated['room_id'],
                'number_of_guests' => $validated['number_of_guests'],
                'status' => $validated['status'],
                'total_price' => $validated['total_price'],
                'special_request' => $validated['special_request'] ?? null,
                'reservation_number' => $this->generateReservationNumber(),
            ]);

            return redirect()->route('admin.reservation.show', $reservation->id)
                ->with('success', 'Walk-in reservation created successfully.');
        }

        $booking = Booking::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'room_id' => $validated['room_id'],
            'number_of_guests' => $validated['number_of_guests'],
            'status' => $validated['status'],
            'total_price' => $validated['total_price'],
            'special_request' => $validated['special_request'] ?? null,
            'reservation_number' => $this->generateBookingNumber(),
        ]);

        return redirect()->route('admin.booking.show', $booking->id)
            ->with('success', 'Walk-in booking created successfully.');
    }
    private function generateReservationNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $reservationNumber = 'RSV-' . $date . '-' . $random;
        } while (
            \App\Models\Booking::where('reservation_number', $reservationNumber)->exists() ||
            Reservation::where('reservation_number', $reservationNumber)->exists()
        );

        return $reservationNumber;
    }
    
    private function generateBookingNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $reservationNumber = 'BK-' . $date . '-' . $random;
        } while (
            Booking::where('reservation_number', $reservationNumber)->exists() ||
            \App\Models\Reservation::where('reservation_number', $reservationNumber)->exists()
        );

        return $reservationNumber;
    }

}
