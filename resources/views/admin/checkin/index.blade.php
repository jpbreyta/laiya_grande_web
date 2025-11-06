@extends('admin.layouts.app')

@section('content')
    <section class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Guest Check-in</h1>
                <p class="text-gray-600">Monitor guest accommodation time and manage check-in/out</p>
            </div>

            <!-- Guest Information Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Guest Details -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Guest Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Name</label>
                                    <p class="text-gray-900">{{ $booking->firstname }} {{ $booking->lastname }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Reservation Number</label>
                                    <p class="text-gray-900">{{ $booking->reservation_number }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Room</label>
                                    <p class="text-gray-900">{{ $booking->room->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Check-in Date</label>
                                    <p class="text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Check-out Date</label>
                                    <p class="text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status and Timer -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Timer</h3>
                            <div class="space-y-4">
                                <!-- Check-in Status -->
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Check-in Status</label>
                                    <div class="mt-1">
                                        @if ($booking->actual_check_in_time)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Checked In
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Checked in at:
                                                {{ \Carbon\Carbon::parse($booking->actual_check_in_time)->format('M d, Y H:i') }}
                                            </p>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Not Checked In
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Accommodation Timer -->
                                <div id="timer-section" class="{{ $booking->actual_check_in_time ? '' : 'hidden' }}">
                                    <label class="text-sm font-medium text-gray-500">Time Accommodated</label>
                                    <div class="mt-1">
                                        <div class="text-2xl font-mono font-bold text-blue-600" id="timer">00:00:00
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Hours:Minutes:Seconds</p>
                                    </div>
                                </div>

                                <!-- Check-out Status -->
                                <div id="checkout-section" class="{{ $booking->actual_check_out_time ? '' : 'hidden' }}">
                                    <label class="text-sm font-medium text-gray-500">Check-out Status</label>
                                    <div class="mt-1">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Checked Out
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Checked out at:
                                            {{ $booking->actual_check_out_time ? \Carbon\Carbon::parse($booking->actual_check_out_time)->format('M d, Y H:i') : '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if (!$booking->actual_check_in_time)
                        <button id="checkin-btn"
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Check In Guest
                        </button>
                    @elseif(!$booking->actual_check_out_time)
                        <button id="checkout-btn"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m-4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            Check Out Guest
                        </button>
                    @endif

                    <a href="{{ route('admin.qr.scanner') }}"
                        class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        </svg>
                        Back to Scanner
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        let timerInterval;
        let startTime = {{ $booking->actual_check_in_time ? strtotime($booking->actual_check_in_time) * 1000 : 'null' }};

        function updateTimer() {
            if (!startTime) return;

            const now = new Date().getTime();
            const elapsed = now - startTime;

            const hours = Math.floor(elapsed / (1000 * 60 * 60));
            const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);

            document.getElementById('timer').textContent =
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        }

        // Start timer if checked in
        if (startTime) {
            updateTimer();
            timerInterval = setInterval(updateTimer, 1000);
        }

        // Check-in button handler
        document.getElementById('checkin-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to check in this guest?')) {
                fetch(`{{ route('admin.checkin.process', $booking->id) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            action: 'checkin'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Guest checked in successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while checking in the guest.');
                    });
            }
        });

        // Check-out button handler
        document.getElementById('checkout-btn')?.addEventListener('click', function() {
            if (confirm('Are you sure you want to check out this guest?')) {
                fetch(`{{ route('admin.checkin.process', $booking->id) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            action: 'checkout'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Guest checked out successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while checking out the guest.');
                    });
            }
        });
    </script>
@endsection
