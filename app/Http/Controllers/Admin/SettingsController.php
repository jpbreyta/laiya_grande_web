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
        return view('admin.settings.communication');
    }
}