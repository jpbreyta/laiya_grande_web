@extends('user.layouts.app')

@section('content')

    <style>
        :root {
            --primary-color: #1a4d6d;
            --secondary-color: #d4a853;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --light-bg: #f8f9fa;
            --border-color: #dee2e6;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }

        .reserve-container {
            background: linear-gradient(135deg, var(--light-bg) 0%, #e8f4f8 100%);
            min-height: 100vh;
            padding: 2rem 0;
            display: flex;
            align-items: center;
            /* vertically center the card */
        }

        /* Center the layout regardless of Bootstrap grid presence */
        .reserve-container .container {
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .reserve-container .row {
            display: flex;
            justify-content: center;
        }

        .reserve-container .col-lg-8 {
            width: 100%;
            max-width: 1100px;
        }

        .reserve-card {
            width: 100%;
            margin: 0 auto;
        }

        @media (min-width: 1536px) {
            .reserve-container .container {
                max-width: 1440px;
                /* better use of ultrawide screens */
            }

            .reserve-container .col-lg-8 {
                max-width: 1200px;
            }
        }

        .reserve-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .reserve-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .reserve-form {
            padding: 2.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 8px;
            padding: 0.9rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            background: #fff;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(26, 77, 109, 0.25);
            outline: none;
        }

        /* Responsive, sleek layout for the form */
        .reserve-form .row {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 1rem 1.25rem;
        }

        .reserve-form .col-md-6 {
            grid-column: span 12;
        }

        @media (min-width: 768px) {
            .reserve-form .col-md-6 {
                grid-column: span 6;
            }
        }

        /* Action buttons layout */
        .form-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .form-actions {
                grid-template-columns: 1fr 1fr;
            }
        }

        .btn-reserve {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #f39c12 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-reserve:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 168, 83, 0.4);
        }

        .btn-back {
            background: var(--border-color);
            color: var(--text-dark);
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .btn-back:hover {
            background: #adb5bd;
            color: var(--text-dark);
            text-decoration: none;
        }

        .reservation-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .info-value {
            color: var(--text-light);
        }

        .alert-info {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #1976d2;
        }

        /* Payment section */
        .payment-section {
            background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .payment-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .qr-wrap {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            align-items: center;
        }

        @media (min-width: 768px) {
            .qr-wrap {
                grid-template-columns: 260px 1fr;
            }
        }

        .qr-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .qr-card img {
            width: 220px;
            height: 220px;
            object-fit: contain;
            display: inline-block;
        }

        .payment-list {
            margin: 0;
            padding-left: 1.25rem;
            list-style: disc;
            color: #dc2626;
            /* red for emphasis */
        }

        /* Payment option buttons */
        .pay-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
            margin-top: .75rem;
        }

        .pay-btn {
            border: 2px solid var(--border-color);
            background: #fff;
            border-radius: 8px;
            padding: .75rem 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .2s ease;
            width: 100%;
        }

        .pay-btn:hover {
            border-color: var(--primary-color);
        }

        .pay-btn.active {
            background: linear-gradient(135deg, #1a4d6d 0%, #2563eb 100%);
            color: #fff;
            border-color: transparent;
        }

        /* Utility hidden class for conditional sections */
        .hidden {
            display: none !important;
        }
    </style>

    <div class="reserve-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="reserve-card">
                        <div class="reserve-header">
                            <h2 class="mb-2">
                                <i class="fas fa-calendar-check"></i>
                                Reserve Your Stay
                            </h2>
                            <p class="mb-0 opacity-90">Hold your preferred rooms with a reservation</p>
                        </div>

                        <div class="reserve-form">
                            <!-- Reservation Info -->
                            @if (session('cart') && count(session('cart')) > 0)
                                <div class="reservation-info">
                                    <h5 class="mb-3">
                                        <i class="fas fa-bed text-primary"></i>
                                        Selected Rooms
                                    </h5>
                                    @foreach (session('cart') as $item)
                                        <div class="info-item">
                                            <span class="info-label">{{ $item['room_name'] }}</span>
                                            <span class="info-value">
                                                Qty: {{ $item['quantity'] }} - PHP
                                                {{ number_format($item['room_price'] * $item['quantity'], 2) }}
                                            </span>
                                        </div>
                                    @endforeach
                                    @php
                                        $cartTotal = collect(session('cart'))->sum(function ($item) {
                                            return $item['room_price'] * $item['quantity'];
                                        });
                                    @endphp
                                    <div class="info-item">
                                        <span class="info-label">Total Amount</span>
                                        <span class="info-value">
                                            <strong>PHP {{ number_format($cartTotal, 2) }}</strong>
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <div class="alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Reservation Policy:</strong> A reservation holds your selected rooms for 24 hours.
                                You can confirm your booking within this period to secure your stay.
                            </div>

                            <form method="POST" action="{{ route('user.reservation.store') }}" id="reserveForm"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user"></i> First Name *
                                            </label>
                                            <input type="text" class="form-control" name="first_name" placeholder="Juan"
                                                autocomplete="given-name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user"></i> Last Name *
                                            </label>
                                            <input type="text" class="form-control" name="last_name"
                                                placeholder="Dela Cruz" autocomplete="family-name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i> Email Address *
                                    </label>
                                    <input type="email" class="form-control" name="email" placeholder="you@example.com"
                                        autocomplete="email" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-phone"></i> Phone Number *
                                    </label>
                                    <input type="tel" class="form-control" name="phone" placeholder="09xx xxx xxxx"
                                        autocomplete="tel" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-calendar"></i> Check-in Date *
                                            </label>
                                            <input type="date" class="form-control" name="check_in" autocomplete="off"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-calendar"></i> Check-out Date *
                                            </label>
                                            <input type="date" class="form-control" name="check_out" autocomplete="off"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-users"></i> Number of Guests
                                    </label>
                                    <input type="number" class="form-control" name="guests" min="1" value="2"
                                        placeholder="2" id="reserveGuestsInput">
                                    <small class="text-gray-500 text-sm mt-1 block" id="reserveCapacityHint">
                                        @php
                                            $cart = session('cart', []);
                                            $totalCapacity = 0;
                                            foreach ($cart as $item) {
                                                $room = \App\Models\Room::find($item['room_id']);
                                                if ($room) {
                                                    $totalCapacity += $room->capacity * $item['quantity'];
                                                }
                                            }
                                        @endphp
                                        @if ($totalCapacity > 0)
                                            Maximum capacity for selected rooms: {{ $totalCapacity }} guests
                                        @endif
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-comment"></i> Special Requests
                                    </label>
                                    <textarea class="form-control" name="special_requests" rows="3"
                                        placeholder="Any special requirements or requests..."></textarea>
                                </div>

                                <!-- Payment Section with QR Code (moved below Special Requests) -->
                                <div class="payment-section">
                                    <div class="payment-header">
                                        <i class="fas fa-receipt"></i>
                                        <span>Payment</span>
                                    </div>
                                    <div class="qr-wrap">
                                        <div class="qr-card">
                                            <img src="{{ asset('storage/qr_codes/resort_qr.png') }}"
                                                alt="Scan to Pay QR Code">
                                            <div class="text-sm mt-2" style="color: var(--text-light);">Scan QR to pay
                                            </div>
                                        </div>
                                        <div>
                                            <div class="info-item"
                                                style="border-bottom:none; padding:0; margin-bottom: .5rem;">
                                                <span class="info-label">Amount Due</span>
                                                <span class="info-value"><strong id="amountDue">PHP
                                                        {{ number_format(($cartTotal ?? 0) * 0.5, 2) }}</strong></span>
                                            </div>
                                            <input type="hidden" name="payment_multiplier" id="paymentMultiplier"
                                                value="0.5">
                                            <div class="form-group" style="margin-top:.75rem;">
                                                <label class="form-label">
                                                    <i class="fas fa-wallet"></i> Mode of Payment *
                                                </label>
                                                <select name="payment_method" class="form-control" required>
                                                    <option value="" disabled selected>Select a payment method
                                                    </option>
                                                    <option value="gcash">GCash</option>
                                                    <option value="paymaya">PayMaya</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                </select>
                                                <!-- Bank transfer specifics -->
                                                <div id="bankTransferFields" class="hidden" style="margin-top: .75rem;">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label"><i
                                                                        class="fas fa-university"></i> Bank Name *</label>
                                                                <input type="text" class="form-control"
                                                                    name="bank_name"
                                                                    placeholder="e.g., BDO / BPI / Metrobank">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label"><i class="fas fa-id-card"></i>
                                                                    Account Name *</label>
                                                                <input type="text" class="form-control"
                                                                    name="bank_account_name"
                                                                    placeholder="Laiya Grande Beach Resort">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label"><i class="fas fa-hashtag"></i>
                                                                    Account Number *</label>
                                                                <input type="text" class="form-control"
                                                                    name="bank_account_number"
                                                                    placeholder="0000-0000-0000">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label"><i class="fas fa-receipt"></i>
                                                                    Reference # *</label>
                                                                <input type="text" class="form-control"
                                                                    name="bank_reference"
                                                                    placeholder="Transaction reference number">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="payment-list">
                                                <li>Use your email as Reference/Note in your transfer.</li>
                                                <li>After payment, upload the proof below so we can verify.</li>
                                                <li>Reservations are held for <strong>24 hours</strong> pending payment.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-file-upload"></i> Upload Proof of Payment (optional)
                                    </label>
                                    <input type="file" class="form-control" name="payment"
                                        accept="image/*,application/pdf">
                                    <small style="color: var(--text-light);">Accepted: JPG, PNG, PDF. Max ~5MB.</small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row mt-4 form-actions">
                                    <div class="col-md-6 mb-3">
                                        <a href="{{ route('booking.index') }}" class="btn-back">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            Back to Rooms
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn-reserve">
                                            <i class="fas fa-calendar-check me-2"></i>
                                            Reserve Now
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Set minimum dates to today
                const today = new Date().toISOString().split('T')[0];
                document.querySelector('input[name="check_in"]').setAttribute('min', today);
                document.querySelector('input[name="check_out"]').setAttribute('min', today);

                // Payment option toggling and dynamic amount due
                const baseTotal = {{ $cartTotal ?? 0 }};
                const amountDueEl = document.getElementById('amountDue');
                const multiplierInput = document.getElementById('paymentMultiplier');
                // Only half payment now, ensure amount due shows 50%
                if (amountDueEl) {
                    const newAmount = (baseTotal * 0.5).toFixed(2);
                    amountDueEl.textContent = 'PHP ' + newAmount.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }

                // Show/hide bank transfer fields and toggle required attributes
                const paymentSelect = document.querySelector('select[name="payment_method"]');
                const bankWrap = document.getElementById('bankTransferFields');
                const bankInputs = bankWrap ? bankWrap.querySelectorAll('input') : [];

                function syncBankFields() {
                    const isBank = paymentSelect && paymentSelect.value === 'bank_transfer';
                    if (bankWrap) bankWrap.classList.toggle('hidden', !isBank);
                    bankInputs.forEach(inp => inp.toggleAttribute('required', isBank));
                }
                if (paymentSelect) {
                    paymentSelect.addEventListener('change', syncBankFields);
                    syncBankFields();
                }

                // Update checkout minimum when checkin changes
                document.querySelector('input[name="check_in"]').addEventListener('change', function() {
                    const checkinDate = this.value;
                    const checkoutInput = document.querySelector('input[name="check_out"]');
                    checkoutInput.setAttribute('min', checkinDate);

                    // If checkout is before checkin, clear it
                    if (checkoutInput.value && checkoutInput.value <= checkinDate) {
                        checkoutInput.value = '';
                    }
                });

                // Form submission with confirmation
                document.getElementById('reserveForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Validate guest count against capacity
                    const guestsInput = document.getElementById('reserveGuestsInput');
                    const maxCapacity = {{ $totalCapacity ?? 0 }};
                    if (guestsInput && maxCapacity > 0 && parseInt(guestsInput.value) > maxCapacity) {
                        Swal.fire({
                            title: 'Capacity Exceeded',
                            text: `Number of guests (${guestsInput.value}) exceeds room capacity (${maxCapacity}). Please reduce the number of guests or select rooms with higher capacity.`,
                            icon: 'warning',
                            confirmButtonColor: '#d4a853'
                        });
                        guestsInput.focus();
                        return;
                    }

                    Swal.fire({
                        title: 'Confirm Reservation',
                        text: 'Are you sure you want to reserve these rooms?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#d4a853',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Reserve',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Creating your reservation',
                                icon: 'info',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Submit form
                            fetch(this.action, {
                                    method: 'POST',
                                    body: new FormData(this),
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            title: 'Reservation Confirmed!',
                                            text: 'Your reservation has been successfully created. You will receive a confirmation email shortly.',
                                            icon: 'success',
                                            confirmButtonColor: '#d4a853'
                                        }).then(() => {
                                            window.location.href =
                                                '{{ route('user.reservation.index') }}';
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: data.message ||
                                                'Something went wrong. Please try again.',
                                            icon: 'error',
                                            confirmButtonColor: '#dc3545'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Network error. Please check your connection and try again.',
                                        icon: 'error',
                                        confirmButtonColor: '#dc3545'
                                    });
                                });
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
