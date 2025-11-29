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

    public function exportCsv()
    {
        $filename = "rooms_" . date('Y-m-d') . ".csv";
        $rooms = Room::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($rooms) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Price', 'Capacity', 'Status', 'Availability', 'Amenities']);

            foreach ($rooms as $room) {
                $amenities = is_array($room->amenities) ? implode(', ', $room->amenities) : '';
                
                fputcsv($file, [
                    $room->id,
                    $room->name,
                    number_format($room->price, 2),
                    $room->capacity,
                    $room->status,
                    $room->availability ? 'Available' : 'Not Available',
                    $amenities,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $rooms = Room::all();
        
        $html = view('admin.room.pdf', compact('rooms'))->render();
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="rooms_' . date('Y-m-d') . '.html"');
    }
}
