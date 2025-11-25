<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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
     * Generate QR code using Endroid only (PNG guaranteed)
     */
    private function generateQrCode()
    {
        // Sanitize guest name
        $guestName = isset($this->bookingDetails['guest_name'])
            ? preg_replace('/[^\x20-\x7E]/', '', $this->bookingDetails['guest_name'])
            : 'Guest';

        // Build QR text content
        $qrString =
            "LAIYA GRANDE BOOKING\n" .
            "Booking ID: {$this->booking->id}\n" .
            "Reservation Code: " . ($this->booking->reservation_number ?? 'N/A') . "\n" .
            "Guest: {$guestName}\n" .
            "Check-in: {$this->booking->check_in}\n" .
            "Check-out: {$this->booking->check_out}\n" .
            "Room ID: {$this->booking->room_id}\n" .
            "Total: PHP " . number_format($this->booking->total_price, 2, '.', '');

        // File path
        $qrCodeFilename = 'qr_codes/booking_' . $this->booking->id . '.png';
        $qrCodePath = storage_path('app/' . $qrCodeFilename);

        // Ensure directory exists
        if (!file_exists(dirname($qrCodePath))) {
            mkdir(dirname($qrCodePath), 0755, true);
        }

        // Create QR code object
        $qrCode = QrCode::create($qrString)
            ->setSize(300)
            ->setMargin(10);

        // Write PNG
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Save to file
        file_put_contents($qrCodePath, $result->getString());

        $this->qrCodePath = $qrCodePath;
    }

    /**
     * Email subject
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Confirmation - Laiya Grande Resort',
        );
    }

    /**
     * Email view
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
        );
    }

    /**
     * Attach the PNG QR code
     */
    public function attachments(): array
    {
        if (!file_exists($this->qrCodePath)) {
            return [];
        }

        return [
            Attachment::fromPath($this->qrCodePath)
                ->as('booking_qr_code.png')
                ->withMime('image/png'),
        ];
    }
}
