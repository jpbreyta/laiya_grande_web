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
use Illuminate\Support\Facades\Mail;
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
        $otpInput = $request->input('otp'); // Check if OTP is provided

        $record = Reservation::with('customer')->where('reservation_number', $code)->first();
        $type = 'reservation';

        if (!$record) {
            $record = Booking::with('customer')->where('reservation_number', $code)->first();
            $type = 'booking';
        }

        if (!$record) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking or reservation not found.'
                ], 404);
            }
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'Booking or reservation not found.'
            ]);
        }

        // Get email and phone from customer relationship
        $dbEmail = strtolower($record->customer ? $record->customer->email : ($record->email ?? ''));
        $dbPhone = $this->normalizePhone($record->customer ? $record->customer->phone_number : ($record->phone_number ?? ''));

        if ($email !== $dbEmail || $phone !== $dbPhone) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The email or phone does not match our records.'
                ], 422);
            }
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'The email or phone does not match our records.'
            ]);
        }

        // If OTP is provided, verify it
        if ($otpInput) {
            $otp = Otp::where('user_id', $record->id)
                ->where('type', $type)
                ->where('otp_code', $otpInput)
                ->where('used', false)
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if (!$otp) {
                return redirect()->back()->with('alert', [
                    'type' => 'error',
                    'message' => 'Invalid or expired OTP. Please request a new one.'
                ])->withInput();
            }

            // Mark OTP as used
            $otp->update(['used' => true]);
            Session::put("{$type}_verified_{$record->id}", true);

            return redirect()->route('search.show', ['id' => $record->id, 'type' => $type])
                ->with('alert', [
                    'type' => 'success',
                    'message' => 'Verification successful!'
                ]);
        }

        // If no OTP provided, create and send one
        // Check for existing, unused, unexpired OTP
        $otp = Otp::where('user_id', $record->id)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        // If no valid OTP exists, create a new one
        if (!$otp) {
            $otpCode = $this->generateOtp();
            
            try {
                $otp = Otp::create([
                    'user_id' => $record->id,
                    'type' => $type,
                    'otp_code' => $otpCode,
                    'email_sent' => false,
                    'sms_sent' => false,
                    'expires_at' => Carbon::now()->addMinutes(10),
                    'used' => false,
                ]);

                Log::info('OTP created successfully', [
                    'otp_id' => $otp->id,
                    'user_id' => $record->id,
                    'type' => $type,
                    'otp_code' => $otpCode,
                ]);

                $phoneNumber = $record->customer ? $record->customer->phone_number : $record->phone_number;
                $this->sendOtpSms($otp, $phoneNumber);
            } catch (\Exception $e) {
                Log::error('Failed to create OTP', [
                    'user_id' => $record->id,
                    'type' => $type,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to generate OTP. Please try again.'
                    ], 500);
                }
                
                return redirect()->back()->with('alert', [
                    'type' => 'error',
                    'message' => 'Failed to generate OTP. Please try again.'
                ]);
            }
        }

        // For AJAX requests (Send OTP button), return success
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your phone.'
            ]);
        }

        // For regular form submission, redirect to OTP page
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

        $record = Reservation::with('customer')->where('reservation_number', $code)->first();
        $type = 'reservation';

        if (!$record) {
            $record = Booking::with('customer')->where('reservation_number', $code)->first();
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
        
        try {
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

            Log::info('OTP updated/created for resend', [
                'otp_id' => $otp->id,
                'user_id' => $record->id,
                'type' => $type,
                'otp_code' => $otpCode,
            ]);

            $phoneNumber = $record->customer ? $record->customer->phone_number : $record->phone_number;
            $this->sendOtpSms($otp, $phoneNumber);
        } catch (\Exception $e) {
            Log::error('Failed to create/update OTP for resend', [
                'user_id' => $record->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ]);
        }

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

        $record = Reservation::with('customer')->where('reservation_number', $code)->first();
        $type = 'reservation';

        if (!$record) {
            $record = Booking::with('customer')->where('reservation_number', $code)->first();
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
            'redirect_url' => route('search.selectOtpMethod', ['reservation_code' => $code])
        ]);
    }

    public function selectOtpMethod(Request $request)
    {
        $reservationCode = $request->query('reservation_code');
        
        if (!$reservationCode) {
            return redirect()->route('search.index')->with('alert', [
                'type' => 'error',
                'message' => 'Please enter a valid booking code.'
            ]);
        }

        // Verify the booking exists
        $record = Reservation::where('reservation_number', $reservationCode)->first()
            ?? Booking::where('reservation_number', $reservationCode)->first();

        if (!$record) {
            return redirect()->route('search.index')->with('alert', [
                'type' => 'error',
                'message' => 'No reservation or booking found with the provided code.'
            ]);
        }

        return view('user.search.select-otp-method', compact('reservationCode'));
    }

    public function sendOtpByMethod(Request $request)
    {
        $request->validate([
            'reservation_code' => 'required|string',
            'otp_method' => 'required|in:email,sms'
        ]);

        $code = $request->reservation_code;
        $method = $request->otp_method;

        $record = Reservation::with('customer')->where('reservation_number', $code)->first();
        $type = 'reservation';

        if (!$record) {
            $record = Booking::with('customer')->where('reservation_number', $code)->first();
            $type = 'booking';
        }

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Booking or reservation not found.'
            ]);
        }

        // Generate OTP
        $otpCode = $this->generateOtp();
        
        try {
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

            Log::info('OTP created for method selection', [
                'otp_id' => $otp->id,
                'user_id' => $record->id,
                'type' => $type,
                'method' => $method,
                'otp_code' => $otpCode,
            ]);

            if ($method === 'sms') {
                $phoneNumber = $record->customer ? $record->customer->phone_number : $record->phone_number;
                $this->sendOtpSms($otp, $phoneNumber);
                $message = 'OTP sent successfully to your phone number.';
            } else {
                $email = $record->customer ? $record->customer->email : $record->email;
                $this->sendOtpEmail($otp, $email);
                $message = 'OTP sent successfully to your email address.';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('search.verifyOtpForm', ['reservation_code' => $code, 'method' => $method])
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create/send OTP', [
                'user_id' => $record->id,
                'type' => $type,
                'method' => $method,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ]);
        }
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

            if (!$apiUrl || !$token) {
                Log::error('OTP SMS failed: API credentials not configured', [
                    'user_id' => $otp->user_id,
                    'otp_id' => $otp->id,
                ]);
                return;
            }

            if (!$phone) {
                Log::error('OTP SMS failed: No phone number provided', [
                    'user_id' => $otp->user_id,
                    'otp_id' => $otp->id,
                ]);
                return;
            }

            $phone = $this->normalizePhone($phone);
            
            // Convert to international format (Philippines)
            if (Str::startsWith($phone, '0')) {
                $phone = '63' . substr($phone, 1);
            } elseif (!Str::startsWith($phone, '63')) {
                $phone = '63' . $phone;
            }

            $message = "Your Laiya Grande OTP is {$otp->otp_code}. It expires in 10 minutes. Do not share this code.";

            $payload = [
                'api_token'    => $token,
                'phone_number' => $phone,
                'message'      => $message,
                'sender_id'    => 'LaiyaGrande',
            ];

            Log::info('Sending OTP SMS', [
                'user_id' => $otp->user_id,
                'otp_id' => $otp->id,
                'phone' => $phone,
                'otp_code' => $otp->otp_code,
                'api_url' => $apiUrl,
            ]);

            $response = Http::timeout(30)->post($apiUrl, $payload);
            
            Log::info('OTP SMS Response', [
                'otp_id' => $otp->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $otp->sms_sent = true;
                $otp->save();
                
                Log::info('OTP SMS sent successfully and record updated', [
                    'user_id' => $otp->user_id,
                    'otp_id' => $otp->id,
                    'sms_sent' => $otp->sms_sent,
                ]);
            } else {
                Log::error('OTP SMS failed with non-successful response', [
                    'user_id' => $otp->user_id,
                    'otp_id' => $otp->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send OTP SMS', [
                'user_id' => $otp->user_id ?? 'unknown',
                'otp_id' => $otp->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function sendOtpEmail($otp, $email)
    {
        try {
            if (!$email) {
                Log::error('OTP Email failed: No email address provided', [
                    'user_id' => $otp->user_id,
                    'otp_id' => $otp->id,
                ]);
                return;
            }

            Log::info('Sending OTP Email', [
                'user_id' => $otp->user_id,
                'otp_id' => $otp->id,
                'email' => $email,
                'otp_code' => $otp->otp_code,
            ]);

            // Send email using Laravel's Mail facade
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Your Laiya Grande Verification Code');
            });

            $otp->email_sent = true;
            $otp->save();
            
            Log::info('OTP Email sent successfully and record updated', [
                'user_id' => $otp->user_id,
                'otp_id' => $otp->id,
                'email_sent' => $otp->email_sent,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send OTP Email', [
                'user_id' => $otp->user_id ?? 'unknown',
                'otp_id' => $otp->id ?? 'unknown',
                'email' => $email ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
