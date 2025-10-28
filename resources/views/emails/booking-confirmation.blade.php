<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Laiya Grande Resort</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }

        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 16px;
        }

        .booking-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .booking-details h2 {
            color: #007bff;
            margin-top: 0;
            font-size: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 18px;
            color: #28a745;
        }

        .detail-label {
            font-weight: bold;
            color: #495057;
        }

        .detail-value {
            color: #333;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #e8f5e8;
            border-radius: 8px;
        }

        .qr-section h3 {
            color: #28a745;
            margin-bottom: 15px;
        }

        .qr-section p {
            color: #666;
            font-size: 14px;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #666;
            font-size: 14px;
        }

        .contact-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .contact-info h4 {
            color: #007bff;
            margin-top: 0;
        }

        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .important-note h4 {
            color: #856404;
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🏖️ Laiya Grande Resort</h1>
            <p>Your Booking is Confirmed!</p>
        </div>

        <p>Dear {{ $bookingDetails['guest_name'] ?? 'Valued Guest' }},</p>

        <p>Thank you for choosing Laiya Grande Resort! We're excited to welcome you to our beautiful beachfront
            property. Your booking has been confirmed and we can't wait to provide you with an unforgettable experience.
        </p>

        <div class="booking-details">
            <h2>📋 Booking Details</h2>
            <div class="detail-row">
                <span class="detail-label">Booking Reference:</span>
                <span class="detail-value">#{{ $booking->id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Guest Name:</span>
                <span class="detail-value">{{ $bookingDetails['guest_name'] ?? 'Guest' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $bookingDetails['guest_email'] ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value">{{ $bookingDetails['guest_phone'] ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Room:</span>
                <span class="detail-value">Room #{{ $booking->room_id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-in Date:</span>
                <span class="detail-value">{{ date('F j, Y', strtotime($booking->check_in)) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Check-out Date:</span>
                <span class="detail-value">{{ date('F j, Y', strtotime($booking->check_out)) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Number of Nights:</span>
                <span
                    class="detail-value">{{ abs(strtotime($booking->check_out) - strtotime($booking->check_in)) / (60 * 60 * 24) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Number of Guests:</span>
                <span class="detail-value">{{ $bookingDetails['guests'] ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value">₱{{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>

        <div class="qr-section">
            <h3>📱 Your Booking QR Code</h3>
            <p>Present this QR code at check-in for quick and easy processing.</p>
            <p><strong>QR Code attached as: booking_qr_code.png</strong></p>
            <p><em>You can also show this email or save the QR code to your phone.</em></p>
        </div>

        <div class="important-note">
            <h4>⚠️ Important Information</h4>
            <ul style="margin: 10px 0; padding-left: 20px;">
                <li>Check-in time: 3:00 PM</li>
                <li>Check-out time: 12:00 PM</li>
                <li>Please bring a valid government-issued ID</li>
                <li>Early check-in and late check-out may be available upon request</li>
                <li>For cancellations, please contact us at least 24 hours in advance</li>
            </ul>
        </div>

        <div class="contact-info">
            <h4>📞 Contact Information</h4>
            <p><strong>Reception:</strong> +63 123 456 7890</p>
            <p><strong>Email:</strong> reservations@laiyagrande.com</p>
            <p><strong>Address:</strong> Laiya, San Juan, Batangas, Philippines</p>
            <p><strong>Website:</strong> www.laiyagrande.com</p>
        </div>

        <p>We look forward to making your stay memorable! If you have any questions or special requests, please don't
            hesitate to contact us.</p>

        <p>See you soon at paradise! 🌴</p>

        <div class="footer">
            <p>This is an automated confirmation email. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Laiya Grande Resort. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
