<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GuestStay;
use App\Models\RentalItem;
use App\Models\WaterSport;
use App\Models\PointOfSale as POS;
use Illuminate\Support\Facades\DB;

class PosGuestServiceController extends Controller
{
    // Return active guests
    public function activeGuests()
    {
        $guests = GuestStay::where('status', 'checked-in')
            ->get(['id', 'guest_name as name', 'room_id']);
        return response()->json($guests);
    }

    // Return all available items (rental + water sports)
    public function items()
    {
        $rentalItems = RentalItem::where('is_available', true)
            ->get(['id', 'name', 'price', DB::raw("'RENTAL' as type")]);

        $waterSports = WaterSport::all(['id', 'name', DB::raw('0 as price'), DB::raw("'WATER_SPORT' as type")]);

        $allItems = $rentalItems->concat($waterSports);

        return response()->json($allItems);
    }

    // Charge cart to guest
    public function charge(Request $request)
    {
        $request->validate([
            'guest_stay_id' => 'required|exists:guest_stays,id',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.type' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'required|numeric|min:0',
            'total_discount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $guestId = $request->guest_stay_id;
            $totalDiscount = $request->total_discount ?? 0;

            foreach ($request->items as $item) {
                POS::create([
                    'guest_stay_id' => $guestId,
                    'item_name' => $item['name'],
                    'item_type' => $item['type'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'total_amount' => ($item['price'] * $item['qty']) - $item['discount'],
                    'discount' => $item['discount'],
                ]);
            }

            if ($totalDiscount > 0) {
                POS::create([
                    'guest_stay_id' => $guestId,
                    'item_name' => 'TOTAL DISCOUNT',
                    'item_type' => 'DISCOUNT',
                    'quantity' => 1,
                    'price' => -$totalDiscount,
                    'total_amount' => -$totalDiscount,
                    'discount' => $totalDiscount,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Cart successfully charged!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error processing cart.', 'error' => $e->getMessage()], 500);
        }
    }
}
