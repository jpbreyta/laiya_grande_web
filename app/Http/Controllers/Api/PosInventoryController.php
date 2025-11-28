<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentalItem;
use App\Models\WaterSport;

class PosInventoryController extends Controller
{
    // Get all rental items
    public function getRentalItems()
    {
        $items = RentalItem::all();
        return response()->json(['success' => true, 'items' => $items]);
    }

    // Get all water sports
    public function getWaterSports()
    {
        $sports = WaterSport::all();
        return response()->json(['success' => true, 'sports' => $sports]);
    }

    // Create rental item
    public function createRentalItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_available' => 'boolean'
        ]);

        $item = RentalItem::create($request->all());
        return response()->json(['success' => true, 'item' => $item], 201);
    }

    // Update rental item
    public function updateRentalItem(Request $request, $id)
    {
        $item = RentalItem::findOrFail($id);
        $item->update($request->all());
        return response()->json(['success' => true, 'item' => $item]);
    }


    // Delete rental item
    public function deleteRentalItem($id)
    {
        $item = RentalItem::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true, 'message' => 'Item deleted']);
    }

    // Create water sport
    public function createWaterSport(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_details' => 'required|string',  // Changed to string to allow text descriptions
            'min_participants' => 'nullable|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1'
        ]);

        $sport = WaterSport::create($request->all());
        return response()->json(['success' => true, 'sport' => $sport], 201);
    }

    // Update water sport
    public function updateWaterSport(Request $request, $id)
    {
        $sport = WaterSport::findOrFail($id);
        $sport->update($request->all());
        return response()->json(['success' => true, 'sport' => $sport]);
    }

    // Delete water sport
    public function deleteWaterSport($id)
    {
        $sport = WaterSport::findOrFail($id);
        $sport->delete();
        return response()->json(['success' => true, 'message' => 'Sport deleted']);
    }
}
