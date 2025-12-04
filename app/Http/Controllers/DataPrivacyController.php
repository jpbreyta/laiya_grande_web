<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DataAccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataPrivacyController extends Controller
{
    /**
     * Show privacy policy and consent form
     */
    public function showConsentForm()
    {
        return view('privacy.consent');
    }

    /**
     * Store customer consent (READ-ONLY after creation)
     */
    public function storeConsent(Request $request)
    {
        $request->validate([
            'consent' => 'required|accepted',
        ]);

        $customer = Auth::user()->customer;
        
        // Only allow consent if not already given
        if (!$customer->data_consent) {
            $customer->update([
                'data_consent' => true,
                'consent_given_at' => now(),
            ]);

            DataAccessLog::logAccess(
                Customer::class,
                $customer->id,
                'consent_given',
                'Customer accepted privacy policy'
            );
        }

        return redirect()->route('dashboard')
            ->with('success', 'Thank you for accepting our privacy policy.');
    }

    /**
     * Download customer data (GDPR Right to Data Portability)
     * Customer data is READ-ONLY and cannot be edited or deleted
     */
    public function downloadData()
    {
        $customer = Auth::user()->customer;

        $data = [
            'personal_information' => [
                'firstname' => $customer->firstname,
                'lastname' => $customer->lastname,
                'email' => $customer->email,
                'phone_number' => $customer->phone_number,
            ],
            'bookings' => $customer->bookings()->get()->map(function ($booking) {
                return [
                    'reservation_number' => $booking->reservation_number,
                    'check_in' => $booking->check_in,
                    'check_out' => $booking->check_out,
                    'room' => $booking->room->name ?? 'N/A',
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                ];
            }),
            'reservations' => $customer->reservations()->get()->map(function ($reservation) {
                return [
                    'reservation_number' => $reservation->reservation_number,
                    'check_in' => $reservation->check_in,
                    'check_out' => $reservation->check_out,
                    'room' => $reservation->room->name ?? 'N/A',
                    'total_price' => $reservation->total_price,
                    'status' => $reservation->status,
                ];
            }),
            'note' => 'Customer data is permanent and cannot be edited or deleted for security and legal compliance.',
            'exported_at' => now()->toDateTimeString(),
        ];

        DataAccessLog::logAccess(
            Customer::class,
            $customer->id,
            'data_exported',
            'Customer downloaded their data'
        );

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="my-data-' . now()->format('Y-m-d') . '.json"');
    }
}
