<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function general()
    {
        $settings = GeneralSetting::firstOrCreate([]);
        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $settings = GeneralSetting::firstOrCreate([]);

        $validated = $request->validate([
            'resort_name'            => 'required|string|max:255',
            'tagline'                => 'nullable|string|max:255',
            'contact_email'          => 'required|email',
            'contact_phone'          => 'nullable|string|max:20',
            'contact_address'        => 'nullable|string',
            'description'            => 'nullable|string',

            // Common error source: URL must have http:// or https://
            'social_facebook'        => 'nullable|url',
            'social_instagram'       => 'nullable|url',
            'social_twitter'         => 'nullable|url',
            'social_tripadvisor'     => 'nullable|url',

            // Removed date_format:H:i to allow seconds (H:i:s) if browser sends them
            'reception_hours_start'  => 'nullable', 
            'reception_hours_end'    => 'nullable',
            'restaurant_hours_start' => 'nullable',
            'restaurant_hours_end'   => 'nullable',
            'pool_hours_start'       => 'nullable',
            'pool_hours_end'         => 'nullable',
            'activities_hours_start' => 'nullable',
            'activities_hours_end'   => 'nullable',

            'currency'               => 'required|string|max:3',
            'date_format'            => 'required|string',
            'time_format'            => 'required|string',

            'logo'                   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon'                => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path && Storage::disk('public')->exists($settings->favicon_path)) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $validated['favicon_path'] = $request->file('favicon')->store('favicons', 'public');
        }

        // Cleanup: Remove the file objects from array so we only pass paths to the model
        unset($validated['logo']);
        unset($validated['favicon']);

        // Update the settings
        $settings->fill($validated);
        $settings->save();

        return back()->with('success', 'General settings updated successfully!');
    }

    
    public function communication()
    {
        $settings = \App\Models\CommunicationSetting::firstOrCreate([]);
        return view('admin.settings.communication', compact('settings'));
    }

    public function updateCommunication(Request $request)
    {
        $settings = \App\Models\CommunicationSetting::firstOrCreate([]);

        $validated = $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|in:tls,ssl,none',
            'from_address' => 'nullable|email|max:255',
            'from_name' => 'nullable|string|max:255',
            'sms_provider' => 'nullable|string|max:255',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_api_secret' => 'nullable|string|max:255',
            'sms_sender_id' => 'nullable|string|max:255',
            'email_otp_enabled' => 'boolean',
            'email_booking_confirmation_enabled' => 'boolean',
            'email_payment_reminder_enabled' => 'boolean',
            'email_checkin_reminder_enabled' => 'boolean',
            'email_cancellation_enabled' => 'boolean',
            'sms_otp_enabled' => 'boolean',
            'sms_booking_confirmation_enabled' => 'boolean',
            'sms_payment_reminder_enabled' => 'boolean',
            'sms_checkin_reminder_enabled' => 'boolean',
            'sms_cancellation_enabled' => 'boolean',
        ]);

        // Convert checkbox values (checkboxes not sent when unchecked)
        $toggles = [
            'email_otp_enabled',
            'email_booking_confirmation_enabled',
            'email_payment_reminder_enabled',
            'email_checkin_reminder_enabled',
            'email_cancellation_enabled',
            'sms_otp_enabled',
            'sms_booking_confirmation_enabled',
            'sms_payment_reminder_enabled',
            'sms_checkin_reminder_enabled',
            'sms_cancellation_enabled',
        ];

        foreach ($toggles as $toggle) {
            $validated[$toggle] = $request->has($toggle);
        }

        $settings->fill($validated);
        $settings->save();

        return back()->with('success', 'Communication settings updated successfully!');
    }
}