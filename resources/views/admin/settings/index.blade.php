@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
                <p class="text-gray-600 mt-1">Manage your resort's configuration and preferences</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-history mr-2"></i>Recent Changes
                </button>
            </div>
        </div>

        <!-- Settings Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- General Settings -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                        <i class="fas fa-cog text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">General Settings</h3>
                        <p class="text-sm text-gray-600">Site info, branding & business hours</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-building mr-2"></i>
                        <span>Resort information</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-palette mr-2"></i>
                        <span>Logo & branding</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Operating hours</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.settings.general') }}"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

            <!-- Reservation Settings -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-check text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Reservation Settings</h3>
                        <p class="text-sm text-gray-600">Booking policies & check-in rules</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Check-in/out times</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-ban mr-2"></i>
                        <span>Cancellation policies</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>Advance booking limits</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#"
                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

            <!-- Payment Settings -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                        <i class="fas fa-file-invoice-dollar text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Payment Settings</h3>
                        <p class="text-sm text-gray-600">Payment proof & verification</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-file-upload mr-2"></i>
                        <span>Proof requirements</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>Verification workflow</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-folder mr-2"></i>
                        <span>Document retention</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#"
                        class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

            <!-- Room Settings -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                        <i class="fas fa-bed text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Room Settings</h3>
                        <p class="text-sm text-gray-600">Room categories & amenities</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-tags mr-2"></i>
                        <span>Room categories</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-concierge-bell mr-2"></i>
                        <span>Amenities management</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-users mr-2"></i>
                        <span>Capacity limits</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#"
                        class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

            <!-- Food Settings -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center mr-3">
                        <i class="fas fa-utensils text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Food Settings</h3>
                        <p class="text-sm text-gray-600">Menu management & dining</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-list mr-2"></i>
                        <span>Menu categories</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Service hours</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-allergies mr-2"></i>
                        <span>Dietary options</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#"
                        class="w-full bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

            <!-- Communication Settings -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-teal-100 flex items-center justify-center mr-3">
                        <i class="fas fa-envelope text-teal-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Communication</h3>
                        <p class="text-sm text-gray-600">Email, SMS & notifications</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-mail-bulk mr-2"></i>
                        <span>Email configuration</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-sms mr-2"></i>
                        <span>SMS notifications</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-language mr-2"></i>
                        <span>Language settings</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.settings.communication') }}"
                        class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

            <!-- System Settings -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                        <i class="fas fa-server text-gray-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">System Settings</h3>
                        <p class="text-sm text-gray-600">Security, backups & maintenance</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Security settings</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-save mr-2"></i>
                        <span>Backup configuration</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-tools mr-2"></i>
                        <span>Maintenance mode</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#"
                        class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

            <!-- User Management -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                        <i class="fas fa-users-cog text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">User Management</h3>
                        <p class="text-sm text-gray-600">Admin roles & permissions</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-user-shield mr-2"></i>
                        <span>Admin roles</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-key mr-2"></i>
                        <span>Access permissions</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-lock mr-2"></i>
                        <span>Password policies</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#"
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

            <!-- Reports Settings -->
            <div
                class="dashboard-card bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center mr-3">
                        <i class="fas fa-chart-bar text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Reports Settings</h3>
                        <p class="text-sm text-gray-600">Dashboard & export preferences</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        <span>Dashboard widgets</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-file-export mr-2"></i>
                        <span>Export formats</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar-week mr-2"></i>
                        <span>Scheduled reports</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="#"
                        class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors inline-block text-center">
                        Configure
                    </a>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button
                    class="flex items-center justify-center px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Export Settings
                </button>
                <button
                    class="flex items-center justify-center px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors">
                    <i class="fas fa-upload mr-2"></i>
                    Import Settings
                </button>
                <button
                    class="flex items-center justify-center px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                    <i class="fas fa-undo mr-2"></i>
                    Reset to Defaults
                </button>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Changes</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-cog text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">General settings updated</p>
                            <p class="text-xs text-gray-500">Business hours modified</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">2 hours ago</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Reservation settings updated</p>
                            <p class="text-xs text-gray-500">Check-in time changed to 2:00 PM</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">1 day ago</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                            <i class="fas fa-envelope text-purple-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Email settings configured</p>
                            <p class="text-xs text-gray-500">SMTP settings updated</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">3 days ago</span>
                </div>
            </div>
        </div>
    </div>
@endsection
