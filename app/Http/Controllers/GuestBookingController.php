<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestBooking;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // QR generation package

class GuestBookingController extends Controller
{
    public function showForm()
    {
        return view('register');
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'contact_number' => 'required|digits_between:10,15',
            'arrival_date' => 'required|date',
            'total_guests' => 'required|integer|min:1',
            'room_names' => 'required|string',
            'balance_mode' => 'required|string',
        ]);

        // Simulate OTP verification
        $otpVerified = true; // For now, you can integrate SMS API later

        // Generate QR code content
        $qrContent = "Guest: {$request->guest_name}, Arrival: {$request->arrival_date}";
        $qrCodePath = 'qrcodes/' . time() . '.png';
        QrCode::format('png')->size(200)->generate($qrContent, public_path($qrCodePath));

        // Save booking
        GuestBooking::create([
            'guest_name' => $request->guest_name,
            'contact_number' => $request->contact_number,
            'arrival_date' => $request->arrival_date,
            'total_guests' => $request->total_guests,
            'room_names' => $request->room_names,
            'car_plate' => $request->car_plate,
            'balance_mode' => $request->balance_mode,
            'key_deposit' => $request->key_deposit ?? 0,
            'eco_ticket' => $request->eco_ticket ? true : false,
            'parking' => $request->parking ? true : false,
            'cookwares' => $request->cookwares ? true : false,
            'videoke' => $request->videoke ? true : false,
            'others' => $request->others,
            'total_amount' => $request->total_amount ?? 0,
            'otp_verified' => $otpVerified,
            'qr_code' => $qrCodePath,
        ]);

        return redirect()->back()->with('success', 'Reservation successful! QR code generated.');
    }
}
