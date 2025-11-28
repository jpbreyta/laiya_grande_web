<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuestStay;

class GuestStayController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = GuestStay::with('booking', 'room');

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                  ->orWhereHas('booking', function($q2) use ($search) {
                      $q2->where('reservation_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('room', function($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Handle "all" entries
        if ($perPage === 'all') {
            $guestStays = $query->orderBy('check_in_time', 'desc')->get();
            // Create a mock paginator for consistency
            $guestStays = new \Illuminate\Pagination\LengthAwarePaginator(
                $guestStays,
                $guestStays->count(),
                $guestStays->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $guestStays = $query->orderBy('check_in_time', 'desc')->paginate($perPage);
        }

        return view('admin.guest-stays.index', compact('guestStays', 'status'));
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
