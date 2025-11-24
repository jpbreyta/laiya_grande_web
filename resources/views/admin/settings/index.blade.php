@extends('admin.settings.layouts.app')

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
