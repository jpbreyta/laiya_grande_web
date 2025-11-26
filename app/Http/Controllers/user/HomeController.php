<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\Room;

class HomeController extends Controller
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

        // Fetch active rooms and prepare full image URL
        $rooms = Room::where('status', 'available')->get()->map(function ($room) {
            $room->image_url = $room->image ? asset($room->image) : asset('images/user/luxury-ocean-view-suite-hotel-room.jpg');
            return $room;
        });

        return view('user.home.index', compact('settings', 'rooms'));
    }
}
