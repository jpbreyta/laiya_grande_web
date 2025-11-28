<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment; // <--- FIXED NAMESPACE (Was App\Models\Http\Payment)
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = Reservation::with(['room', 'customer']);

        switch ($status) {
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'confirmed':
                $query->where('status', 'confirmed');
                break;
            case 'paid':
                $query->where('status', 'paid');
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
            case 'archived':
                $query->onlyTrashed();
                break;
        }

        // Search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('reservation_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('firstname', 'like', "%{$search}%")
                         ->orWhere('lastname', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('room', function($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Handle "all" entries
        if ($perPage === 'all') {
            $reservations = $query->latest()->get();
            // Create a mock paginator for consistency
            $reservations = new \Illuminate\Pagination\LengthAwarePaginator(
                $reservations,
                $reservations->count(),
                $reservations->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $reservations = $query->latest()->paginate($perPage);
        }

        return view('admin.reservation.index', compact('reservations', 'status'));
    }

    public function show($id)
    {
        $reservation = Reservation::withTrashed()->with(['room', 'payments', 'customer'])->findOrFail($id);
        return view('admin.reservation.show', compact('reservation'));
    }

    public function edit($id)
    {
        $reservation = Reservation::with(['room', 'customer'])->findOrFail($id);
        $rooms = Room::all();
        return view('admin.reservation.edit', compact('reservation', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::with('customer')->findOrFail($id);

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

        // 1. Update Customer Data (Normalized)
        $reservation->customer->update([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
        ]);

        // 2. Update Reservation Data
        $reservation->update([
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'room_id' => $validated['room_id'],
            'number_of_guests' => $validated['number_of_guests'],
            'status' => $validated['status'],
            'total_price' => $validated['total_price'],
            'special_request' => $validated['special_request'],
        ]);

        return redirect()->route('admin.reservation.show', $reservation->id)
            ->with('success', 'Reservation updated successfully.');
    }

    public function approve($id)
    {
        $reservation = Reservation::with('customer')->findOrFail($id);

        if ($reservation->status === 'confirmed' || $reservation->status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Already confirmed/paid.']);
        }

        // Create Booking (Normalized)
        Booking::create([
            'reservation_number' => $reservation->reservation_number,
            'room_id' => $reservation->room_id,
            'customer_id' => $reservation->customer_id,
            'check_in' => $reservation->check_in,
            'check_out' => $reservation->check_out,
            'number_of_guests' => $reservation->number_of_guests,
            'special_request' => $reservation->special_request,
            'payment_method' => $reservation->payment_method,
            'payment' => $reservation->second_payment ?? $reservation->first_payment,
            'total_price' => $reservation->total_price,
            'status' => 'confirmed',
            'source' => 'online'
        ]);

        $reservation->update(['status' => 'paid']);

        return response()->json(['success' => true, 'message' => 'Reservation confirmed and converted to Booking.']);
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        if ($reservation->status === 'cancelled') {
            return response()->json(['success' => false, 'message' => 'Already cancelled.']);
        }
        $reservation->update(['status' => 'cancelled']);

        if ($reservation->room) {
            $reservation->room->increment('availability');
        }

        return response()->json(['success' => true, 'message' => 'Reservation cancelled.']);
    }

    /**
     * Process OCR for payment (first or second).
     *
     * @param int $id Reservation ID
     * @param string $stage 'partial' for first payment, 'final' for second payment
     * @return \Illuminate\Http\JsonResponse
     */
    private function processPaymentOCR($id, $stage)
    {
        $reservation = Reservation::withTrashed()->with('customer')->findOrFail($id);

        $paymentField = $stage === 'partial' ? 'first_payment' : 'second_payment';
        $paymentFile = $reservation->$paymentField;

        if (!$paymentFile || !file_exists(storage_path('app/public/' . $paymentFile))) {
            return response()->json([
                'success' => false,
                'message' => ucfirst($stage) . " payment proof file not found."
            ]);
        }

        try {
            $paymentPath = storage_path('app/public/' . $paymentFile);
            $ocrResult = $this->processOCR($paymentPath);

            $validation = $this->validatePaymentData($ocrResult);
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'OCR validation failed: ' . implode(', ', $validation['errors'])
                ]);
            }

            $customerName = trim($reservation->customer->firstname . ' ' . $reservation->customer->lastname);
            $customerName = empty($customerName) ? 'Unknown Customer' : $customerName;

            $contactNumber = $reservation->customer->phone_number ?? 'N/A';

            $paymentData = [
                'reservation_id' => $reservation->id,
                'booking_id' => null,
                'reference_id' => $ocrResult['reference_id'],
                'customer_name' => $customerName,
                'contact_number' => $contactNumber,
                'payment_date' => $ocrResult['date_time'],
                'amount_paid' => $ocrResult['total_amount'] ?? 0,
                'payment_stage' => $stage,
                'status' => 'verified',
                'payment_method' => 'gcash',
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'notes' => "Processed via OCR button for {$stage} payment"
            ];

            $payment = Payment::updateOrCreate(
                ['reservation_id' => $reservation->id, 'payment_stage' => $stage],
                $paymentData
            );

            return response()->json([
                'success' => true,
                'message' => ucfirst($stage) . ' payment verified successfully!',
                'data' => [
                    'reference_id' => $payment->reference_id,
                    'amount_paid' => $payment->amount_paid
                ]
            ]);
        } catch (\Exception $e) {
            Log::error(ucfirst($stage) . " Payment OCR Error ID {$id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'OCR Error: ' . $e->getMessage()]);
        }
    }

    // Public methods to trigger first and second payment OCR
    public function processFirstPaymentOCR($id)
    {
        return $this->processPaymentOCR($id, 'partial');
    }

    public function processSecondPaymentOCR($id)
    {
        return $this->processPaymentOCR($id, 'final');
    }

    /**
     * Execute OCR via Python script and return parsed JSON.
     */
    private function processOCR($imagePath)
    {
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
            throw new \Exception('OCR processing failed - no output.');
        }

        preg_match('/\{.*\}/s', $result, $matches);

        if (empty($matches)) {
            Log::error("OCR Extraction Failed. Raw Output: " . $result);
            throw new \Exception('OCR script did not return valid JSON. Check logs.');
        }

        $data = json_decode($matches[0], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("OCR JSON Decode Error: " . json_last_error_msg() . " | String: " . $matches[0]);
            throw new \Exception('Failed to decode OCR JSON.');
        }

        return $data;
    }

    /**
     * Validate required OCR fields.
     */
    private function validatePaymentData($data)
    {
        $errors = [];

        if (isset($data['error'])) {
            return ['valid' => false, 'errors' => [$data['error']]];
        }

        $required_fields = ['reference_id', 'date_time', 'total_amount'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[] = "Missing field: {$field}";
            }
        }

        return ['valid' => empty($errors), 'errors' => $errors];
    }

    // --- Import / Export / Soft Delete ---

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $fileContents = file($file->getPathname());

        foreach ($fileContents as $key => $line) {
            if ($key === 0) continue; // Skip header row

            $data = str_getcsv($line);
            // Expected CSV: [Firstname, Lastname, Email, Phone, Room ID, CheckIn, CheckOut, Guests, Total, Status]
            if (count($data) < 5) continue;

            $customer = Customer::firstOrCreate(
                ['email' => $data[2]],
                ['firstname' => $data[0], 'lastname' => $data[1], 'phone_number' => $data[3] ?? '']
            );

            Reservation::create([
                'reservation_number' => Reservation::generateReservationNumber(),
                'customer_id' => $customer->id,
                'room_id' => $data[4],
                'check_in' => \Carbon\Carbon::parse($data[5]),
                'check_out' => \Carbon\Carbon::parse($data[6]),
                'number_of_guests' => $data[7] ?? 1,
                'total_price' => $data[8] ?? 0,
                'status' => strtolower($data[9] ?? 'pending'),
                'source' => 'pos'
            ]);
        }

        return redirect()->back()->with('success', 'Reservations imported successfully.');
    }

    public function exportCsv(Request $request)
    {
        $status = $request->get('status', 'all');
        $filename = "reservations_{$status}_" . date('Y-m-d') . ".csv";

        $reservations = Reservation::with(['customer', 'room']);
        if ($status != 'all') {
            if ($status == 'archived') $reservations->onlyTrashed();
            else $reservations->where('status', $status);
        }
        $reservations = $reservations->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($reservations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Reservation #', 'Customer Name', 'Email', 'Phone', 'Room', 'Check In', 'Check Out', 'Total Price', 'Status', 'Date Booked']);

            foreach ($reservations as $reservation) {
                fputcsv($file, [
                    $reservation->id,
                    $reservation->reservation_number,
                    $reservation->customer->firstname . ' ' . $reservation->customer->lastname,
                    $reservation->customer->email,
                    $reservation->customer->phone_number,
                    $reservation->room->name ?? 'N/A',
                    \Carbon\Carbon::parse($reservation->check_in)->format('Y-m-d'),
                    \Carbon\Carbon::parse($reservation->check_out)->format('Y-m-d'),
                    $reservation->total_price,
                    ucfirst($reservation->status),
                    $reservation->created_at->format('Y-m-d H:i')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $status = $request->get('status', 'all');
        $reservations = Reservation::with(['customer', 'room']);
        if ($status != 'all' && $status != 'archived') $reservations->where('status', $status);
        if ($status == 'archived') $reservations->onlyTrashed();
        $reservations = $reservations->get();

        return view('admin.reservation.print', compact('reservations', 'status'));
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete(); // Soft delete
        return redirect()->route('admin.reservation.index')
            ->with('success', 'Reservation has been archived successfully.');
    }

    public function restore($id)
    {
        $reservation = Reservation::withTrashed()->findOrFail($id);
        $reservation->restore();
        return redirect()->route('admin.reservation.index', ['status' => 'archived'])
            ->with('success', 'Reservation restored successfully.');
    }

    public function forceDelete($id)
    {
        $reservation = Reservation::withTrashed()->findOrFail($id);
        $reservation->forceDelete();
        return redirect()->route('admin.reservation.index', ['status' => 'archived'])
            ->with('success', 'Reservation permanently deleted.');
    }
}
