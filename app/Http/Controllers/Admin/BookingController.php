<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
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
        $booking = Booking::with('room', 'paymentRecord')->findOrFail($id);

        return view('admin.booking.show', compact('booking'));
    }

    /**
     * Process OCR for a specific booking
     */
    public function processOCRForBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->paymentRecord) {
            return response()->json([
                'success' => false,
                'message' => 'No payment proof found for this booking.'
            ]);
        }

        try {
            $paymentPath = storage_path('app/public/' . $booking->payment); 
            if (!file_exists($paymentPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment proof file not found.'
                ]);
            }

            $ocrResult = $this->processOCR($paymentPath);
            $validation = $this->validatePaymentData($ocrResult);

            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'OCR validation failed: ' . implode(', ', $validation['errors'])
                ]);
            }

            // Create or update payment record with OCR data
            $paymentData = [
                'reference_id' => $ocrResult['reference_id'],
                'customer_name' => $booking->firstname . ' ' . $booking->lastname,
                'contact_number' => $booking->phone_number,
                'payment_date' => $ocrResult['date_time'],
                'amount_paid' => $ocrResult['total_amount'] ?? null,
                'status' => 'verified',
                'payment_method' => 'gcash', // Default to GCash
                'verified_at' => now(),
                'verified_by' => Auth::check() ? Auth::id() : null,
                'notes' => 'Processed via OCR button'
            ];

            $payment = $booking->paymentRecord()->updateOrCreate(
                ['booking_id' => $booking->id],
                $paymentData
            );

            return response()->json([
                'success' => true,
                'message' => 'OCR processed successfully!',
                'data' => [
                    'reference_id' => $payment->reference_id,
                    'payment_date' => $payment->payment_date,
                    'payment_method' => $payment->payment_method,
                    'amount_paid' => $payment->amount_paid
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('OCR processing failed for booking ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'OCR processing failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Process OCR on image
     */
    private function processOCR($imagePath)
    {
        // Ensure the file exists
        if (!file_exists($imagePath)) {
            throw new \Exception('Image file does not exist: ' . $imagePath);
        }

        $command = [
            'python',
            base_path('process_payment_ocr.py'),
            $imagePath
        ];

        $result = shell_exec(implode(' ', array_map('escapeshellarg', $command)));

        if (!$result) {
            throw new \Exception('OCR processing failed - no output from command');
        }

        // Remove any non-JSON output like "Active code page: 65001" and "Tesseract version:"
        $lines = explode("\n", trim($result));
        $jsonLines = array_filter($lines, function($line) {
            $trimmed = trim($line);
            return !preg_match('/^(Active code page|Tesseract version):/', $trimmed) && !empty($trimmed);
        });
        $cleanResult = implode("\n", $jsonLines);

        $data = json_decode($cleanResult, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid OCR output: ' . $cleanResult . ' | Full result: ' . $result);
        }

        return $data;
    }

    /**
     * Validate payment data
     */
    private function validatePaymentData($data)
    {
        $errors = [];

        if (isset($data['error'])) {
            return ['valid'=>false,'errors'=>[$data['error']]];
        }

        $required_fields = ['reference_id','date_time'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[] = "Missing or empty field: {$field}";
            }
        }

        if (!empty($data['reference_id']) && (!ctype_digit($data['reference_id']) || strlen($data['reference_id']) < 10 || strlen($data['reference_id']) > 13)) {
            $errors[] = "Reference ID should be 10-13 digits";
        }

        if (!empty($data['date_time']) && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',$data['date_time'])) {
            $errors[] = "Date/time should be in YYYY-MM-DD HH:MM:SS format";
        }

        return ['valid'=>empty($errors),'errors'=>$errors];
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

    public function comparePayments()
    {
        $bookings = Booking::with('paymentRecord')->get();
        $discrepancies = [];

        foreach ($bookings as $booking) {
            if ($booking->paymentRecord) {
                if (floatval($booking->total_price) !== floatval($booking->paymentRecord->amount_paid)) {
                    $discrepancies[] = [
                        'booking_id' => $booking->id,
                        'expected_amount' => $booking->total_price,
                        'paid_amount' => $booking->paymentRecord->amount_paid,
                    ];
                }
            }
        }

        return view('admin.booking.discrepancies', compact('discrepancies'));
    }
}
