# Communication Settings Usage Guide

## Overview
The communication settings system allows you to dynamically control email and SMS notifications throughout the application.

## Database Migration
The migration has been applied and added the following fields to `communication_settings` table:
- SMS Configuration: `sms_provider`, `sms_api_key`, `sms_api_secret`, `sms_sender_id`
- Email Toggles: `email_otp_enabled`, `email_booking_confirmation_enabled`, etc.
- SMS Toggles: `sms_otp_enabled`, `sms_booking_confirmation_enabled`, etc.

## Admin Interface
Navigate to: **Admin > Settings > Communication**

You can now:
1. Configure SMTP settings for email
2. Configure SMS provider settings (Twilio, Nexmo, AWS SNS, Semaphore)
3. Toggle email notifications for:
   - OTP Verification
   - Booking Confirmation
   - Payment Reminder
   - Check-in Reminder
   - Cancellation Notice
4. Toggle SMS notifications for the same events

## Using in Your Code

### Check if Email is Enabled
```php
use App\Helpers\NotificationHelper;

// Before sending an email
if (NotificationHelper::isEmailEnabled('otp')) {
    // Send OTP email
    Mail::to($user)->send(new OtpMail($otp));
}

if (NotificationHelper::isEmailEnabled('booking_confirmation')) {
    // Send booking confirmation email
    Mail::to($user)->send(new BookingConfirmationMail($booking));
}
```

### Check if SMS is Enabled
```php
use App\Helpers\NotificationHelper;

// Before sending an SMS
if (NotificationHelper::isSmsEnabled('otp')) {
    $smsConfig = NotificationHelper::getSmsConfig();
    // Send OTP SMS using the configured provider
    $this->sendSms($user->phone, $otp, $smsConfig);
}

if (NotificationHelper::isSmsEnabled('booking_confirmation')) {
    // Send booking confirmation SMS
    $this->sendSms($user->phone, $message);
}
```

### Get Configuration
```php
// Get SMS configuration
$smsConfig = NotificationHelper::getSmsConfig();
if ($smsConfig) {
    $provider = $smsConfig['provider']; // twilio, nexmo, aws, semaphore
    $apiKey = $smsConfig['api_key'];
    $apiSecret = $smsConfig['api_secret'];
    $senderId = $smsConfig['sender_id'];
}

// Get Email configuration
$emailConfig = NotificationHelper::getEmailConfig();
if ($emailConfig) {
    $host = $emailConfig['host'];
    $port = $emailConfig['port'];
    // ... etc
}
```

## Notification Types
Available notification types:
- `otp` - OTP verification codes
- `booking_confirmation` - Booking confirmation messages
- `payment_reminder` - Payment due reminders
- `checkin_reminder` - Check-in reminders
- `cancellation` - Cancellation notices

## Example: Sending Booking Confirmation
```php
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Mail;

public function sendBookingConfirmation($booking)
{
    $user = $booking->user;
    
    // Send email if enabled
    if (NotificationHelper::isEmailEnabled('booking_confirmation')) {
        Mail::to($user->email)->send(new BookingConfirmationMail($booking));
    }
    
    // Send SMS if enabled
    if (NotificationHelper::isSmsEnabled('booking_confirmation')) {
        $smsConfig = NotificationHelper::getSmsConfig();
        if ($smsConfig) {
            $message = "Your booking #{$booking->id} has been confirmed!";
            $this->sendSms($user->phone, $message, $smsConfig);
        }
    }
}
```

## Changes Made

### 1. Sidebar Navigation
- Removed non-existent settings pages (Reservation, Payment, Room, Food, System, User Management, Reports)
- Kept only: General Settings and Communication

### 2. Communication Settings Page
- Removed static Email Templates section
- Added dynamic toggle switches for Email notifications
- Added dynamic toggle switches for SMS notifications
- All settings are now stored in the database
- Form submits to `admin.settings.communication.update` route

### 3. Controller Updates
- Added `updateCommunication()` method to handle form submission
- Properly handles checkbox toggles (unchecked = false, checked = true)
- Validates all input fields
- Shows success/error messages

### 4. Model Updates
- Added all new fields to `$fillable` array
- Added `$casts` for boolean fields to ensure proper type conversion

## Next Steps
To fully integrate this system:
1. Update your OTP sending logic to check `NotificationHelper::isEmailEnabled('otp')` and `NotificationHelper::isSmsEnabled('otp')`
2. Update booking confirmation logic to check the respective toggles
3. Implement SMS sending functionality using the configured provider
4. Update any other notification sending code to respect these settings
