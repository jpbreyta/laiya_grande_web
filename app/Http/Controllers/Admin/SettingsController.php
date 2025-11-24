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
        $settings = GeneralSetting::first() ?? new GeneralSetting();
        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $settings = GeneralSetting::first() ?? new GeneralSetting();

        $validated = $request->validate([
            'resort_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'description' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_tripadvisor' => 'nullable|url',
            'reception_hours_start' => 'nullable|date_format:H:i',
            'reception_hours_end' => 'nullable|date_format:H:i',
            'restaurant_hours_start' => 'nullable|date_format:H:i',
            'restaurant_hours_end' => 'nullable|date_format:H:i',
            'pool_hours_start' => 'nullable|date_format:H:i',
            'pool_hours_end' => 'nullable|date_format:H:i',
            'activities_hours_start' => 'nullable|date_format:H:i',
            'activities_hours_end' => 'nullable|date_format:H:i',
            'currency' => 'required|string|max:3',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($settings->logo_path && Storage::exists('public/' . $settings->logo_path)) {
                Storage::delete('public/' . $settings->logo_path);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path && Storage::exists('public/' . $settings->favicon_path)) {
                Storage::delete('public/' . $settings->favicon_path);
            }
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            $validated['favicon_path'] = $faviconPath;
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->back()->with('success', 'General settings updated successfully!');
    }

    public function communication()
    {
        return view('admin.settings.communication');
    }
}
