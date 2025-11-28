<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GuestStay;
use App\Models\RentalItem;
use App\Models\WaterSport;
use App\Models\PointOfSale as POS;
use App\Models\PosTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosGuestServiceController extends Controller
{
    // Return active guests
    public function activeGuests()
    {
        $guests = GuestStay::where('status', 'checked-in')
            ->with('room:id,name')
            ->get(['id', 'guest_name as name', 'room_id']);
        return response()->json($guests);
    }

    // Return all available items (rental + water sports)
    public function items()
    {
        $rentalItems = RentalItem::where('is_available', true)
            ->select('id', 'name', 'price', DB::raw("'rental' as type"))
            ->get();

        $waterSports = WaterSport::select('id', 'name', 'price_details as price', DB::raw("'water_sport' as type"))
            ->get();

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
            
            // Generate unique transaction/receipt number
            $receiptNumber = 'RCP-' . date('YmdHis') . '-' . strtoupper(Str::random(4));
            
            // Calculate totals
            $subtotal = 0;
            $itemsData = [];
            
            // Create transaction record first
            $transaction = PosTransaction::create([
                'receipt_number' => $receiptNumber,
                'guest_stay_id' => $guestId,
                'subtotal' => 0, // Will update after
                'discount' => $totalDiscount,
                'total' => 0, // Will update after
                'items_count' => count($request->items),
                'transaction_date' => now(),
            ]);
            
            foreach ($request->items as $item) {
                $itemTotal = ($item['price'] * $item['qty']) - $item['discount'];
                $subtotal += $itemTotal;
                
                POS::create([
                    'guest_stay_id' => $guestId,
                    'transaction_id' => $transaction->id,
                    'item_name' => $item['name'],
                    'item_type' => $item['type'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'total_amount' => $itemTotal,
                    'discount' => $item['discount'],
                ]);
                
                $itemsData[] = [
                    'name' => $item['name'],
                    'type' => $item['type'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'discount' => $item['discount'],
                    'total' => $itemTotal
                ];
            }

            $grandTotal = $subtotal - $totalDiscount;
            
            // Update transaction totals
            $transaction->update([
                'subtotal' => $subtotal,
                'total' => $grandTotal,
            ]);

            DB::commit();
            
            // Return receipt data
            $guest = GuestStay::with('room')->find($guestId);
            
            return response()->json([
                'success' => true,
                'message' => 'Cart successfully charged!',
                'receipt' => [
                    'receipt_number' => $receiptNumber,
                    'transaction_id' => $transaction->id,
                    'guest_name' => $guest->guest_name,
                    'room' => $guest->room->name ?? 'N/A',
                    'date' => now()->format('M d, Y h:i A'),
                    'items' => $itemsData,
                    'subtotal' => $subtotal,
                    'discount' => $totalDiscount,
                    'total' => $grandTotal
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error processing cart.', 'error' => $e->getMessage()], 500);
        }
    }
}
