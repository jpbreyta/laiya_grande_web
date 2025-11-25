@extends('admin.layouts.app')

@section('content')
<section class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <div class="relative h-40 md:h-48 rounded-t-2xl overflow-hidden shadow-xl mb-6">
            <div class="w-full h-full bg-gradient-to-br from-[#5f9ea0] to-[#99e6b3]"></div>
            <div class="absolute top-0 left-0 p-6 md:p-8 z-10">
                <div class="inline-block bg-white/10 backdrop-blur-md px-5 py-2.5 rounded-xl border border-white/20 shadow-lg">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-white tracking-tight drop-shadow-lg">
                        Check-in Details
                    </h1>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 z-10">
                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            {{ strtoupper(substr($guestStay->guest_name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xl md:text-2xl font-bold text-white">{{ $guestStay->guest_name }}</p>
                            <p class="text-sm text-gray-200">{{ $guestStay->room->name ?? 'Room N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold shadow-lg
                            {{ $guestStay->status === 'checked-in' ? 'bg-gradient-to-r from-green-400 to-emerald-400 text-white border border-green-300' : 'bg-gradient-to-r from-blue-400 to-indigo-400 text-white border border-blue-300' }}">
                            <span class="w-2 h-2 rounded-full mr-2 bg-white"></span>
                            {{ ucfirst($guestStay->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                            <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Guest Name</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $guestStay->guest_name }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Email</div>
                            <div class="text-sm font-medium text-gray-900">{{ $guestStay->booking->email ?? 'N/A' }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Phone Number</div>
                            <div class="text-sm font-medium text-gray-900">{{ $guestStay->booking->phone_number ?? 'N/A' }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Number of Guests</div>
                            <div class="text-sm font-medium text-gray-900">{{ $guestStay->booking->number_of_guests ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border border-purple-100">
                            <div class="text-xs font-bold text-purple-600 uppercase tracking-wider mb-1">Room</div>
                            <div class="text-lg font-semibold text-gray-900">{{ $guestStay->room->name ?? 'N/A' }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Check-in Time</div>
                            <div class="text-sm font-medium text-gray-900">{{ $guestStay->check_in_time?->format('M d, Y H:i') ?? 'N/A' }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Check-out Time</div>
                            <div class="text-sm font-medium text-gray-900">{{ $guestStay->check_out_time?->format('M d, Y H:i') ?? '-' }}</div>
                        </div>

                        <div class="p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200">
                            <div class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Status</div>
                            <div class="text-sm font-medium text-gray-900">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold shadow-sm
                                    {{ $guestStay->status === 'checked-in' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200' : 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $guestStay->status === 'checked-in' ? 'bg-green-500' : 'bg-blue-500' }}"></span>
                                    {{ ucfirst($guestStay->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-3 pt-6 border-t border-gray-200" id="actionButtons">
                    <a href="{{ route('admin.guest-stays.index') }}" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        Back to List
                    </a>
                    @if ($guestStay->status === 'checked-in')
                        <button type="button" id="checkoutBtn" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            Check Out
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    const checkoutBtn = document.getElementById('checkoutBtn');
    checkoutBtn?.addEventListener('click', function () {
        Swal.fire({
            title: 'Check out this guest?',
            text: 'This will mark the guest as checked out.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Check out'
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch(`{{ url('admin/guest-stays') }}/${guestStayId}/checkout`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Checked out!',
                        text: data.message,
                        timer: 1400,
                        showConfirmButton: false
                    }).then(() => location.href = '{{ route("admin.guest-stays.index") }}');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while processing checkout.', 'error');
            });
        });
    });
})();
</script>
<script>
    const guestStayId = {{ $guestStay->id }};
</script>
@endsection
