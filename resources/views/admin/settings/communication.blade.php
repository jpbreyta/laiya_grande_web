@extends('admin.settings.layouts.app')

@section('content')
    <div class="space-y-6">
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
                <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>

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
                    <input type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="smtp.gmail.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                    <input type="number"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="587">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="your-email@gmail.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                    <input type="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="noreply@laiyagrande.com">
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
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select Provider</option>
                        <option value="twilio">Twilio</option>
                        <option value="nexmo">Nexmo</option>
                        <option value="aws">AWS SNS</option>
                        <option value="semaphore">Semaphore</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Your API Key">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Secret</label>
                    <input type="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Your API Secret">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                    <input type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="LaiyaGrande">
                </div>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                    <i class="fas fa-bell text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Notification Settings</h3>
                    <p class="text-sm text-gray-600">Configure when and how notifications are sent</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Booking Notifications -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Booking Notifications</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">Booking Confirmation</p>
                                <p class="text-sm text-gray-600">Send when booking is confirmed</p>
                            </div>
                            <div class="flex space-x-2">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2 text-sm">Email</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2 text-sm">SMS</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">Payment Reminder</p>
                                <p class="text-sm text-gray-600">Send payment due reminders</p>
                            </div>
                            <div class="flex space-x-2">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" checked>
                                    <span class="ml-2 text-sm">Email</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2 text-sm">SMS</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">Check-in Reminder</p>
                                <p class="text-sm text-gray-600">Send day before check-in</p>
                            </div>
                            <div class="flex space-x-2">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" checked>
                                    <span class="ml-2 text-sm">Email</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" checked>
                                    <span class="ml-2 text-sm">SMS</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">Cancellation Notice</p>
                                <p class="text-sm text-gray-600">Send when booking is cancelled</p>
                            </div>
                            <div class="flex space-x-2">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" checked>
                                    <span class="ml-2 text-sm">Email</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2 text-sm">SMS</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Notifications -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Admin Notifications</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">New Booking</p>
                                <p class="text-sm text-gray-600">Alert when new booking is made</p>
                            </div>
                            <div class="flex space-x-2">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" checked>
                                    <span class="ml-2 text-sm">Email</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2 text-sm">SMS</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">Payment Verification</p>
                                <p class="text-sm text-gray-600">Alert when payment needs verification</p>
                            </div>
                            <div class="flex space-x-2">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" checked>
                                    <span class="ml-2 text-sm">Email</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" checked>
                                    <span class="ml-2 text-sm">SMS</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Language & Localization -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                    <i class="fas fa-language text-indigo-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Language & Localization</h3>
                    <p class="text-sm text-gray-600">Configure language and regional settings</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Language</label>
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="en">English</option>
                        <option value="tl">Filipino (Tagalog)</option>
                        <option value="es">Spanish</option>
                        <option value="zh">Chinese</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="Asia/Manila">Asia/Manila (GMT+8)</option>
                        <option value="UTC">UTC</option>
                        <option value="America/New_York">America/New_York (GMT-5)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="m/d/Y">MM/DD/YYYY</option>
                        <option value="d/m/Y">DD/MM/YYYY</option>
                        <option value="Y-m-d">YYYY-MM-DD</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Email Templates -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center mr-3">
                    <i class="fas fa-file-alt text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Email Templates</h3>
                    <p class="text-sm text-gray-600">Customize email templates for different notifications</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Booking Confirmation Template</p>
                        <p class="text-sm text-gray-600">Template sent when booking is confirmed</p>
                    </div>
                    <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Template
                    </button>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Payment Reminder Template</p>
                        <p class="text-sm text-gray-600">Template for payment due reminders</p>
                    </div>
                    <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Template
                    </button>
                </div>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Check-in Reminder Template</p>
                        <p class="text-sm text-gray-600">Template sent before check-in date</p>
                    </div>
                    <button class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Template
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
