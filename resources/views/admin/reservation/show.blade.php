@extends('admin.layouts.app')

@section('content')
<section class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Reservation Details #{{ $reservation->id }}</h1>

            <div class="grid grid-cols-2 gap-4 text-gray-700">
                <div><strong>Guest:</strong> {{ $reservation->firstname }} {{ $reservation->lastname }}</div>
                <div><strong>Email:</strong> {{ $reservation->email }}</div>
                <div><strong>Phone:</strong> {{ $reservation->phone_number }}</div>
                <div><strong>Guests:</strong> {{ $reservation->number_of_guests }}</div>
                <div><strong>Room:</strong> {{ $reservation->room->name ?? 'N/A' }}</div>
                <div><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($reservation->check_in)->format('M d, Y') }}</div>
                <div><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($reservation->check_out)->format('M d, Y') }}</div>
                <div><strong>Total Price:</strong> ₱{{ number_format($reservation->total_price, 2) }}</div>
                <div><strong>Payment Method:</strong> {{ ucfirst($reservation->payment_method ?? 'N/A') }}</div>
                <div><strong>Status:</strong>
                    <span id="statusBadge" class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($reservation->status === 'confirmed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($reservation->status) }}
                    </span>
                </div>
            </div>

            @if($reservation->payment && file_exists(storage_path('app/public/' . $reservation->payment)))
                <div class="mt-6">
                    <h3 class="font-semibold mb-2 text-gray-800">Payment Proof:</h3>
                    <img src="{{ asset('storage/' . $reservation->payment) }}" alt="Payment Proof" class="rounded-lg w-64 border">
                </div>
            @elseif($reservation->payment)
                <div class="mt-6 text-red-600">
                    Payment proof uploaded but file not found!
                </div>
            @endif

            <div class="flex justify-end gap-4 mt-8" id="actionButtons">
                @if($reservation->status === 'pending')
                    <form id="approveForm" action="{{ route('admin.reservation.approve', $reservation->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" id="approveBtn" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">Confirm</button>
                    </form>

                    <form id="cancelForm" action="{{ route('admin.reservation.cancel', $reservation->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" id="cancelBtn" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg">Cancel</button>
                    </form>
                @endif

                <a href="{{ route('admin.reservation.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg">Back</a>
            </div>
        </div>
    </div>
</section>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const updateReservationUI = (status, message) => {
    const statusBadge = document.getElementById('statusBadge');
    if (statusBadge) {
        let colorClasses = '';
        if (status === 'confirmed') colorClasses = 'bg-green-100 text-green-800';
        else if (status === 'pending') colorClasses = 'bg-yellow-100 text-yellow-800';
        else colorClasses = 'bg-red-100 text-red-800';

        statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        statusBadge.className = `px-3 py-1 rounded-full text-xs font-semibold ${colorClasses}`;
    }

    const actionButtons = document.getElementById('actionButtons');
    if (status !== 'pending' && actionButtons) {
        actionButtons.querySelector('#approveBtn')?.remove();
        actionButtons.querySelector('#cancelBtn')?.remove();
    }

    Swal.fire({
        icon: status === 'confirmed' ? 'success' : 'warning',
        title: status === 'confirmed' ? 'Reservation Confirmed!' : 'Reservation Cancelled!',
        text: message,
        confirmButtonColor: '#16a34a'
    });
};

document.getElementById('approveBtn')?.addEventListener('click', function () {
    Swal.fire({
        title: 'Confirm this reservation?',
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
            if (data.success) updateReservationUI('confirmed', data.message || 'The reservation has been confirmed.');
            else Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong!', confirmButtonColor: '#d33' });
        })
        .catch(() => {
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to process the request.', confirmButtonColor: '#d33' });
        });
    });
});

document.getElementById('cancelBtn')?.addEventListener('click', function () {
    Swal.fire({
        title: 'Cancel this reservation?',
        text: 'This will mark the reservation as cancelled.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!'
    }).then(result => {
        if (!result.isConfirmed) return;

        const form = document.getElementById('cancelForm');
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
            if (data.success) updateReservationUI('cancelled', data.message || 'The reservation has been cancelled.');
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
