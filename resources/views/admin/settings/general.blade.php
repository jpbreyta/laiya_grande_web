@extends('admin.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">General Settings</h1>
                <p class="text-gray-600 mt-1">Configure basic resort information and branding</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.settings.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Settings
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>

        <!-- Resort Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                    <i class="fas fa-building text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Resort Information</h3>
                    <p class="text-sm text-gray-600">Basic information about your resort</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resort Name</label>
                    <input type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Laiya Grande Resort" value="Laiya Grande Resort">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tagline</label>
                    <input type="text"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Your perfect beach getaway" value="Your perfect beach getaway">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                    <input type="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="info@laiyagrande.com" value="info@laiyagrande.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="tel"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="+63 123 456 7890" value="+63 123 456 7890">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter full address">Laiya, Batangas, Philippines</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Brief description of your resort">Experience luxury and relaxation at Laiya Grande Resort, nestled along the pristine beaches of Laiya. Our resort offers world-class amenities, exceptional service, and unforgettable memories for every guest.</textarea>
                </div>
            </div>
        </div>

        <!-- Branding & Logo -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                    <i class="fas fa-palette text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Branding & Logo</h3>
                    <p class="text-sm text-gray-600">Upload and manage your resort's branding assets</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <img src="{{ asset('logo.png') }}" alt="Current Logo" class="w-16 h-16 mx-auto mb-2">
                            <p class="text-sm text-gray-600">Current logo</p>
                        </div>
                        <input type="file" accept="image/*" class="hidden" id="logo-upload">
                        <label for="logo-upload"
                            class="cursor-pointer bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-block">
                            <i class="fas fa-upload mr-2"></i>Change Logo
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <img src="{{ asset('favicon.ico') }}" alt="Current Favicon" class="w-8 h-8 mx-auto mb-2">
                            <p class="text-sm text-gray-600">Current favicon</p>
                        </div>
                        <input type="file" accept="image/*" class="hidden" id="favicon-upload">
                        <label for="favicon-upload"
                            class="cursor-pointer bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-block">
                            <i class="fas fa-upload mr-2"></i>Change Favicon
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Hours -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Business Hours</h3>
                    <p class="text-sm text-gray-600">Set operating hours for different resort services</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Reception -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-concierge-bell text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Reception</p>
                            <p class="text-sm text-gray-600">Front desk and check-in services</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="time" class="px-2 py-1 border border-gray-300 rounded text-sm" value="06:00">
                        <span class="text-gray-500">to</span>
                        <input type="time" class="px-2 py-1 border border-gray-300 rounded text-sm" value="22:00">
                    </div>
                </div>

                <!-- Restaurant -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-utensils text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Restaurant</p>
                            <p class="text-sm text-gray-600">Dining services</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="time" class="px-2 py-1 border border-gray-300 rounded text-sm" value="07:00">
                        <span class="text-gray-500">to</span>
                        <input type="time" class="px-2 py-1 border border-gray-300 rounded text-sm" value="21:00">
                    </div>
                </div>

                <!-- Pool & Beach -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-swimmer text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Pool & Beach</p>
                            <p class="text-sm text-gray-600">Swimming pool and beach access</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="time" class="px-2 py-1 border border-gray-300 rounded text-sm" value="06:00">
                        <span class="text-gray-500">to</span>
                        <input type="time" class="px-2 py-1 border border-gray-300 rounded text-sm" value="18:00">
                    </div>
                </div>

                <!-- Activities -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-hiking text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Activities</p>
                            <p class="text-sm text-gray-600">Sports and recreational activities</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="time" class="px-2 py-1 border border-gray-300 rounded text-sm" value="08:00">
                        <span class="text-gray-500">to</span>
                        <input type="time" class="px-2 py-1 border border-gray-300 rounded text-sm" value="17:00">
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                    <i class="fas fa-share-alt text-indigo-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Social Media Links</h3>
                    <p class="text-sm text-gray-600">Connect your social media accounts</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook
                    </label>
                    <input type="url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="https://facebook.com/laiyagrande" value="https://facebook.com/laiyagrande">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram
                    </label>
                    <input type="url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="https://instagram.com/laiyagrande" value="https://instagram.com/laiyagrande">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-twitter text-blue-400 mr-2"></i>Twitter
                    </label>
                    <input type="url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="https://twitter.com/laiyagrande">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-tripadvisor text-green-600 mr-2"></i>TripAdvisor
                    </label>
                    <input type="url"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="https://tripadvisor.com/laiyagrande">
                </div>
            </div>
        </div>

        <!-- System Preferences -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                    <i class="fas fa-cog text-gray-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">System Preferences</h3>
                    <p class="text-sm text-gray-600">General system settings and preferences</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                        <option value="PHP" selected>PHP (₱)</option>
                        <option value="USD">USD ($)</option>
                        <option value="EUR">EUR (€)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                        <option value="d/m/Y">DD/MM/YYYY</option>
                        <option value="m/d/Y" selected>MM/DD/YYYY</option>
                        <option value="Y-m-d">YYYY-MM-DD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Format</label>
                    <select
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                        <option value="12" selected>12 Hour</option>
                        <option value="24">24 Hour</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection
