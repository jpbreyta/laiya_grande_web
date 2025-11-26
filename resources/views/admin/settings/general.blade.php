@extends('admin.settings.layouts.app')

@section('content')
    <form action="{{ route('admin.settings.general.update') }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-6">
        @csrf
        @method('PUT')

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

        {{-- 1. ERROR SUMMARY ALERT (New Addition) --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            There were {{ $errors->count() }} errors with your submission:
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resort Name <span class="text-red-500">*</span></label>
                    <input type="text" name="resort_name"
                        value="{{ old('resort_name', $settings->resort_name) }}"
                        class="w-full px-3 py-2 border @error('resort_name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('resort_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tagline</label>
                    <input type="text" name="tagline"
                        value="{{ old('tagline', $settings->tagline) }}"
                        class="w-full px-3 py-2 border @error('tagline') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                     @error('tagline') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email"
                        value="{{ old('contact_email', $settings->contact_email) }}"
                        class="w-full px-3 py-2 border @error('contact_email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('contact_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="tel" name="contact_phone"
                        value="{{ old('contact_phone', $settings->contact_phone) }}"
                        class="w-full px-3 py-2 border @error('contact_phone') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('contact_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="contact_address" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('contact_address', $settings->contact_address) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $settings->description) }}</textarea>
                </div>
            </div>
        </div>

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
                            <img src="{{ $settings->logo_path ? asset('storage/'.$settings->logo_path) : asset('logo.png') }}"
                                alt="Logo" class="w-16 h-16 mx-auto mb-2 object-contain">
                            <p class="text-sm text-gray-600">Current logo</p>
                        </div>

                        <input type="file" name="logo" accept="image/*" class="hidden" id="logo-upload">
                        <label for="logo-upload"
                            class="cursor-pointer bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-block">
                            <i class="fas fa-upload mr-2"></i>Change Logo
                        </label>
                        @error('logo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <img src="{{ $settings->favicon_path ? asset('storage/'.$settings->favicon_path) : asset('favicon.ico') }}"
                                alt="Favicon" class="w-8 h-8 mx-auto mb-2 object-contain">
                            <p class="text-sm text-gray-600">Current favicon</p>
                        </div>

                        <input type="file" name="favicon" accept="image/*" class="hidden" id="favicon-upload">
                        <label for="favicon-upload"
                            class="cursor-pointer bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-block">
                            <i class="fas fa-upload mr-2"></i>Change Favicon
                        </label>
                        @error('favicon') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

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
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-concierge-bell text-green-600 mr-3"></i>
                        <p class="font-medium text-gray-800">Reception</p>
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
                {{-- Repeat similar blocks for other hours... --}}
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                    <i class="fas fa-share-alt text-indigo-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Social Media Links</h3>
                    <p class="text-xs text-gray-500">Must include http:// or https://</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach(['facebook', 'instagram', 'twitter', 'tripadvisor'] as $social)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 capitalize">{{ $social }}</label>
                    <input type="url" name="social_{{ $social }}"
                        value="{{ old('social_'.$social, $settings->{'social_'.$social}) }}"
                        class="w-full px-3 py-2 border @error('social_'.$social) border-red-500 @else border-gray-300 @enderror rounded-lg">
                    @error('social_'.$social) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endforeach
            </div>
        </div>

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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default Currency</label>
                    <select name="currency" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="PHP" {{ old('currency', $settings->currency) == 'PHP' ? 'selected' : '' }}>PHP (₱)</option>
                        <option value="USD" {{ old('currency', $settings->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                        <option value="EUR" {{ old('currency', $settings->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date Format</label>
                    <select name="date_format" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="d/m/Y" {{ old('date_format', $settings->date_format) == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                        <option value="m/d/Y" {{ old('date_format', $settings->date_format) == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                        <option value="Y-m-d" {{ old('date_format', $settings->date_format) == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Format</label>
                    <select name="time_format" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="12" {{ old('time_format', $settings->time_format) == '12' ? 'selected' : '' }}>12 Hour</option>
                        <option value="24" {{ old('time_format', $settings->time_format) == '24' ? 'selected' : '' }}>24 Hour</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
@endsection