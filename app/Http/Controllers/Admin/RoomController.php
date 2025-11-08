<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('admin.room.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.room.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'price' => 'required|numeric',
            'capacity' => 'required|integer',
            'availability' => 'required|boolean',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
            'image' => 'nullable|string',
            'rate_name' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'has_aircon' => 'boolean',
            'has_private_cr' => 'boolean',
            'has_kitchen' => 'boolean',
            'has_free_parking' => 'boolean',
            'no_entrance_fee' => 'boolean',
            'no_corkage_fee' => 'boolean',
        ]);

        Room::create($validated);

        return redirect()->route('admin.room.index')->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        return view('admin.room.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('admin.room.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'price' => 'required|numeric',
            'capacity' => 'required|integer',
            'availability' => 'required|boolean',
            'amenities' => 'nullable|array',
            'images' => 'nullable|array',
            'image' => 'nullable|string',
            'rate_name' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'has_aircon' => 'boolean',
            'has_private_cr' => 'boolean',
            'has_kitchen' => 'boolean',
            'has_free_parking' => 'boolean',
            'no_entrance_fee' => 'boolean',
            'no_corkage_fee' => 'boolean',
        ]);

        $room->update($validated);

        return redirect()->route('admin.room.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.room.index')->with('success', 'Room deleted successfully.');
    }
}
