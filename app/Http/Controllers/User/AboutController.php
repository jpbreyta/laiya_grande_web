<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $settings = GeneralSetting::first();

        if (!$settings) {
            $settings = new GeneralSetting([
                'resort_name' => 'Laiya Grande Resort',
                'tagline' => 'Your tropical escape in San Juan, Batangas',
                'description' => 'Experience paradise with pristine beaches, luxury accommodations, and world-class amenities in the heart of Batangas.'
            ]);
        }

        return view('user.about.index', compact('settings'));
    }
}
