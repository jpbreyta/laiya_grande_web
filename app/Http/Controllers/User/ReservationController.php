<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display user reservations lookup page.
     */
    public function index()
    {
        return view('user.reserve.reservation');
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer',
            'payment' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('payment')) {
            $validated['first_payment'] = $request->file('payment')->store('payments', 'public');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'No rooms selected for reservation.'
            ]);
        }

        // Validate guest capacity against room capacities
        $totalGuests = $validated['guests'];
        $totalCapacity = 0;
        foreach ($cart as $item) {
            $room = Room::find($item['room_id']);
            if (!$room) continue;
            $totalCapacity += $room->capacity * $item['quantity'];
        }

        if ($totalGuests > $totalCapacity) {
            return response()->json([
                'success' => false,
                'message' => "Number of guests ({$totalGuests}) exceeds total room capacity ({$totalCapacity}). Please adjust your guest count or select rooms with higher capacity."
            ]);
        }

        // Check capacity limits before proceeding
        foreach ($cart as $item) {
            $room = Room::find($item['room_id']);
            if (!$room) continue;
            if ($room->availability < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient availability for {$room->name}. Only {$room->availability} room(s) left."
                ]);
            }
        }

        $reservations = collect();
        foreach ($cart as $item) {
            $room = Room::find($item['room_id']);
            if (!$room) continue;

            $days = max(1, (strtotime($request->check_out) - strtotime($request->check_in)) / 86400);
            $totalPrice = $room->price * $item['quantity'] * $days;

            $reservation = Reservation::create([
                'room_id' => $room->id,
                'firstname' => $validated['first_name'],
                'lastname' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone'],
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'number_of_guests' => $validated['guests'],
                'special_request' => $validated['special_requests'] ?? null,
                'payment_method' => $validated['payment_method'],
                'first_payment' => $validated['first_payment'] ?? null,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'expires_at' => Carbon::now()->addHours(24),
                'reservation_number' => $this->generateReservationNumber(),
            ]);

            $reservations->push($reservation);

            // Reduce room availability
            $room->availability -= $item['quantity'];
            $room->save();
        }

        // Create notification for admin
        \App\Models\Notification::create([
            'type' => 'reservation',
            'title' => 'New Reservation Request',
            'message' => "New reservation from {$validated['first_name']} {$validated['last_name']} for {$reservations->count()} room(s)",
            'data' => [
                'reservation_id' => $reservations->first()->id,
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'guests' => $validated['guests'],
            ],
            'read' => false,
        ]);

        // Clear cart
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'reservation_ids' => collect($reservations)->pluck('id')->toArray(),
        ]);
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
            'phone_number' => 'required|string|max:15',
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
        $days = max(1, (strtotime($validated['check_out']) - strtotime($validated['check_in'])) / 86400);
        $validated['total_price'] = $room->price * $days;

        $reservation->update($validated);

        return redirect()->route('user.reservation.index')->with('success', 'Reservation updated successfully!');
    }

    /**
     * Delete a reservation.
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('user.reservation.index')->with('success', 'Reservation deleted successfully!');
    }

    /**
     * Continue payment for pending reservation.
     */
    public function continuePaying(Request $request, $id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);

        if ($request->isMethod('POST')) {
            $request->validate([
                'email' => 'required|email',
                'phone_number' => 'required|string|max:15',
            ]);

            if ($request->email !== $reservation->email || $request->phone_number != $reservation->phone_number) {
                return back()->withErrors(['Invalid credentials to access this reservation.']);
            }

            // Store verification in session
            session(['reservation_verified_' . $id => true]);
            return redirect()->route('user.reservation.continue', $id);
        }

        return view('user.search.continue', compact('reservation'));
    }

    /**
     * Update payment for reservation.
     */
    public function updatePayment(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'payment_method' => 'required|in:gcash,paymaya,bank_transfer',
            'payment' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('payment')) {
            $validated['second_payment'] = $request->file('payment')->store('payments', 'public');
        }

        $validated['status'] = 'paid';
        $reservation->update($validated);

        // Clear the verification session
        session()->forget('reservation_verified_' . $id);

        return response()->json([
            'success' => true,
            'message' => 'Payment submitted successfully! Your reservation is now being processed.',
            'redirect' => route('home')
        ]);
    }

    private function generateReservationNumber(): string
    {
        do {
            $date = Carbon::now()->format('YmdHis');
            $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
            $reservationNumber = 'RSV-' . $date . '-' . $random;
        } while (\App\Models\Booking::where('reservation_number', $reservationNumber)->exists() ||
                 Reservation::where('reservation_number', $reservationNumber)->exists());

        return $reservationNumber;
    }

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
