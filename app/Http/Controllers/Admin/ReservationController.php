<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display all reservations
     */
    public function index()
    {
        $reservations = Reservation::with('room')->latest()->paginate(10);
        return view('admin.reservation.index', compact('reservations'));
    }

    /**
     * Show single reservation details
     */
    public function show($id)
    {
        $reservation = Reservation::with('room', 'payments')->findOrFail($id);
        return view('admin.reservation.show', compact('reservation'));
    }

    /**
     * Show the form for editing a reservation
     */
    public function edit($id)
    {
        $reservation = Reservation::with('room')->findOrFail($id);
        $rooms = Room::all();
        return view('admin.reservation.edit', compact('reservation', 'rooms'));
    }

    /**
     * Update a reservation
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'room_id' => 'required|exists:rooms,id',
            'number_of_guests' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,paid,cancelled',
            'total_price' => 'required|numeric|min:0',
            'special_request' => 'nullable|string',
        ]);

        $reservation->update($validated);

        return redirect()->route('admin.reservation.show', $reservation->id)
            ->with('success', 'Reservation updated successfully.');
    }

    /**
     * Approve reservation
     */
    public function approve($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status === 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'This reservation is already confirmed.'
            ]);
        }

        // Convert reservation to booking
        $booking = \App\Models\Booking::create([
            'room_id' => $reservation->room_id,
            'firstname' => $reservation->firstname,
            'lastname' => $reservation->lastname,
            'email' => $reservation->email,
            'phone_number' => $reservation->phone_number,
            'check_in' => $reservation->check_in,
            'check_out' => $reservation->check_out,
            'number_of_guests' => $reservation->number_of_guests,
            'special_request' => $reservation->special_request,
            'payment_method' => $reservation->payment_method,
            'payment' => $reservation->second_payment, // Use the final payment proof
            'total_price' => $reservation->total_price,
            'status' => 'pending', // Start as pending in booking
            'reservation_number' => $reservation->reservation_number,
        ]);

        // Update reservation status to paid
        $reservation->update(['status' => 'paid']);

        return response()->json([
            'success' => true,
            'message' => 'Reservation confirmed and moved to booking page successfully.'
        ]);
    }

    /**
     * Cancel reservation
     */
    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'This reservation is already cancelled.'
            ]);
        }

        $reservation->update(['status' => 'cancelled']);

        // Restore room availability
        if ($reservation->room) {
            $reservation->room->availability += 1;
            $reservation->room->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Reservation has been cancelled successfully.'
        ]);
    }

    /**
     * Process OCR for first payment of a reservation
     */
    public function processFirstPaymentOCR($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!$reservation->first_payment) {
            return response()->json([
                'success' => false,
                'message' => 'No first payment proof found for this reservation.'
            ]);
        }

        try {
            $paymentPath = storage_path('app/public/' . $reservation->first_payment);
            if (!file_exists($paymentPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'First payment proof file not found.'
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
                'reservation_id' => $reservation->id,
                'reference_id' => $ocrResult['reference_id'],
                'customer_name' => $reservation->firstname . ' ' . $reservation->lastname,
                'contact_number' => $reservation->phone_number,
                'payment_date' => $ocrResult['date_time'],
                'amount_paid' => $ocrResult['total_amount'] ?? null,
                'payment_stage' => 'partial',
                'status' => 'verified',
                'payment_method' => 'gcash',
                'verified_at' => now(),
                'verified_by' => Auth::check() ? Auth::id() : null,
                'notes' => 'Processed via OCR button for first payment'
            ];

            $payment = $reservation->payments()->updateOrCreate(
                ['reservation_id' => $reservation->id, 'payment_stage' => 'partial'],
                $paymentData
            );

            return response()->json([
                'success' => true,
                'message' => 'First payment OCR processed successfully!',
                'data' => [
                    'reference_id' => $payment->reference_id,
                    'payment_date' => $payment->payment_date,
                    'payment_method' => $payment->payment_method,
                    'amount_paid' => $payment->amount_paid
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('OCR processing failed for reservation first payment ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'OCR processing failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Process OCR for second payment of a reservation
     */
    public function processSecondPaymentOCR($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!$reservation->second_payment) {
            return response()->json([
                'success' => false,
                'message' => 'No second payment proof found for this reservation.'
            ]);
        }

        try {
            $paymentPath = storage_path('app/public/' . $reservation->second_payment);
            if (!file_exists($paymentPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Second payment proof file not found.'
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
                'reservation_id' => $reservation->id,
                'reference_id' => $ocrResult['reference_id'],
                'customer_name' => $reservation->firstname . ' ' . $reservation->lastname,
                'contact_number' => $reservation->phone_number,
                'payment_date' => $ocrResult['date_time'],
                'amount_paid' => $ocrResult['total_amount'] ?? null,
                'payment_stage' => 'final',
                'status' => 'verified',
                'payment_method' => 'gcash',
                'verified_at' => now(),
                'verified_by' => Auth::check() ? Auth::id() : null,
                'notes' => 'Processed via OCR button for second payment'
            ];

            $payment = $reservation->payments()->updateOrCreate(
                ['reservation_id' => $reservation->id, 'payment_stage' => 'final'],
                $paymentData
            );

            return response()->json([
                'success' => true,
                'message' => 'Second payment OCR processed successfully!',
                'data' => [
                    'reference_id' => $payment->reference_id,
                    'payment_date' => $payment->payment_date,
                    'payment_method' => $payment->payment_method,
                    'amount_paid' => $payment->amount_paid
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('OCR processing failed for reservation second payment ' . $id . ': ' . $e->getMessage());
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
}
