<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\GuestStay;
use Zxing\QrReader; // Make sure: composer require khanamiryan/qrcode-detector-decoder
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

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

        // Try to extract booking ID from multi-line QR code format
        // Format: "LAIYA GRANDE BOOKING\nBooking ID: 123\nReservation Code: RSV-..."
        $bookingId = null;
        $reservationCode = null;

        // Extract Booking ID
        if (preg_match('/Booking ID:\s*(\d+)/i', $qrCode, $matches)) {
            $bookingId = $matches[1];
        }

        // Extract Reservation Code
        if (preg_match('/Reservation Code:\s*([^\n]+)/i', $qrCode, $matches)) {
            $reservationCode = trim($matches[1]);
            if ($reservationCode === 'N/A') {
                $reservationCode = null;
            }
        }

        // Also try to extract simple reservation number format
        if (!$bookingId && !$reservationCode) {
            if (preg_match('/(BK|RSV)-\d{14}-[A-Z0-9]{6}/', $qrCode, $matches)) {
                $reservationCode = $matches[0];
            }
        }

        // Try to find booking by ID or reservation number
        $booking = null;
        
        if ($bookingId) {
            $booking = Booking::with('room')->where('id', $bookingId)->first();
        }
        
        if (!$booking && $reservationCode) {
            $booking = Booking::with('room')->where('reservation_number', $reservationCode)->first();
        }
        
        // Fallback: try direct match
        if (!$booking) {
            $booking = Booking::with('room')
                ->where('reservation_number', $qrCode)
                ->orWhere('id', $qrCode)
                ->first();
        }

        if (!$booking) {
            return response()->json([
                'success' => false, 
                'message' => 'Booking not found for this QR code.',
                'debug' => [
                    'booking_id' => $bookingId,
                    'reservation_code' => $reservationCode,
                    'raw_qr' => substr($qrCode, 0, 200)
                ]
            ]);
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

        return response()->json([
            'success' => true, 
            'message' => 'Guest checked-in successfully!',
            'booking_id' => $booking->id
        ]);
    }

    /**
     * Process manual reservation code submission
     */
    public function manual(Request $request)
    {
        $request->validate([
            'reservation_code' => 'required|string'
        ]);

        $code = trim($request->reservation_code);
        
        $booking = Booking::with('room')
            ->where('reservation_number', $code)
            ->orWhere('id', $code)
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

        // Extract booking ID from QR code text
        $bookingId = null;
        $reservationCode = null;

        if (preg_match('/Booking ID:\s*(\d+)/i', $decodedText, $matches)) {
            $bookingId = $matches[1];
        }

        if (preg_match('/Reservation Code:\s*([^\n]+)/i', $decodedText, $matches)) {
            $reservationCode = trim($matches[1]);
            if ($reservationCode === 'N/A') {
                $reservationCode = null;
            }
        }

        $booking = null;
        
        if ($bookingId) {
            $booking = Booking::with('room')->where('id', $bookingId)->first();
        }
        
        if (!$booking && $reservationCode) {
            $booking = Booking::with('room')->where('reservation_number', $reservationCode)->first();
        }
        
        if (!$booking) {
            $booking = Booking::with('room')
                ->where('reservation_number', $decodedText)
                ->orWhere('id', $decodedText)
                ->first();
        }

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

    /**
     * Generate voucher preview for printing
     */
    public function previewPdf($id)
    {
        // Force fresh query to ensure we get the correct booking
        $booking = Booking::with(['room', 'customer', 'paymentRecord'])->findOrFail($id);
        

        // Generate QR code with same format as email for consistency
        // Use the ACTUAL customer data from the relationship
        $guestName = 'Guest'; // Default
        
        if ($booking->customer) {
            $guestName = trim(($booking->customer->firstname ?? '') . ' ' . ($booking->customer->lastname ?? ''));
            if (empty($guestName)) {
                $guestName = 'Guest';
            }
        }
        
        $qrString =
            "LAIYA GRANDE BOOKING\n" .
            "Booking ID: {$booking->id}\n" .
            "Reservation Code: " . ($booking->reservation_number ?? 'N/A') . "\n" .
            "Guest: {$guestName}\n" .
            "Check-in: {$booking->check_in}\n" .
            "Check-out: {$booking->check_out}\n" .
            "Room ID: {$booking->room_id}\n" .
            "Total: PHP " . number_format($booking->total_price, 2, '.', '');
        
        // Prepare data for voucher
        $booking_id_display = $booking->reservation_number ?? 'BK-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT);
        $deposited_amount = $booking->paymentRecord->amount_paid ?? 0;
        $remaining_balance = $booking->total_price - $deposited_amount;
        $prepared_by = Auth::user()->name ?? 'ADMIN';
        
        return view('admin.qr.voucher', compact(
            'booking',
            'booking_id_display',
            'deposited_amount',
            'remaining_balance',
            'prepared_by',
            'qrString',
            'guestName'
        ));
    }
}
