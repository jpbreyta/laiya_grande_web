<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Room;
use App\Mail\BookingConfirmationMail;

class BookingController extends Controller
{
    /**
     * Display all bookings
     */
    public function index()
    {
        $bookings = Booking::with('room')->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

    /**
     * Show single booking details
     */
    public function show($id)
    {
        $booking = Booking::with('room')->findOrFail($id);
        return view('admin.booking.show', compact('booking'));
    }
    public function edit($id)
    {
        $booking = Booking::with('room')->findOrFail($id);
        $rooms = Room::all();
        return view('admin.booking.edit', compact('booking', 'rooms'));
    }

    /**
     * Update a booking
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'room_id' => 'required|exists:rooms,id',
            'number_of_guests' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled',
            'total_price' => 'required|numeric|min:0',
            'special_request' => 'nullable|string',
        ]);

        $booking->update($validated);

        return redirect()->route('admin.booking.show', $booking->id)
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Approve booking: update status, send email, send SMS
     */
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'This booking is already confirmed.'
            ]);
        }

        $booking->update(['status' => 'confirmed']);


        // Send confirmation email
        try {
            $bookingDetails = [
                'guest_name' => $booking->firstname . ' ' . $booking->lastname,
                'guest_email' => $booking->email,
                'guest_phone' => $booking->phone_number,
                'guests' => $booking->number_of_guests,
            ];

            Mail::to($booking->email)->send(new BookingConfirmationMail($booking, $bookingDetails));

            Log::info('Booking confirmation email sent successfully', [
                'booking_id' => $booking->id,
                'email' => $booking->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Send SMS via IPROG
        try {
            $apiUrl = env('IPROG_SMS_API_URL', 'https://sms.iprogtech.com/api/v1/sms_messages');
            $token  = env('IPROG_SMS_API_TOKEN');
            $phone  = $booking->phone_number;

            // Convert to international format if starts with 0 (Philippines)
            if (Str::startsWith($phone, '0')) {
                $phone = '63' . substr($phone, 1);
            }

            $message = "Hi {$booking->firstname}, your booking is confirmed! Check your email for details and QR code.";

            $payload = [
                'api_token'    => $token,
                'phone_number' => $phone,
                'message'      => $message,
                'sender_id'    => 'LaiyaGrande',
            ];

            $response = Http::post($apiUrl, $payload);

            Log::info('IPROG SMS response', [
                'booking_id' => $booking->id,
                'response'   => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send SMS via IPROG', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed – email sent and SMS triggered.'
        ]);
    }


    /**
     * Reject / Cancel booking
     */
    public function reject($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'This booking is already cancelled.'
            ]);
        }

        $booking->update(['status' => 'cancelled']);
        $room = Room::find($booking->room_id);
        if ($room) {
            $room->availability += 1;
            $room->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking has been cancelled successfully.'
        ]);
    }
}
