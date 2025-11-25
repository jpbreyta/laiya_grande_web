<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuestStay;

class GuestStayController extends Controller
{
    public function index()
    {
        // Get all guest stays, optionally paginate
        $guestStays = GuestStay::with('booking', 'room')
            ->orderBy('check_in_time', 'desc')
            ->paginate(10);

        return view('admin.guest-stays.index', compact('guestStays'));
    }

    public function show($id)
    {
        $guestStay = GuestStay::with('booking', 'room')->findOrFail($id);

        return view('admin.guest-stays.show', compact('guestStay'));
    }

    public function checkout(Request $request, $id)
    {
        $guestStay = GuestStay::findOrFail($id);

        if ($guestStay->status === 'checked-out') {
            return response()->json([
                'success' => false,
                'message' => 'Guest is already checked out.'
            ]);
        }

        $guestStay->update([
            'status' => 'checked-out',
            'check_out_time' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guest has been checked out successfully.'
        ]);
    }
    public function storeOrCheckin(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
        ]);

        $bookingId = $request->booking_id;

        $guestStay = GuestStay::updateOrCreate(
            ['booking_id' => $bookingId],
            [
                'guest_name' => optional(\App\Models\Booking::find($bookingId))->firstname
                    . ' ' . optional(\App\Models\Booking::find($bookingId))->lastname,
                'room_id' => optional(\App\Models\Booking::find($bookingId))->room_id,
                'status' => 'checked-in',
                'check_in_time' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'guest_stay' => $guestStay,
        ]);
    }
}
