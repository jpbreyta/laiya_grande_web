<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Booking;
use App\Models\Otp;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function validateContactInformation(Request $request)
    {
        $request->validate([
            'reservation_code' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $code = $request->reservation_code;
        $email = strtolower($request->email);
        $phone = $this->normalizePhone($request->phone);

        $record = Reservation::where('reservation_number', $code)->first();
        $type = 'reservation';

        if (!$record) {
            $record = Booking::where('reservation_number', $code)->first();
            $type = 'booking';
        }

        if (!$record) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'Booking or reservation not found.'
            ]);
        }

        $dbEmail = strtolower($record->email ?? '');
        $dbPhone = $this->normalizePhone($record->phone_number ?? '');

        if ($email !== $dbEmail || $phone !== $dbPhone) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'The email or phone does not match our records.'
            ]);
        }

        // Check for existing, unused, unexpired OTP
        $otp = Otp::where('user_id', $record->id)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        // If no valid OTP exists, create a new one
        if (!$otp) {
            $otpCode = $this->generateOtp();
            $otp = Otp::create([
                'user_id' => $record->id,
                'type' => $type,
                'otp_code' => $otpCode,
                'email_sent' => false,
                'sms_sent' => false,
                'expires_at' => Carbon::now()->addMinutes(10),
                'used' => false,
            ]);

            $this->sendOtpSms($otp, $record->phone_number);
        }

        return redirect()->route('search.verifyOtpForm', ['reservation_code' => $code])
            ->with('alert', [
                'type' => 'success',
                'message' => 'OTP sent successfully. Please check your phone/email.'
            ]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'reservation_code' => 'required|string'
        ]);

        $code = $request->reservation_code;

        $record = Reservation::where('reservation_number', $code)->first();
        $type = 'reservation';

        if (!$record) {
            $record = Booking::where('reservation_number', $code)->first();
            $type = 'booking';
        }

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Booking or reservation not found.'
            ]);
        }

        // Generate a new OTP for resend
        $otpCode = $this->generateOtp();
        $otp = Otp::updateOrCreate(
            ['user_id' => $record->id, 'type' => $type],
            [
                'otp_code' => $otpCode,
                'email_sent' => false,
                'sms_sent' => false,
                'expires_at' => Carbon::now()->addMinutes(10),
                'used' => false,
            ]
        );

        $this->sendOtpSms($otp, $record->phone_number);

        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully.'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'reservation_code' => 'required|string',
            'otp' => 'required|string',
        ]);

        $code = $request->reservation_code;
        $otpInput = $request->otp;

        $record = Reservation::where('reservation_number', $code)->first();
        $type = 'reservation';

        if (!$record) {
            $record = Booking::where('reservation_number', $code)->first();
            $type = 'booking';
        }

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Booking or reservation not found.'
            ]);
        }

        $otp = Otp::where('user_id', $record->id)
            ->where('type', $type)
            ->where('otp_code', $otpInput)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP. Please resend OTP.'
            ]);
        }

        $otp->update(['used' => true]);
        Session::put("{$type}_verified_{$record->id}", true);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
            'redirect_url' => route('search.show', ['id' => $record->id, 'type' => $type])
        ]);
    }

    public function index()
    {
        return view('user.search.index');
    }

    public function searchByCode(Request $request)
    {
        $request->validate([
            'reservation_code' => 'required|string'
        ]);

        $code = $request->reservation_code;

        $record = Reservation::where('reservation_number', $code)->first()
            ?? Booking::where('reservation_number', $code)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'No reservation or booking found with the provided code.'
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('search.verifyInfo', ['reservation_code' => $code])
        ]);
    }

    public function show($id, $type)
    {
        $verifiedKey = "{$type}_verified_{$id}";

        if (!Session::has($verifiedKey)) {
            return redirect()->route('search.index')->with('alert', [
                'type' => 'error',
                'message' => 'Please verify your booking information first.'
            ]);
        }

        $data = $type === 'reservation'
            ? Reservation::with('room', 'payments')->findOrFail($id)
            : Booking::with('room', 'payments')->findOrFail($id);

        return view('user.search.view', compact('data', 'type'));
    }

    public function continuePayment($id, $type)
    {
        if ($type !== 'reservation') {
            return redirect()->route('search.index')->with('alert', [
                'type' => 'error',
                'message' => 'Booking payment continuation is not supported. Please contact support.'
            ]);
        }

        $reservation = Reservation::with('room')->findOrFail($id);

        if ($reservation->status !== 'pending') {
            return redirect()->route('search.show', [$id, $type])->with('alert', [
                'type' => 'error',
                'message' => 'This reservation has already been paid or confirmed.'
            ]);
        }

        return view('user.search.continue', compact('reservation'));
    }

    // Helper Methods

    private function generateOtp()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function normalizePhone($phone)
    {
        return preg_replace('/\D/', '', $phone);
    }

    private function sendOtpSms($otp, $phone)
    {
        try {
            $apiUrl = env('IPROG_SMS_API_URL');
            $token  = env('IPROG_SMS_API_TOKEN');

            $phone = $this->normalizePhone($phone);
            if (Str::startsWith($phone, '0')) {
                $phone = '63' . substr($phone, 1);
            }

            $message = "Your OTP is {$otp->otp_code}. It expires in 10 minutes.";

            $payload = [
                'api_token'    => $token,
                'phone_number' => $phone,
                'message'      => $message,
                'sender_id'    => 'LaiyaGrande',
            ];

            $response = Http::post($apiUrl, $payload);
            if ($response->successful()) {
                $otp->update(['sms_sent' => true]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send OTP SMS', [
                'user_id' => $otp->user_id,
                'otp_id' => $otp->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
