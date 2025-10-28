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
            min-height: 80vh;
            padding: 2rem 0;
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
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(26, 77, 109, 0.25);
            outline: none;
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

                            <form method="POST" action="{{ route('booking.confirm') }}" id="reserveForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user"></i> First Name *
                                            </label>
                                            <input type="text" class="form-control" name="first_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user"></i> Last Name *
                                            </label>
                                            <input type="text" class="form-control" name="last_name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope"></i> Email Address *
                                    </label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-phone"></i> Phone Number *
                                    </label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-calendar"></i> Check-in Date *
                                            </label>
                                            <input type="date" class="form-control" name="check_in" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-calendar"></i> Check-out Date *
                                            </label>
                                            <input type="date" class="form-control" name="check_out" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-users"></i> Number of Guests
                                    </label>
                                    <input type="number" class="form-control" name="guests" min="1" value="2">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-comment"></i> Special Requests
                                    </label>
                                    <textarea class="form-control" name="special_requests" rows="3"
                                        placeholder="Any special requirements or requests..."></textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row mt-4">
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
                                                '{{ route('booking.index') }}';
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
