<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Room;
use App\Models\RoomRate;
use App\Services\Communication\StaffNotificationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function __construct(
        private readonly RoomAvailabilityService $availability,
        private readonly StaffNotificationService $notifications
    ) {
    }

    /**
     * Create one booking row for each reserved room unit.
     *
     * Room rows are locked to prevent concurrent requests from overbooking
     * the same inventory.
     */
    public function createFromCart(
        array $cart,
        array $customerData,
        string $checkInDate,
        string $checkOutDate,
        int $totalGuests,
        ?string $specialRequest,
        string $paymentMethod,
        array $paymentProof,
        string $numberPrefix = 'BKG'
    ): Collection {
        $checkIn = Carbon::parse($checkInDate)->startOfDay();
        $checkOut = Carbon::parse($checkOutDate)->startOfDay();
        $nights = $checkIn->diffInDays($checkOut);

        if ($nights < 1) {
            throw ValidationException::withMessages([
                'check_out' => 'The check-out date must be after the check-in date.',
            ]);
        }

        return DB::transaction(function () use (
            $cart,
            $customerData,
            $checkIn,
            $checkOut,
            $nights,
            $totalGuests,
            $specialRequest,
            $paymentMethod,
            $paymentProof,
            $numberPrefix
        ): Collection {
            $customer = $this->upsertCustomer($customerData);
            $preparedItems = $this->prepareItems($cart, $checkIn, $checkOut, $nights);

            $totalUnits = collect($preparedItems)->sum('quantity');
            $totalCapacity = collect($preparedItems)
                ->sum(fn (array $item): int => $item['room']->capacity * $item['quantity']);

            if ($totalGuests > $totalCapacity) {
                throw ValidationException::withMessages([
                    'guests' => "The selected rooms can accommodate only {$totalCapacity} guest(s).",
                ]);
            }

            // Each normalized booking row represents one physical room unit.
            if ($totalGuests < $totalUnits) {
                throw ValidationException::withMessages([
                    'guests' => "At least {$totalUnits} guest(s) are required for {$totalUnits} reserved room unit(s).",
                ]);
            }

            $guestAllocations = $this->allocateGuests($preparedItems, $totalGuests);
            $bookings = new Collection();
            $allocationIndex = 0;

            foreach ($preparedItems as $preparedItem) {
                /** @var Room $room */
                $room = $preparedItem['room'];
                /** @var RoomRate $rate */
                $rate = $preparedItem['rate'];

                for ($unit = 0; $unit < $preparedItem['quantity']; $unit++) {
                    $quotedTotal = (float) $rate->price * $nights;

                    $booking = Booking::create([
                        'booking_number' => Booking::generateBookingNumber($numberPrefix),
                        'customer_id' => $customer->id,
                        'room_id' => $room->id,
                        'room_rate_id' => $rate->id,
                        'source' => 'online',
                        'check_in' => $checkIn->toDateString(),
                        'check_out' => $checkOut->toDateString(),
                        'number_of_guests' => $guestAllocations[$allocationIndex],
                        'special_request' => $specialRequest,
                        'status' => 'pending',
                        'quoted_total' => $quotedTotal,
                        'expires_at' => now()->addHours(24),
                    ]);

                    Payment::create([
                        'booking_id' => $booking->id,
                        'amount_paid' => $quotedTotal,
                        'payment_stage' => 'full',
                        'status' => 'pending',
                        'payment_method' => $paymentMethod,
                        'paid_at' => now(),
                        'notes' => 'Payment proof submitted for verification.',
                        'metadata' => ['payment_proof' => $paymentProof],
                    ]);

                    $bookings->push($booking->load(['customer', 'room', 'roomRate', 'payments']));
                    $allocationIndex++;
                }
            }

            $firstBooking = $bookings->first();
            $this->notifications->notify(
                type: 'booking',
                title: 'New Booking Request',
                message: "New booking from {$customer->full_name} for {$bookings->count()} room unit(s).",
                data: [
                    'booking_id' => $firstBooking?->id,
                    'booking_number' => $firstBooking?->booking_number,
                    'customer_id' => $customer->id,
                    'check_in' => $checkIn->toDateString(),
                    'check_out' => $checkOut->toDateString(),
                    'guests' => $totalGuests,
                    'room_units' => $bookings->count(),
                ]
            );

            return $bookings;
        }, 3);
    }

    /**
     * Add a validated payment submission to an existing booking.
     */
    public function submitAdditionalPayment(
        Booking $booking,
        string $paymentMethod,
        ?float $requestedAmount,
        array $paymentProof
    ): Payment {
        return DB::transaction(function () use (
            $booking,
            $paymentMethod,
            $requestedAmount,
            $paymentProof
        ): Payment {
            $lockedBooking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

            if (! in_array($lockedBooking->status, ['pending', 'confirmed'], true)) {
                throw ValidationException::withMessages([
                    'booking' => 'This booking no longer accepts payments.',
                ]);
            }

            $submittedAmount = (float) $lockedBooking->payments()
                ->whereIn('status', ['pending', 'verified'])
                ->sum('amount_paid');

            $remainingBalance = max(0, (float) $lockedBooking->quoted_total - $submittedAmount);

            if ($remainingBalance <= 0) {
                throw ValidationException::withMessages([
                    'amount_paid' => 'This booking has no remaining balance.',
                ]);
            }

            $amount = $requestedAmount ?? $remainingBalance;

            if ($amount <= 0 || $amount > $remainingBalance) {
                throw ValidationException::withMessages([
                    'amount_paid' => "The payment must be greater than zero and must not exceed {$remainingBalance}.",
                ]);
            }

            $stage = $amount >= $remainingBalance
                ? ($submittedAmount > 0 ? 'final' : 'full')
                : 'partial';

            $payment = Payment::create([
                'booking_id' => $lockedBooking->id,
                'amount_paid' => $amount,
                'payment_stage' => $stage,
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'paid_at' => now(),
                'notes' => 'Additional payment proof submitted for verification.',
                'metadata' => ['payment_proof' => $paymentProof],
            ]);

            $this->notifications->notify(
                type: 'payment',
                title: 'New Payment Submission',
                message: "A payment was submitted for booking {$lockedBooking->booking_number}.",
                data: [
                    'booking_id' => $lockedBooking->id,
                    'payment_id' => $payment->id,
                    'amount_paid' => $amount,
                ]
            );

            return $payment;
        }, 3);
    }

    private function upsertCustomer(array $customerData): Customer
    {
        $email = mb_strtolower(trim((string) $customerData['email']));

        $customer = Customer::withTrashed()->firstOrNew(['email' => $email]);
        if ($customer->trashed()) {
            $customer->restore();
        }

        $customer->fill([
            'first_name' => trim((string) $customerData['first_name']),
            'last_name' => trim((string) $customerData['last_name']),
            'email' => $email,
            'phone_number' => $this->normalizePhone((string) $customerData['phone']),
            'data_consent' => (bool) ($customerData['data_consent'] ?? true),
            'consent_given_at' => ($customerData['data_consent'] ?? true)
                ? ($customer->consent_given_at ?? now())
                : null,
        ]);
        $customer->save();

        return $customer;
    }

    private function prepareItems(array $cart, Carbon $checkIn, Carbon $checkOut, int $nights): array
    {
        if ($cart === []) {
            throw ValidationException::withMessages([
                'cart' => 'Please select at least one room.',
            ]);
        }

        $prepared = [];
        $roomIds = collect($cart)->pluck('room_id')->map(fn ($id) => (int) $id)->sort()->values();

        // Lock room rows in a stable order to reduce deadlock risk.
        $rooms = Room::query()
            ->whereIn('id', $roomIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($cart as $item) {
            $roomId = (int) ($item['room_id'] ?? 0);
            $rateId = (int) ($item['room_rate_id'] ?? 0);
            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            /** @var Room|null $room */
            $room = $rooms->get($roomId);

            if (! $room || $room->status !== 'available') {
                throw ValidationException::withMessages([
                    'cart' => 'One of the selected rooms is no longer available.',
                ]);
            }

            $rate = RoomRate::query()
                ->whereKey($rateId)
                ->where('room_id', $roomId)
                ->active()
                ->effectiveOn($checkIn)
                ->first();

            if (! $rate) {
                throw ValidationException::withMessages([
                    'cart' => "The selected rate for {$room->name} is no longer active.",
                ]);
            }

            if ($nights < $rate->minimum_nights) {
                throw ValidationException::withMessages([
                    'check_out' => "The {$rate->name} rate requires at least {$rate->minimum_nights} night(s).",
                ]);
            }

            $availableUnits = $this->availability->availableUnits($room, $checkIn, $checkOut);
            if ($quantity > $availableUnits) {
                throw ValidationException::withMessages([
                    'cart' => "Only {$availableUnits} unit(s) of {$room->name} remain available.",
                ]);
            }

            $prepared[] = [
                'room' => $room,
                'rate' => $rate,
                'quantity' => $quantity,
            ];
        }

        return $prepared;
    }

    private function allocateGuests(array $preparedItems, int $totalGuests): array
    {
        $capacities = [];

        foreach ($preparedItems as $item) {
            for ($unit = 0; $unit < $item['quantity']; $unit++) {
                $capacities[] = $item['room']->capacity;
            }
        }

        $allocations = array_fill(0, count($capacities), 1);
        $remaining = $totalGuests - count($capacities);

        while ($remaining > 0) {
            $changed = false;

            foreach ($capacities as $index => $capacity) {
                if ($remaining <= 0) {
                    break;
                }

                if ($allocations[$index] < $capacity) {
                    $allocations[$index]++;
                    $remaining--;
                    $changed = true;
                }
            }

            if (! $changed) {
                break;
            }
        }

        return $allocations;
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '63')) {
            return '+63' . substr($digits, 2);
        }

        if (str_starts_with($digits, '9') && strlen($digits) === 10) {
            return '+63' . $digits;
        }

        return $digits;
    }
}
