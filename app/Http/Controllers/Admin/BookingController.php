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
use App\Models\Customer;
use App\Models\Payment;
use App\Mail\BookingConfirmationMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = Booking::with(['room', 'customer']);

        // Filter Logic
        switch ($status) {
            case 'pending':
                $query->where('status', 'pending');
                break;
            case 'confirmed':
                $query->where('status', 'confirmed');
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
            case 'rejected':
                $query->where('status', 'rejected');
                break;
            case 'archived':
                $query->onlyTrashed();
                break;
            default:
                // 'all' shows everything except trashed
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
            $bookings = $query->latest()->get();
            // Create a mock paginator for consistency
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator(
                $bookings,
                $bookings->count(),
                $bookings->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $bookings = $query->latest()->paginate($perPage);
        }

        return view('admin.booking.index', compact('bookings', 'status'));
    }

    public function show($id)
    {
        $booking = Booking::withTrashed()->with(['room', 'paymentRecord', 'customer'])->findOrFail($id);
        
        // Generate QR string with same format as email
        $guestName = $booking->customer 
            ? $booking->customer->firstname . ' ' . $booking->customer->lastname
            : 'Guest';
        
        $qrString =
            "LAIYA GRANDE BOOKING\n" .
            "Booking ID: {$booking->id}\n" .
            "Reservation Code: " . ($booking->reservation_number ?? 'N/A') . "\n" .
            "Guest: {$guestName}\n" .
            "Check-in: {$booking->check_in}\n" .
            "Check-out: {$booking->check_out}\n" .
            "Room ID: {$booking->room_id}\n" .
            "Total: PHP " . number_format($booking->total_price, 2, '.', '');
        
        return view('admin.booking.show', compact('booking', 'qrString'));
    }

    public function processOCRForBooking($id)
    {
        $booking = Booking::with('customer')->findOrFail($id);

        if (!$booking->paymentRecord) {
            // Attempt to find payment record manually or check if image exists in booking table
            // In your schema, booking has 'payment' string (file path) and relation to Payment model
            
            // If strictly using Payment model:
            // return response()->json(['success' => false, 'message' => 'No payment record found.']);
            
            // However, your code implies creating a Payment record FROM the booking's image
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

            $paymentData = [
                'reference_id' => $ocrResult['reference_id'],
                'customer_name' => $booking->customer->firstname . ' ' . $booking->customer->lastname,
                'contact_number' => $booking->customer->phone_number,
                'payment_date' => $ocrResult['date_time'],
                'amount_paid' => $ocrResult['total_amount'] ?? null,
                'status' => 'verified',
                'payment_method' => 'gcash',
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
            throw new \Exception('OCR processing failed - no output from command');
        }

        $lines = explode("\n", trim($result));
        $jsonLines = array_filter($lines, function ($line) {
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

    private function validatePaymentData($data)
    {
        $errors = [];

        if (isset($data['error'])) {
            return ['valid' => false, 'errors' => [$data['error']]];
        }

        $required_fields = ['reference_id', 'date_time'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[] = "Missing or empty field: {$field}";
            }
        }

        if (!empty($data['reference_id']) && (!ctype_digit($data['reference_id']) || strlen($data['reference_id']) < 10 || strlen($data['reference_id']) > 13)) {
            $errors[] = "Reference ID should be 10-13 digits";
        }

        if (!empty($data['date_time']) && !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $data['date_time'])) {
            $errors[] = "Date/time should be in YYYY-MM-DD HH:MM:SS format";
        }

        return ['valid' => empty($errors), 'errors' => $errors];
    }

    public function edit($id)
    {
        $booking = Booking::with(['room', 'customer'])->findOrFail($id);
        $rooms = Room::all();
        return view('admin.booking.edit', compact('booking', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::with('customer')->findOrFail($id);

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'room_id' => 'required|exists:rooms,id',
            'number_of_guests' => 'required|integer|min:1',
            'status' => 'required|in:pending,confirmed,cancelled,rejected',
            'total_price' => 'required|numeric|min:0',
            'special_request' => 'nullable|string',
        ]);

        // Update Customer
        $booking->customer->update([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
        ]);

        // Update Booking
        $booking->update([
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'room_id' => $validated['room_id'],
            'number_of_guests' => $validated['number_of_guests'],
            'status' => $validated['status'],
            'total_price' => $validated['total_price'],
            'special_request' => $validated['special_request'],
        ]);

        return redirect()->route('admin.booking.show', $booking->id)
            ->with('success', 'Booking updated successfully.');
    }

    // "Archive" functionality (Soft Delete)
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete(); // Soft delete

        return redirect()->route('admin.booking.index')
            ->with('success', 'Booking has been archived successfully.');
    }

    // Restore archived booking
    public function restore($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        $booking->restore();

        return redirect()->route('admin.booking.index', ['status' => 'archived'])
            ->with('success', 'Booking restored successfully.');
    }

    // Force delete (Permanent)
    public function forceDelete($id)
    {
        $booking = Booking::withTrashed()->findOrFail($id);
        $booking->forceDelete();

        return redirect()->route('admin.booking.index', ['status' => 'archived'])
            ->with('success', 'Booking permanently deleted.');
    }

    public function approve($id)
    {
        $booking = Booking::with('customer')->findOrFail($id);

        if ($booking->status === 'confirmed') {
            return response()->json(['success' => false, 'message' => 'Already confirmed.']);
        }

        $booking->update(['status' => 'confirmed']);
        $this->sendConfirmationNotifications($booking);

        return response()->json(['success' => true, 'message' => 'Booking confirmed.']);
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        
        // You requested "Rejected" status specifically
        $booking->update(['status' => 'rejected']);
        
        // Logic to free up room if needed
        $room = Room::find($booking->room_id);
        if ($room) {
            $room->availability += 1;
            $room->save();
        }

        return response()->json(['success' => true, 'message' => 'Booking marked as rejected.']);
    }

    // --- Export Functions ---

    public function exportCsv(Request $request)
    {
        $status = $request->get('status', 'all');
        $filename = "bookings_{$status}_" . date('Y-m-d') . ".csv";

        $bookings = Booking::with(['customer', 'room']);
        if($status != 'all') {
            if($status == 'archived') $bookings->onlyTrashed();
            else $bookings->where('status', $status);
        }
        $bookings = $bookings->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Reservation #', 'Customer Name', 'Email', 'Phone', 'Room', 'Check In', 'Check Out', 'Total Price', 'Status', 'Date Booked']);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->reservation_number,
                    $booking->full_name, // Uses accessor
                    $booking->email,
                    $booking->phone_number,
                    $booking->room->name ?? 'N/A',
                    $booking->check_in->format('Y-m-d'),
                    $booking->check_out->format('Y-m-d'),
                    $booking->total_price,
                    ucfirst($booking->status),
                    $booking->created_at->format('Y-m-d H:i')
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        // For a full PDF, usually 'dompdf' or 'barryvdh/laravel-dompdf' is used.
        // Assuming you might not have it, we can trigger a print view or just return the view to print.
        // If you want strict PDF download, you need the package. 
        // Here I will return a clean print-friendly view.
        $status = $request->get('status', 'all');
        $bookings = Booking::with(['customer', 'room']);
        if($status != 'all' && $status != 'archived') $bookings->where('status', $status);
        if($status == 'archived') $bookings->onlyTrashed();
        $bookings = $bookings->get();

        return view('admin.booking.print', compact('bookings', 'status'));
    }

    // --- Import Function ---
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
            // Expected CSV format: [Firstname, Lastname, Email, Phone, Room ID, CheckIn, CheckOut, Guests, Total, Status]
            // This is basic; in production, validate indices exist
            if(count($data) < 5) continue; 

            // Create/Find Customer
            $customer = Customer::firstOrCreate(
                ['email' => $data[2]], 
                ['firstname' => $data[0], 'lastname' => $data[1], 'phone_number' => $data[3]]
            );

            Booking::create([
                'reservation_number' => Booking::generateReservationNumber(),
                'customer_id' => $customer->id,
                'room_id' => $data[4], // Assumes ID is known
                'check_in' => Carbon::parse($data[5]),
                'check_out' => Carbon::parse($data[6]),
                'number_of_guests' => $data[7] ?? 1,
                'total_price' => $data[8] ?? 0,
                'status' => strtolower($data[9] ?? 'pending'),
                'source' => 'pos' // Imported data usually manual
            ]);
        }

        return redirect()->back()->with('success', 'Bookings imported successfully.');
    }

    // --- Helpers ---

    private function sendConfirmationNotifications($booking)
    {
        // Email
        try {
            $bookingDetails = [
                'guest_name' => $booking->full_name,
                'guest_email' => $booking->email,
                'guest_phone' => $booking->phone_number,
                'guests' => $booking->number_of_guests,
            ];
            Mail::to($booking->email)->send(new BookingConfirmationMail($booking, $bookingDetails));
        } catch (\Exception $e) {
            Log::error('Email failed: ' . $e->getMessage());
        }

        // SMS
        try {
            $apiUrl = env('IPROG_SMS_API_URL', 'https://sms.iprogtech.com/api/v1/sms_messages');
            $token  = env('IPROG_SMS_API_TOKEN');
            $phone  = $booking->phone_number;
            if (Str::startsWith($phone, '0')) $phone = '63' . substr($phone, 1);

            Http::post($apiUrl, [
                'api_token'    => $token,
                'phone_number' => $phone,
                'message'      => "Hi {$booking->firstname}, your booking is confirmed!",
                'sender_id'    => 'LaiyaGrande',
            ]);
        } catch (\Exception $e) {
            Log::error('SMS failed: ' . $e->getMessage());
        }
    }
}