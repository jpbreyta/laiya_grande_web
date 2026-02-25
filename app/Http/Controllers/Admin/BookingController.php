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

        $query = Booking::with([
            'room:id,name', 
            'customer:id,firstname,lastname,email'
        ]);

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
        }

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

        if ($perPage === 'all') {
            $bookings = $query->latest()->limit(100)->get();
            $bookings = new \Illuminate\Pagination\LengthAwarePaginator(
                $bookings,
                $bookings->count(),
                $bookings->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $limit = is_numeric($perPage) ? (int)$perPage : 10;
            $bookings = $query->latest()->paginate($limit);
        }

        return view('admin.booking.index', compact('bookings', 'status'));
    }

    public function show($id)
    {
        $booking = Booking::withTrashed()->with(['room', 'paymentRecord', 'customer'])->findOrFail($id);
        
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

        try {
            $paymentPath = storage_path('app/public/' . $booking->payment);
            if (!file_exists($paymentPath)) {
                return response()->json(['success' => false, 'message' => 'Payment proof file not found.']);
            }

            $ocrResult = $this->processOCR($paymentPath);
            $validation = $this->validatePaymentData($ocrResult);

            if (!$validation['valid']) {
                return response()->json(['success' => false, 'message' => 'OCR validation failed: ' . implode(', ', $validation['errors'])]);
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
                'verified_by' => Auth::id(),
                'notes' => 'Processed via OCR button'
            ];

            $payment = $booking->paymentRecord()->updateOrCreate(['booking_id' => $booking->id], $paymentData);

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
            return response()->json(['success' => false, 'message' => 'OCR processing failed: ' . $e->getMessage()]);
        }
    }

    private function processOCR($imagePath)
    {
        $command = ['python', base_path('process_payment_ocr.py'), $imagePath];
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
            throw new \Exception('Invalid OCR output');
        }

        return $data;
    }

    private function validatePaymentData($data)
    {
        $errors = [];
        if (isset($data['error'])) return ['valid' => false, 'errors' => [$data['error']]];

        $required_fields = ['reference_id', 'date_time'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) $errors[] = "Missing field: {$field}";
        }

        if (!empty($data['reference_id']) && (!ctype_digit($data['reference_id']) || strlen($data['reference_id']) < 10)) {
            $errors[] = "Invalid Reference ID";
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
        ]);

        $booking->customer->update([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
        ]);

        $booking->update($validated);

        return redirect()->route('admin.booking.show', $booking->id)->with('success', 'Booking updated.');
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return redirect()->route('admin.booking.index')->with('success', 'Archived.');
    }

    public function restore($id)
    {
        Booking::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.booking.index', ['status' => 'archived'])->with('success', 'Restored.');
    }

    public function forceDelete($id)
    {
        Booking::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.booking.index', ['status' => 'archived'])->with('success', 'Deleted.');
    }

    public function approve($id)
    {
        $booking = Booking::with('customer')->findOrFail($id);
        if ($booking->status === 'confirmed') return response()->json(['success' => false, 'message' => 'Already confirmed.']);

        $booking->update(['status' => 'confirmed']);
        $this->sendConfirmationNotifications($booking);
        return response()->json(['success' => true, 'message' => 'Confirmed.']);
    }

    public function reject($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'rejected']);
        
        $room = Room::find($booking->room_id);
        if ($room) {
            $room->increment('availability');
        }

        return response()->json(['success' => true, 'message' => 'Rejected.']);
    }

    private function sendConfirmationNotifications($booking)
    {
        try {
            $bookingDetails = [
                'guest_name' => $booking->full_name,
                'guest_email' => $booking->email,
                'guest_phone' => $booking->phone_number,
                'guests' => $booking->number_of_guests,
            ];
            Mail::to($booking->email)->send(new BookingConfirmationMail($booking, $bookingDetails));
        } catch (\Exception $e) {}

        try {
            $apiUrl = env('IPROG_SMS_API_URL');
            $token  = env('IPROG_SMS_API_TOKEN');
            $phone  = $booking->phone_number;
            if (Str::startsWith($phone, '0')) $phone = '63' . substr($phone, 1);

            Http::post($apiUrl, [
                'api_token' => $token,
                'phone_number' => $phone,
                'message' => "Hi {$booking->firstname}, booking confirmed!",
                'sender_id' => 'LaiyaGrande',
            ]);
        } catch (\Exception $e) {}
    }
}