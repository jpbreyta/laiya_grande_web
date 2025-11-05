<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Booking;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $bookingDetails;
    public $qrCodePath;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, $bookingDetails = [])
    {
        $this->booking = $booking;
        $this->bookingDetails = $bookingDetails;
        $this->generateQrCode();
    }

    /**
     * Generate QR code for the booking
     */
    private function generateQrCode()
    {
        $qrData = [
            'booking_id' => $this->booking->id,
            'reservation_number' => $this->booking->reservation_number,
            'check_in' => $this->booking->check_in,
            'check_out' => $this->booking->check_out,
            'room_id' => $this->booking->room_id,
            'total_price' => $this->booking->total_price,
            'guest_name' => $this->bookingDetails['guest_name'] ?? 'Guest',
            'guest_email' => $this->bookingDetails['guest_email'] ?? '',
        ];

        // Sanitize guest name to ASCII characters only
        $guestName = isset($this->bookingDetails['guest_name'])
            ? preg_replace('/[^\x20-\x7E]/', '', $this->bookingDetails['guest_name'])
            : 'Guest';

        $qrString = "LAIYA GRANDE BOOKING\n" .
                   "Booking ID: " . $this->booking->id . "\n" .
                   "Reservation Code: " . ($this->booking->reservation_number ?? 'N/A') . "\n" .
                   "Guest: " . $guestName . "\n" .
                   "Check-in: " . $this->booking->check_in . "\n" .
                   "Check-out: " . $this->booking->check_out . "\n" .
                   "Room ID: " . $this->booking->room_id . "\n" .
                   "Total: PHP " . number_format($this->booking->total_price, 2, '.', '');

        // Generate QR code and save to storage
        $qrCodeFilename = 'qr_codes/booking_' . $this->booking->id . '.png';
        $qrCodePath = storage_path('app/' . $qrCodeFilename);

        // Create directory if it doesn't exist
        if (!file_exists(dirname($qrCodePath))) {
            mkdir(dirname($qrCodePath), 0755, true);
        }

        try {
            // Try to generate QR code with specific settings for GD
            $qrCodeData = QrCode::format('png')
                  ->size(300)
                  ->margin(2)
                  ->errorCorrection('M')
                  ->generate($qrString);

            // Save the QR code data to file
            file_put_contents($qrCodePath, $qrCodeData);
        } catch (\Exception $e) {
            // Fallback: Generate SVG instead if PNG fails
            $qrCodePath = str_replace('.png', '.svg', $qrCodePath);
            $qrCodeData = QrCode::format('svg')
                  ->size(300)
                  ->margin(2)
                  ->generate($qrString);
            file_put_contents($qrCodePath, $qrCodeData);
        }

        $this->qrCodePath = $qrCodePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmation - Laiya Grande Resort',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if (!file_exists($this->qrCodePath)) {
            return [];
        }

        $extension = pathinfo($this->qrCodePath, PATHINFO_EXTENSION);
        $mimeType = $extension === 'svg' ? 'image/svg+xml' : 'image/png';
        $filename = 'booking_qr_code.' . $extension;

        return [
            Attachment::fromPath($this->qrCodePath)
                      ->as($filename)
                      ->withMime($mimeType),
        ];
    }
}
