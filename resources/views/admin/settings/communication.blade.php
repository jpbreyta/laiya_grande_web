@extends('admin.settings.layouts.app')

@section('content')
    <form action="{{ route('admin.settings.communication.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Communication Settings</h1>
                <p class="text-gray-600 mt-1">Configure email, SMS, and notification preferences</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.settings.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Settings
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Email Configuration -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Email Configuration</h3>
                    <p class="text-sm text-gray-600">Configure SMTP settings for sending emails</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                    <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="smtp.gmail.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                    <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings->smtp_port) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="587">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings->smtp_username) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="your-email@gmail.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings->smtp_password) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                    <select name="smtp_encryption"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="tls"
                            {{ old('smtp_encryption', $settings->smtp_encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl"
                            {{ old('smtp_encryption', $settings->smtp_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="none"
                            {{ old('smtp_encryption', $settings->smtp_encryption) == 'none' ? 'selected' : '' }}>None
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                    <input type="email" name="from_address" value="{{ old('from_address', $settings->from_address) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="noreply@laiyagrande.com">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                    <input type="text" name="from_name" value="{{ old('from_name', $settings->from_name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Laiya Grande Resort">
                </div>
            </div>
        </div>

        <!-- SMS Configuration -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                    <i class="fas fa-sms text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">SMS Configuration</h3>
                    <p class="text-sm text-gray-600">Configure SMS provider for notifications</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMS Provider</label>
                    <select name="sms_provider"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Provider</option>
                        <option value="twilio"
                            {{ old('sms_provider', $settings->sms_provider) == 'twilio' ? 'selected' : '' }}>Twilio
                        </option>
                        <option value="nexmo"
                            {{ old('sms_provider', $settings->sms_provider) == 'nexmo' ? 'selected' : '' }}>Nexmo</option>
                        <option value="aws"
                            {{ old('sms_provider', $settings->sms_provider) == 'aws' ? 'selected' : '' }}>AWS SNS</option>
                        <option value="semaphore"
                            {{ old('sms_provider', $settings->sms_provider) == 'semaphore' ? 'selected' : '' }}>Semaphore
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="text" name="sms_api_key" value="{{ old('sms_api_key', $settings->sms_api_key) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Your API Key">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Secret</label>
                    <input type="password" name="sms_api_secret"
                        value="{{ old('sms_api_secret', $settings->sms_api_secret) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Your API Secret">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                    <input type="text" name="sms_sender_id"
                        value="{{ old('sms_sender_id', $settings->sms_sender_id) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="LaiyaGrande">
                </div>
            </div>
        </div>

        <!-- Email Notification Toggles -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                    <i class="fas fa-envelope-open-text text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Email Notifications</h3>
                    <p class="text-sm text-gray-600">Enable or disable email notifications for different events</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">OTP Verification</p>
                        <p class="text-sm text-gray-600">Send OTP codes via email for authentication</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_otp_enabled" value="1"
                            {{ old('email_otp_enabled', $settings->email_otp_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Booking Confirmation</p>
                        <p class="text-sm text-gray-600">Send email when booking is confirmed</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_booking_confirmation_enabled" value="1"
                            {{ old('email_booking_confirmation_enabled', $settings->email_booking_confirmation_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Payment Reminder</p>
                        <p class="text-sm text-gray-600">Send payment due reminders via email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_payment_reminder_enabled" value="1"
                            {{ old('email_payment_reminder_enabled', $settings->email_payment_reminder_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Check-in Reminder</p>
                        <p class="text-sm text-gray-600">Send reminder email before check-in date</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_checkin_reminder_enabled" value="1"
                            {{ old('email_checkin_reminder_enabled', $settings->email_checkin_reminder_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Cancellation Notice</p>
                        <p class="text-sm text-gray-600">Send email when booking is cancelled</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="email_cancellation_enabled" value="1"
                            {{ old('email_cancellation_enabled', $settings->email_cancellation_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- SMS Notification Toggles -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                    <i class="fas fa-comment-dots text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">SMS Notifications</h3>
                    <p class="text-sm text-gray-600">Enable or disable SMS notifications for different events</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">OTP Verification</p>
                        <p class="text-sm text-gray-600">Send OTP codes via SMS for authentication</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_otp_enabled" value="1"
                            {{ old('sms_otp_enabled', $settings->sms_otp_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Booking Confirmation</p>
                        <p class="text-sm text-gray-600">Send SMS when booking is confirmed</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_booking_confirmation_enabled" value="1"
                            {{ old('sms_booking_confirmation_enabled', $settings->sms_booking_confirmation_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Payment Reminder</p>
                        <p class="text-sm text-gray-600">Send payment due reminders via SMS</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_payment_reminder_enabled" value="1"
                            {{ old('sms_payment_reminder_enabled', $settings->sms_payment_reminder_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Check-in Reminder</p>
                        <p class="text-sm text-gray-600">Send reminder SMS before check-in date</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_checkin_reminder_enabled" value="1"
                            {{ old('sms_checkin_reminder_enabled', $settings->sms_checkin_reminder_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Cancellation Notice</p>
                        <p class="text-sm text-gray-600">Send SMS when booking is cancelled</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="sms_cancellation_enabled" value="1"
                            {{ old('sms_cancellation_enabled', $settings->sms_cancellation_enabled) ? 'checked' : '' }}
                            class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600">
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </form>
@endsection
