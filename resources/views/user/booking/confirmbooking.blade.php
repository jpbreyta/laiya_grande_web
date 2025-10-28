@extends('user.layouts.app')

@section('content')
    <section class="py-5" style="background-color: var(--light-bg);">
        <div class="container">
            <div class="row g-4">
                <!-- Main Content (Left) -->
                <div class="col-lg-8">
                    <div class="card"
                        style="border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <div class="card-body" style="padding: 2rem;">

                            <!-- Progress Indicator -->
                            <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                                <div
                                    style="width: 40px; height: 40px; background-color: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    1</div>
                                <div style="flex: 1; height: 2px; background-color: var(--primary-color); margin: 0 1rem;">
                                </div>
                                <div
                                    style="width: 40px; height: 40px; background-color: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    2</div>
                            </div>

                            <h2 style="margin-bottom: 0.5rem;">Step 2: Review & Confirm</h2>
                            <p style="color: var(--text-light); margin-bottom: 2rem;">Please review your booking details
                                before confirming</p>

                            <!-- Guest Information -->
                            <div
                                style="background-color: white; padding: 1.5rem; border-radius: 6px; border: 1px solid var(--border-color); margin-bottom: 1.5rem;">
                                <h6 style="margin-bottom: 1rem; color: var(--text-dark);">Guest Information</h6>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">Name
                                        </p>
                                        <p style="font-weight: 600;">{{ $request->first_name }} {{ $request->last_name }}
                                        </p>
                                    </div>
                                    <div>
                                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">
                                            Email</p>
                                        <p style="font-weight: 600;">{{ $request->email }}</p>
                                    </div>
                                    <div>
                                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">
                                            Phone</p>
                                        <p style="font-weight: 600;">{{ $request->phone }}</p>
                                    </div>
                                    <div>
                                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">
                                            Guests</p>
                                        <p style="font-weight: 600;">{{ $request->guests }} Guest(s)</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Details -->
                            <div
                                style="background-color: white; padding: 1.5rem; border-radius: 6px; border: 1px solid var(--border-color); margin-bottom: 1.5rem;">
                                <h6 style="margin-bottom: 1rem; color: var(--text-dark);">Booking Details</h6>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">
                                            Check-in</p>
                                        <p style="font-weight: 600;">
                                            {{ \Carbon\Carbon::parse($request->check_in)->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">
                                            Check-out</p>
                                        <p style="font-weight: 600;">
                                            {{ \Carbon\Carbon::parse($request->check_out)->format('M d, Y') }}</p>
                                    </div>
                                    <div>
                                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">
                                            Duration</p>
                                        <p style="font-weight: 600;">{{ $nights }} Night(s)</p>
                                    </div>
                                </div>
                                @if ($request->special_requests)
                                    <div
                                        style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">
                                            Special Requests</p>
                                        <p style="font-weight: 600;">{{ $request->special_requests }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Price Breakdown -->
                            <div
                                style="background-color: var(--light-bg); padding: 1.5rem; border-radius: 6px; margin-bottom: 1.5rem;">
                                <h6 style="margin-bottom: 1rem; color: var(--text-dark);">Price Breakdown</h6>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="color: var(--text-light);">Subtotal</span>
                                    <span style="font-weight: 600;">PHP {{ number_format($cart_total, 2) }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                    <span style="color: var(--text-light);">Taxes & Fees (12%)</span>
                                    <span style="font-weight: 600;">PHP {{ number_format($tax, 2) }}</span>
                                </div>
                                <div
                                    style="border-top: 1px solid var(--border-color); padding-top: 1rem; display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700; color: var(--secondary-color);">
                                    <span>Total Amount</span>
                                    <span>PHP {{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <form method="POST" action="{{ route('booking.process') }}" id="processBookingForm">
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

                                <div
                                    style="background-color: #fff3cd; border: 1px solid #ffc107; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
                                    <div style="display: flex; gap: 1rem;">
                                        <input type="checkbox" id="termsCheckbox" name="agree_terms" required
                                            style="margin-top: 0.25rem;">
                                        <label for="termsCheckbox" style="color: var(--text-dark); font-size: 0.95rem;">
                                            I agree to the <a href="#"
                                                style="color: var(--secondary-color); font-weight: 600;">Terms and
                                                Conditions</a> and <a href="#"
                                                style="color: var(--secondary-color); font-weight: 600;">Cancellation
                                                Policy</a>
                                        </label>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div style="display: flex; gap: 1rem;">
                                    <button type="button" class="btn" onclick="history.back()"
                                        style="background-color: var(--border-color); color: var(--text-dark); padding: 0.75rem 1.5rem; font-weight: 600; border-radius: 6px; flex: 1;">
                                        Back
                                    </button>
                                    <button type="button" class="btn" onclick="completeBooking()"
                                        style="background-color: var(--primary-color); color: white; padding: 0.75rem 1.5rem; font-weight: 600; border-radius: 6px; flex: 1;">
                                        Complete Booking
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Read-only Cart Sidebar (Right) -->
                <div class="col-lg-4">
                    <div
                        style="background-color: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); height: fit-content; position: sticky; top: 100px;">
                        <h5 style="margin-bottom: 1.5rem; color: var(--text-dark); font-weight: 700;">
                            <i class="fas fa-bed"></i> Booking Summary
                        </h5>

                        <!-- Cart Items (Read-only) -->
                        <div id="cartItems" style="max-height: 400px; overflow-y: auto; margin-bottom: 1.5rem;">
                            @if (empty($cart))
                                <p style="color: var(--text-light); text-align: center; padding: 2rem 0;">
                                    <i class="fas fa-inbox"
                                        style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                    No items selected
                                </p>
                            @else
                                @foreach ($cart as $item)
                                    <div
                                        style="padding: 1rem; border: 1px solid var(--border-color); border-radius: 6px; margin-bottom: 1rem; background-color: var(--light-bg);">
                                        <div
                                            style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                            <h6 style="margin: 0; color: var(--text-dark); font-size: 0.95rem;">
                                                {{ $item['room_name'] }}</h6>
                                        </div>
                                        <div
                                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                            <span style="color: var(--text-light); font-size: 0.85rem;">Qty:
                                                {{ $item['quantity'] }}</span>
                                            <span style="font-weight: 600; color: var(--secondary-color);">PHP
                                                {{ number_format($item['room_price'] * $item['quantity'], 2) }}</span>
                                        </div>
                                        <p style="margin: 0; color: var(--text-light); font-size: 0.8rem;">PHP
                                            {{ number_format($item['room_price'], 2) }}/night</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Cart Summary (Read-only) -->
                        @if (!empty($cart))
                            <div style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                    <span style="color: var(--text-light);">Subtotal</span>
                                    <span style="font-weight: 600;">PHP {{ number_format($cart_total, 2) }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                                    <span style="color: var(--text-light);">Taxes & Fees (12%)</span>
                                    <span style="font-weight: 600;">PHP {{ number_format($tax, 2) }}</span>
                                </div>
                                <div
                                    style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 700; color: var(--secondary-color); padding-top: 1rem; border-top: 1px solid var(--border-color);">
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

    <!-- Added completion popup modal -->
    <div class="modal fade" id="completionModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border: none; border-radius: 12px;">
                <div class="modal-body" style="padding: 2rem; text-align: center;">
                    <div style="font-size: 3rem; color: #27ae60; margin-bottom: 1rem;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4 style="color: var(--text-dark); margin-bottom: 1rem;">Booking Complete!</h4>
                    <p style="color: var(--text-light); margin-bottom: 1.5rem;">
                        Your booking is pending admin approval. You will receive a confirmation email shortly.
                    </p>
                    <div
                        style="background-color: var(--light-bg); padding: 1.5rem; border-radius: 6px; margin-bottom: 1.5rem;">
                        <p style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-qrcode" style="color: var(--secondary-color); margin-right: 0.5rem;"></i>
                            <strong>QR Code & Check-in Details</strong>
                        </p>
                        <p style="color: var(--text-light); font-size: 0.85rem; margin: 0;">
                            A unique QR code will be sent to your email. Please present this code at check-in for quick
                            resort access.
                        </p>
                    </div>
                    <div
                        style="background-color: #e8f4f8; padding: 1.5rem; border-radius: 6px; margin-bottom: 1.5rem; border-left: 4px solid var(--primary-color);">
                        <p style="color: var(--text-dark); font-size: 0.9rem; margin: 0;">
                            <i class="fas fa-envelope" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                            Booking details and QR code will be sent to <strong>{{ $request->email }}</strong>
                        </p>
                    </div>
                    <button type="button" class="btn w-100" onclick="window.location.href='{{ route('home') }}'"
                        style="background-color: var(--primary-color); color: white; padding: 0.75rem; font-weight: 600; border-radius: 6px;">
                        Return to Home
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function completeBooking() {
            const termsCheckbox = document.getElementById('termsCheckbox');
            if (!termsCheckbox.checked) {
                alert('Please agree to the terms and conditions');
                return;
            }

            // Submit the form via AJAX to get JSON response
            const formData = new FormData(document.getElementById('processBookingForm'));

            fetch('{{ route('booking.process') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show completion popup
                        const modal = new bootstrap.Modal(document.getElementById('completionModal'));
                        modal.show();
                    } else {
                        alert('Error: ' + (data.message || 'Something went wrong'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your booking');
                });
        }
    </script>
@endsection
