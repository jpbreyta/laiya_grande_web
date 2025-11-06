<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

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
     * Process QR code scan and return booking data
     */
    public function scan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $qrCode = $request->qr_code;

        // Find booking by reservation number
        $booking = Booking::with('room')
            ->where('reservation_number', $qrCode)
            ->orWhere('id', $qrCode)
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found for this QR code.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'qr_url' => route('admin.qr.generate-pdf', $booking->id)
        ]);
    }

    /**
     * Generate PDF voucher for booking
     */
    public function generatePdf($id)
    {
        $booking = Booking::with('room')->findOrFail($id);

        // Generate QR code as base64
        $qrCodeData = $booking->reservation_number ?? $booking->id;
        $qrCodeBase64 = base64_encode(QrCode::size(150)->margin(1)->generate($qrCodeData));

        // Calculate remaining balance
        $depositedAmount = $booking->payment_amount ?? 0; // Assuming you have this field
        $remainingBalance = $booking->total_price - $depositedAmount;

        $data = [
            'booking' => $booking,
            'qr_code_base64' => $qrCodeBase64,
            'deposited_amount' => $depositedAmount,
            'remaining_balance' => $remainingBalance,
            'prepared_by' => 'System Generated', // You can customize this
            'booking_id_display' => '24-LGBR-C-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT)
        ];

        $pdf = Pdf::loadView('admin.qr.voucher', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif']);

        // Log PDF generation
        Log::info('Booking confirmation PDF generated', [
            'booking_id' => $booking->id,
            'reservation_number' => $booking->reservation_number
        ]);

        return $pdf->download('booking-voucher-' . ($booking->reservation_number ?? $booking->id) . '.pdf');
    }

    /**
     * Stream PDF for preview
     */
    public function previewPdf($id)
    {
        $booking = Booking::with('room')->findOrFail($id);

        $qrCodeData = $booking->reservation_number ?? $booking->id;
        $qrCodeBase64 = base64_encode(QrCode::size(150)->margin(1)->generate($qrCodeData));

        $depositedAmount = $booking->payment_amount ?? 0;
        $remainingBalance = $booking->total_price - $depositedAmount;

        $data = [
            'booking' => $booking,
            'qr_code_base64' => $qrCodeBase64,
            'deposited_amount' => $depositedAmount,
            'remaining_balance' => $remainingBalance,
            'prepared_by' => 'System Generated',
            'booking_id_display' => '24-LGBR-C-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT)
        ];

        $pdf = Pdf::loadView('admin.qr.voucher', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->stream('booking-voucher-preview.pdf');
    }


}
