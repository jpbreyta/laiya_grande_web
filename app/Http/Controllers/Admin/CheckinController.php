<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class CheckinController extends Controller
{
    /**
     * Display check-in page with timer
     */
    public function index($id)
    {
        $booking = Booking::with('room')->findOrFail($id);

        return view('admin.checkin.index', compact('booking'));
    }

    /**
     * Process check-in
     */
    public function processCheckin(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:checkin,checkout'
        ]);

        $booking = Booking::findOrFail($id);

        if ($request->action === 'checkin') {
            if ($booking->actual_check_in_time) {
                return response()->json(['success' => false, 'message' => 'Already checked in']);
            }

            $booking->update([
                'actual_check_in_time' => now(),
                'status' => 'active' // or 'checked_in' if you have that status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Guest checked in successfully',
                'check_in_time' => $booking->actual_check_in_time->format('Y-m-d H:i:s')
            ]);
        } elseif ($request->action === 'checkout') {
            if (!$booking->actual_check_in_time) {
                return response()->json(['success' => false, 'message' => 'Guest not checked in yet']);
            }

            if ($booking->actual_check_out_time) {
                return response()->json(['success' => false, 'message' => 'Already checked out']);
            }

            $booking->update([
                'actual_check_out_time' => now(),
                'status' => 'completed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Guest checked out successfully',
                'check_out_time' => $booking->actual_check_out_time->format('Y-m-d H:i:s')
            ]);
        }
    }
}
