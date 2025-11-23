@extends('admin.settings.layouts.app')

@section('content')
    <form action="{{ route('admin.settings.general.update') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-6">
        @csrf
        @method('PUT')

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

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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
                <!-- Resort Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resort Name</label>
                    <input type="text" name="resort_name"
                        value="{{ old('resort_name', $settings->resort_name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Tagline -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tagline</label>
                    <input type="text" name="tagline"
                        value="{{ old('tagline', $settings->tagline) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Contact Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                    <input type="email" name="contact_email"
                        value="{{ old('contact_email', $settings->contact_email) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Contact Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="tel" name="contact_phone"
                        value="{{ old('contact_phone', $settings->contact_phone) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="contact_address" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('contact_address', $settings->contact_address) }}</textarea>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $settings->description) }}</textarea>
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
                <!-- Logo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <img src="{{ $settings->logo_path ? asset('storage/'.$settings->logo_path) : asset('logo.png') }}"
                                alt="Logo" class="w-16 h-16 mx-auto mb-2">
                            <p class="text-sm text-gray-600">Current logo</p>
                        </div>

                        <input type="file" name="logo" accept="image/*" class="hidden" id="logo-upload">
                        <label for="logo-upload"
                            class="cursor-pointer bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-block">
                            <i class="fas fa-upload mr-2"></i>Change Logo
                        </label>
                    </div>
                </div>

                <!-- Favicon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <img src="{{ $settings->favicon_path ? asset('storage/'.$settings->favicon_path) : asset('favicon.ico') }}"
                                alt="Favicon" class="w-8 h-8 mx-auto mb-2">
                            <p class="text-sm text-gray-600">Current favicon</p>
                        </div>

                        <input type="file" name="favicon" accept="image/*" class="hidden" id="favicon-upload">
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
                    <p class="text-sm text-gray-600">Set operating hours</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Reception -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-concierge-bell text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Reception</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="time" name="reception_hours_start"
                            value="{{ old('reception_hours_start', $settings->reception_hours_start) }}"
                            class="px-2 py-1 border border-gray-300 rounded text-sm">
                        <span class="text-gray-500">to</span>
                        <input type="time" name="reception_hours_end"
                            value="{{ old('reception_hours_end', $settings->reception_hours_end) }}"
                            class="px-2 py-1 border border-gray-300 rounded text-sm">
                    </div>
                </div>

                <!-- Restaurant -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-utensils text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Restaurant</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="time" name="restaurant_hours_start"
                            value="{{ old('restaurant_hours_start', $settings->restaurant_hours_start) }}"
                            class="px-2 py-1 border border-gray-300 rounded text-sm">
                        <span class="text-gray-500">to</span>
                        <input type="time" name="restaurant_hours_end"
                            value="{{ old('restaurant_hours_end', $settings->restaurant_hours_end) }}"
                            class="px-2 py-1 border border-gray-300 rounded text-sm">
                    </div>
                </div>

                <!-- Pool -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-swimmer text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Pool & Beach</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="time" name="pool_hours_start"
                            value="{{ old('pool_hours_start', $settings->pool_hours_start) }}"
                            class="px-2 py-1 border border-gray-300 rounded text-sm">
                        <span class="text-gray-500">to</span>
                        <input type="time" name="pool_hours_end"
                            value="{{ old('pool_hours_end', $settings->pool_hours_end) }}"
                            class="px-2 py-1 border border-gray-300 rounded text-sm">
                    </div>
                </div>

                <!-- Activities -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-hiking text-green-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Activities</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <input type="time" name="activities_hours_start"
                            value="{{ old('activities_hours_start', $settings->activities_hours_start) }}"
                            class="px-2 py-1 border border-gray-300 rounded text-sm">
                        <span class="text-gray-500">to</span>
                        <input type="time" name="activities_hours_end"
                            value="{{ old('activities_hours_end', $settings->activities_hours_end) }}"
                            class="px-2 py-1 border border-gray-300 rounded text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                    <i class="fas fa-share-alt text-indigo-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Social Media Links</h3>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Facebook -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                    <input type="url" name="social_facebook"
                        value="{{ old('social_facebook', $settings->social_facebook) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                    <input type="url" name="social_instagram"
                        value="{{ old('social_instagram', $settings->social_instagram) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Twitter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                    <input type="url" name="social_twitter"
                        value="{{ old('social_twitter', $settings->social_twitter) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Tripadvisor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">TripAdvisor</label>
                    <input type="url" name="social_tripadvisor"
                        value="{{ old('social_tripadvisor', $settings->social_tripadvisor) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
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
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Currency -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                    <select name="currency"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="PHP" {{ old('currency', $settings->currency) == 'PHP' ? 'selected' : '' }}>PHP (₱)</option>
                        <option value="USD" {{ old('currency', $settings->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ old('currency', $settings->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                    </select>
                </div>

                <!-- Date Format -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                    <select name="date_format"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="d/m/Y" {{ old('date_format', $settings->date_format) == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                        <option value="m/d/Y" {{ old('date_format', $settings->date_format) == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                        <option value="Y-m-d" {{ old('date_format', $settings->date_format) == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                    </select>
                </div>

                <!-- Time Format -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Format</label>
                    <select name="time_format"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="12" {{ old('time_format', $settings->time_format) == '12' ? 'selected' : '' }}>12 Hour</option>
                        <option value="24" {{ old('time_format', $settings->time_format) == '24' ? 'selected' : '' }}>24 Hour</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
@endsection
