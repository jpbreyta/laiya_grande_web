<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\GuestStay;
use Zxing\QrReader; // Make sure: composer require khanamiryan/qrcode-detector-decoder

class QRController extends Controller
{
    /**
     * Display QR scanner interface
     */
    public function scanner()
    {
        return view('admin.qr.scanner');
    }

    /**
     * Process QR code scan from camera (AJAX)
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $qrCode = trim($request->qr_code);

        // Extract valid reservation number if extra text exists
        if (preg_match('/(BK|RSV)-\d{14}-[A-Z0-9]{6}/', $qrCode, $matches)) {
            $qrCode = $matches[0];
        }

        $booking = Booking::with('room')->where('reservation_number', $qrCode)
            ->orWhere('id', $qrCode)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found for this QR code.']);
        }

        // Mark guest as checked-in
        $guestStay = GuestStay::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'guest_name' => $booking->firstname . ' ' . $booking->lastname,
                'room_id' => $booking->room_id,
                'status' => 'checked-in',
                'check_in_time' => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Guest checked-in successfully!']);
    }

    /**
     * Process manual reservation code submission
     */
    public function manual(Request $request)
    {
        $request->validate([
            'reservation_code' => 'required|string'
        ]);

        $booking = Booking::with('room')->where('reservation_number', $request->reservation_code)
            ->orWhere('id', $request->reservation_code)
            ->first();

        if (!$booking) {
            return back()->with('error', 'Booking not found.');
        }

        // Mark guest as checked-in
        $guestStay = GuestStay::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'guest_name' => $booking->firstname . ' ' . $booking->lastname,
                'room_id' => $booking->room_id,
                'status' => 'checked-in',
                'check_in_time' => now(),
            ]
        );

        return view('admin.qr.scanner', compact('booking'))->with('success','Booking found & checked-in!');
    }

    /**
     * Process QR image upload
     */
    public function upload(Request $request)
    {
        $request->validate([
            'qr_image' => 'required|image|max:2048'
        ]);

        $path = $request->file('qr_image')->getRealPath();
        $qrcode = new QrReader($path);
        $decodedText = $qrcode->text();

        if (!$decodedText) {
            return back()->with('error','QR code not detected in the image.');
        }

        $booking = Booking::with('room')->where('reservation_number', $decodedText)
            ->orWhere('id', $decodedText)
            ->first();

        if (!$booking) {
            return back()->with('error','Booking not found for this QR code.');
        }

        // Mark guest as checked-in
        $guestStay = GuestStay::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'guest_name' => $booking->firstname . ' ' . $booking->lastname,
                'room_id' => $booking->room_id,
                'status' => 'checked-in',
                'check_in_time' => now(),
            ]
        );

        return view('admin.qr.scanner', compact('booking'))->with('success','Booking found & checked-in!');
    }
}
