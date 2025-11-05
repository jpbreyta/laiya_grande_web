@extends('admin.layouts.app')

@section('content')
<section class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Booking Details</h1>

            <div class="grid grid-cols-2 gap-4 text-gray-700">
                <div><strong>Guest:</strong> {{ $booking->firstname }} {{ $booking->lastname }}</div>
                <div><strong>Email:</strong> {{ $booking->email }}</div>
                <div><strong>Phone:</strong> {{ $booking->phone_number }}</div>
                <div><strong>Guests:</strong> {{ $booking->number_of_guests }}</div>
                <div><strong>Room:</strong> {{ $booking->room->name ?? 'N/A' }}</div>
                <div><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</div>
                <div><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</div>
                <div><strong>Total Price:</strong> ₱{{ number_format($booking->total_price, 2) }}</div>
                <div><strong>Payment Method:</strong> {{ ucfirst($booking->payment_method ?? 'N/A') }}</div>
                <div><strong>Status:</strong>
                    <span id="statusBadge" class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            @if($booking->payment && file_exists(storage_path('app/public/' . $booking->payment)))
                <div class="mt-6" id="paymentProofSection">
                    <h3 class="font-semibold mb-2 text-gray-800">Payment Proof:</h3>
                    <img src="{{ asset('storage/' . $booking->payment) }}" alt="Payment Proof" class="rounded-lg w-64 border">
                </div>
            @elseif($booking->payment)
                <div class="mt-6 text-red-600" id="paymentProofSection">
                    Payment proof uploaded but file not found!
                </div>
            @endif

            <div class="flex justify-end gap-4 mt-8" id="actionButtons">
                @if($booking->status === 'pending')
                    <form id="approveForm" action="{{ route('admin.booking.approve', $booking->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" id="approveBtn" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">Confirm</button>
                    </form>

                    <form id="rejectForm" action="{{ route('admin.booking.reject', $booking->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" id="rejectBtn" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg">Cancel</button>
                    </form>
                @endif

                <a href="{{ route('admin.booking.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg">Back</a>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const updateBookingUI = (status, message) => {
    const statusBadge = document.getElementById('statusBadge');
    if (statusBadge) {
        let colorClasses = '';
        if (status === 'confirmed') colorClasses = 'bg-green-100 text-green-800';
        else if (status === 'pending') colorClasses = 'bg-yellow-100 text-yellow-800';
        else colorClasses = 'bg-red-100 text-red-800';

        statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        statusBadge.className = `px-3 py-1 rounded-full text-xs font-semibold ${colorClasses}`;
    }

    // Remove action buttons
    const actionButtons = document.getElementById('actionButtons');
    if (status !== 'pending' && actionButtons) {
        actionButtons.querySelector('#approveBtn')?.remove();
        actionButtons.querySelector('#rejectBtn')?.remove();
    }

    Swal.fire({
        icon: status === 'confirmed' ? 'success' : 'warning',
        title: status === 'confirmed' ? 'Booking Confirmed!' : 'Booking Cancelled!',
        text: message,
        confirmButtonColor: '#16a34a'
    });
};

document.getElementById('approveBtn')?.addEventListener('click', function () {
    Swal.fire({
        title: 'Confirm this booking?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, confirm it!'
    }).then(result => {
        if (!result.isConfirmed) return;

        const form = document.getElementById('approveForm');
        const formData = new FormData(form);

        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            Swal.close();
            if (data.success) updateBookingUI('confirmed', data.message || 'The booking has been successfully confirmed.');
            else Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong!', confirmButtonColor: '#d33' });
        })
        .catch(() => {
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to process the request.', confirmButtonColor: '#d33' });
        });
    });
});

document.getElementById('rejectBtn')?.addEventListener('click', function () {
    Swal.fire({
        title: 'Cancel this booking?',
        text: 'This will mark the booking as cancelled.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!'
    }).then(result => {
        if (!result.isConfirmed) return;

        const form = document.getElementById('rejectForm');
        const formData = new FormData(form);

        Swal.fire({ title: 'Processing...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            Swal.close();
            if (data.success) updateBookingUI('cancelled', data.message || 'The booking has been cancelled.');
            else Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong!', confirmButtonColor: '#d33' });
        })
        .catch(() => {
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to process the request.', confirmButtonColor: '#d33' });
        });
    });
});
</script>
@endsection
