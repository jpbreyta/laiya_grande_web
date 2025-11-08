@extends('user.layouts.app')

@section('content')
    <section class="py-10 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-6">

                <!-- Main Content (Left) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-md p-8">

                        <!-- Progress Indicator -->
                        <div class="flex items-center mb-8">
                            <div
                                class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center font-bold rounded-full">
                                1</div>
                            <div class="flex-1 h-0.5 bg-blue-600 mx-4"></div>
                            <div
                                class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center font-bold rounded-full">
                                2</div>
                        </div>

                        <h2 class="text-2xl font-bold mb-2">Step 2: Review & Confirm</h2>
                        <p class="text-gray-500 mb-6">Please review your booking details before confirming</p>

                        <!-- Guest Information -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                            <h6 class="font-semibold text-gray-800 mb-4">Guest Information</h6>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 mb-1">Name</p>
                                    <p class="font-semibold">{{ $request->first_name }} {{ $request->last_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1">Email</p>
                                    <p class="font-semibold">{{ $request->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1">Phone</p>
                                    <p class="font-semibold">{{ $request->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1">Guests</p>
                                    <p class="font-semibold">{{ $request->guests }} Guest(s)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                            <h6 class="font-semibold text-gray-800 mb-4">Booking Details</h6>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 mb-1">Check-in</p>
                                    <p class="font-semibold">
                                        {{ \Carbon\Carbon::parse($request->check_in)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1">Check-out</p>
                                    <p class="font-semibold">
                                        {{ \Carbon\Carbon::parse($request->check_out)->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 mb-1">Duration</p>
                                    <p class="font-semibold">{{ $nights }} Night(s)</p>
                                </div>
                            </div>
                            @if ($request->special_requests)
                                <div class="border-t border-gray-200 mt-4 pt-4">
                                    <p class="text-gray-500 text-sm mb-1">Special Requests</p>
                                    <p class="font-semibold">{{ $request->special_requests }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Price Breakdown -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <h6 class="font-semibold text-gray-800 mb-4">Price Breakdown</h6>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="font-semibold">PHP {{ number_format($cart_total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mb-4">
                                <span class="text-gray-500">Taxes & Fees (12%)</span>
                                <span class="font-semibold">PHP {{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-4 font-bold text-lg text-blue-700">
                                <span>Total Amount</span>
                                <span>PHP {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <!-- Terms & Payment Upload -->
                        <form method="POST" action="{{ route('booking.confirm') }}" id="processBookingForm"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="first_name" value="{{ $request->first_name }}">
                            <input type="hidden" name="last_name" value="{{ $request->last_name }}">
                            <input type="hidden" name="email" value="{{ $request->email }}">
                            <input type="hidden" name="phone" value="{{ $request->phone }}">
                            <input type="hidden" name="check_in" value="{{ $request->check_in }}">
                            <input type="hidden" name="check_out" value="{{ $request->check_out }}">
                            <input type="hidden" name="guests" value="{{ $request->guests }}">
                            <input type="hidden" name="special_requests" value="{{ $request->special_requests }}">
                            <input type="hidden" name="total_price" value="{{ $total }}">
                            <input type="hidden" name="payment_method" value="{{ $request->payment_method }}">
                            @if ($request->hasFile('payment_proof'))
                                <input type="hidden" name="payment_proof_temp"
                                    value="{{ $request->file('payment_proof')->store('temp') }}">
                            @endif

                            @if (!empty($paymentProofUrl))
                                <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                                    <h6 class="font-semibold text-gray-800 mb-4">Uploaded Payment Proof</h6>
                                    <div class="flex justify-center">
                                        <img src="{{ $paymentProofUrl }}" alt="Payment Proof"
                                            class="rounded-lg shadow-md max-h-96 object-contain">
                                    </div>
                                    <input type="hidden" name="payment_proof_temp" value="{{ $paymentProofPath }}">
                                </div>
                            @endif

                            <!-- Terms & Conditions -->
                            <div class="bg-yellow-50 border border-yellow-400 rounded-lg p-4 mb-6 flex gap-3">
                                <input type="checkbox" id="termsCheckbox" name="agree_terms" class="mt-1" required>
                                <label for="termsCheckbox" class="text-gray-700 text-sm">
                                    I agree to the
                                    <a href="#" class="text-blue-700 font-semibold">Terms and Conditions</a> and
                                    <a href="#" class="text-blue-700 font-semibold">Cancellation Policy</a>
                                </label>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-4">
                                <button type="button" onclick="history.back()"
                                    class="flex-1 bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg hover:bg-gray-400 transition">
                                    Back
                                </button>
                                <button type="button" id="completeBookingBtn" onclick="completeBooking()"
                                    class="flex-1 bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition">
                                    Complete Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cart Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-xl shadow-md sticky top-24">
                        <h5 class="font-bold text-lg text-gray-800 mb-6 flex items-center gap-2">
                            <i class="fas fa-bed text-blue-600"></i> Booking Summary
                        </h5>

                        <!-- Cart Items -->
                        <div class="max-h-96 overflow-y-auto mb-6">
                            @if (empty($cart))
                                <p class="text-gray-500 text-center py-8">
                                    <i class="fas fa-inbox text-2xl mb-2 block"></i>
                                    No items selected
                                </p>
                            @else
                                @foreach ($cart as $item)
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                        <h6 class="font-semibold text-gray-800 mb-2">{{ $item['room_name'] }}</h6>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-500">Qty: {{ $item['quantity'] }}</span>
                                            <span class="font-semibold text-blue-700">PHP
                                                {{ number_format($item['room_price'] * $item['quantity'], 2) }}</span>
                                        </div>
                                        <p class="text-gray-500 text-xs">PHP
                                            {{ number_format($item['room_price'], 2) }}/night</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Totals -->
                        @if (!empty($cart))
                            <div class="border-t border-gray-200 pt-4 text-sm">
                                <div class="flex justify-between mb-1 text-gray-600">
                                    <span>Subtotal</span>
                                    <span class="font-semibold">PHP {{ number_format($cart_total, 2) }}</span>
                                </div>
                                <div class="flex justify-between mb-2 text-gray-600">
                                    <span>Taxes & Fees (12%)</span>
                                    <span class="font-semibold">PHP {{ number_format($tax, 2) }}</span>
                                </div>
                                <div
                                    class="flex justify-between font-bold text-lg text-blue-700 border-t border-gray-200 pt-3">
                                    <span>Total</span>
                                    <span>PHP {{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function completeBooking() {
            const termsCheckbox = document.getElementById('termsCheckbox');
            const submitBtn = document.getElementById('completeBookingBtn');

            if (!termsCheckbox.checked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Please agree to the terms and conditions first!',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            // Disable button and show processing
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

            // Show processing SweetAlert
            Swal.fire({
                title: 'Processing Booking...',
                text: 'Please wait while we process your booking.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(document.getElementById('processBookingForm'));

            fetch('{{ route('booking.confirm') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Close processing alert
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Booking Complete!',
                            html: `
                    <p>Your booking is pending admin approval.<br>
                    You will receive a confirmation email shortly.</p>
                    <hr class="my-3">
                    <p class="text-gray-600 text-sm">
                        <i class="fas fa-envelope text-blue-600 mr-1"></i>
                        Booking details and QR code will be sent to <b>{{ $request->email }}</b>
                    </p>
                `,
                            confirmButtonText: 'Return to Home',
                            confirmButtonColor: '#2563eb'
                        }).then(() => {
                            window.location.href = '{{ route('home') }}';
                        });
                    } else {
                        // Re-enable button on error
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Complete Booking';

                        Swal.fire({
                            icon: 'error',
                            title: 'Booking Failed!',
                            text: data.message || 'Something went wrong.',
                            confirmButtonColor: '#2563eb'
                        });
                    }
                })
                .catch(error => {
                    // Close processing alert and re-enable button
                    Swal.close();
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Complete Booking';

                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your booking.',
                        confirmButtonColor: '#2563eb'
                    });
                });
        }
    </script>
@endsection
