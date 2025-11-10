<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Booking;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with('booking.room')->latest()->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with('booking.room')->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Process payment proof using OCR
     */
    public function processPaymentProof(Request $request, $bookingId)
    {
        // Validate file
        try {
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        $booking = Booking::findOrFail($bookingId);

        $tempPath = null;
        try {
            // Store uploaded image temporarily
            $tempPath = $request->file('payment_proof')->store('temp');

            // Debug: Check if file was stored
            $fullPath = storage_path('app/' . $tempPath);
            if (!file_exists($fullPath)) {
                throw new \Exception('File was not stored properly: ' . $fullPath);
            }

            // Process with OCR
            $ocrResult = $this->processOCR($fullPath);

            // Validate OCR result
            $validation = $this->validatePaymentData($ocrResult);

            if (!$validation['valid']) {
                Storage::delete($tempPath);
                return response()->json([
                    'success' => false,
                    'errors' => $validation['errors']
                ], 422);
            }

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'reference_id' => $ocrResult['reference_id'],
                'customer_name' => $booking->customer_name,
                'contact_number' => $booking->customer_contact,
                'payment_date' => $ocrResult['date_time'],
                'amount_paid' => $ocrResult['total_amount'] ?? null,
                'status' => 'verified',
                'payment_method' => 'gcash',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'notes' => 'Verified via OCR processing'
            ]);

            Storage::delete($tempPath);

            return response()->json([
                'success' => true,
                'payment' => $payment,
                'message' => 'Payment proof processed and verified successfully!'
            ]);

        } catch (\Exception $e) {
            if ($tempPath) Storage::delete($tempPath);

            return response()->json([
                'success' => false,
                'errors' => ['OCR processing failed: ' . $e->getMessage()]
            ], 500);
        }
    }

    /**
     * Show test OCR page
     */
    public function testOcrPage()
    {
        return view('admin.payments.test-ocr');
    }

    /**
     * Process test payment proof (without saving to DB)
     */
    public function testProcessPaymentProof(Request $request)
    {
        // Validate file
        try {
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        $tempPath = null;
        try {
            // Store uploaded image temporarily
            $tempPath = $request->file('payment_proof')->store('temp');

            // Debug: Check if file was stored
            $fullPath = storage_path('app/' . $tempPath);
            if (!file_exists($fullPath)) {
                throw new \Exception('File was not stored properly: ' . $fullPath);
            }

            // Process with OCR
            $ocrResult = $this->processOCR($fullPath);

            // Validate OCR result
            $validation = $this->validatePaymentData($ocrResult);

            Storage::delete($tempPath);

            return response()->json([
                'success' => true,
                'ocr_result' => $ocrResult,
                'validation' => $validation,
                'message' => 'OCR processing completed successfully!'
            ]);

        } catch (\Exception $e) {
            if ($tempPath) Storage::delete($tempPath);

            return response()->json([
                'success' => false,
                'errors' => ['OCR processing failed: ' . $e->getMessage()]
            ], 500);
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

        // Validate total_amount if present
        if (isset($data['total_amount']) && $data['total_amount'] < 0) {
            $errors[] = "Total amount cannot be negative";
        }

        return ['valid'=>empty($errors),'errors'=>$errors];
    }
}
