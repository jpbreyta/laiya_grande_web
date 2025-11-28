@extends('user.layouts.app')

@section('content')
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Please check your inputs',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#f59e0b'
            })
        </script>
    @endif

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-sky-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-100">
                    <h1 class="text-3xl font-extrabold mb-6 text-gray-900 tracking-tight">
                        Verify Your Information
                    </h1>

                    <form id="verifyInfoForm" method="POST" action="{{ route('search.validateContactInformation') }}"
                        class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg shadow-sm border border-gray-200 space-y-6">
                        @csrf

                        <!-- Reservation Code -->
                        <div>
                            <label for="reservation_code" class="block text-gray-700 font-semibold mb-2">
                                Booking or Reservation Code
                            </label>
                            <input type="text" name="reservation_code" id="reservation_code"
                                value="{{ request('reservation_code') }}"
                                class="w-full p-3 rounded border border-gray-300 focus:ring focus:ring-blue-200" required
                                autofocus>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-700 font-semibold mb-2">
                                Email Address
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full p-3 rounded border border-gray-300 focus:ring focus:ring-blue-200" required>
                        </div>

                        <!-- Phone Number With Clear OTP Purpose -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-gray-700 font-semibold">
                                Phone Number
                            </label>

                            <div class="flex gap-3">
                                <input type="text" name="phone" id="phone" placeholder="e.g. 09123456789"
                                    value="{{ old('phone') }}"
                                    class="flex-1 p-3 rounded border border-gray-300 focus:ring focus:ring-blue-200"
                                    required>

                                <button type="button" id="sendOtpButton"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 rounded whitespace-nowrap transition">
                                    Send OTP
                                </button>
                            </div>

                            <p class="text-sm text-gray-600 font-semibold mt-1">
                                An OTP will be sent to your phone number for verification.
                            </p>
                        </div>

                        <!-- OTP Input -->
                        <div id="otpField" class="hidden">
                            <label for="otp" class="block text-gray-700 font-semibold mb-2">
                                Enter OTP
                            </label>
                            <input type="text" name="otp" id="otp" maxlength="6"
                                class="w-full p-3 rounded border border-gray-300 focus:ring focus:ring-blue-200"
                                placeholder="6-digit code">
                            <p class="text-sm text-gray-500 mt-1">Enter the 6-digit code sent to your phone.</p>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded transition">
                            Verify Information
                        </button>
                    </form>

                    <div class="text-center mt-6">
                        <a href="{{ route('search.index') }}" class="text-blue-600 hover:underline font-medium">
                            ← Back to Search
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let otpSent = false;

        document.getElementById("sendOtpButton").addEventListener("click", function() {
            const reservationCode = document.getElementById("reservation_code").value;
            const email = document.getElementById("email").value;
            const phone = document.getElementById("phone").value;
            const sendOtpBtn = this;
            const originalText = sendOtpBtn.innerHTML;

            // Validate inputs
            if (!reservationCode || !email || !phone) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all fields before sending OTP.',
                });
                return;
            }

            // Disable button and show loading
            sendOtpBtn.disabled = true;
            sendOtpBtn.innerHTML = 'Sending...';

            // Validate contact information and send OTP
            fetch("{{ route('search.validateContactInformation') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        reservation_code: reservationCode,
                        email: email,
                        phone: phone
                    })
                })
                .then(response => {
                    // Check if redirect (successful validation and OTP sent)
                    if (response.redirected) {
                        // OTP was sent successfully
                        otpSent = true;
                        document.getElementById("otpField").classList.remove("hidden");
                        document.getElementById("otp").focus();

                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Sent!',
                            html: 'A 6-digit verification code has been sent to:<br><strong>' + phone +
                                '</strong><br><br>Please check your phone.',
                            timer: 4000,
                            showConfirmButton: true
                        });

                        sendOtpBtn.innerHTML = 'Resend OTP';
                        sendOtpBtn.disabled = false;

                        // Start cooldown timer
                        startCooldownTimer(sendOtpBtn, 60);
                        return null;
                    }
                    return response.text();
                })
                .then(html => {
                    if (html) {
                        // Parse error message from HTML response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Try to find error message
                        let errorMessage = 'Verification failed. Please check your information.';

                        const alertDiv = doc.querySelector('[role="alert"], .alert-danger, .alert-error');
                        if (alertDiv) {
                            errorMessage = alertDiv.textContent.trim();
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Verification Failed',
                            text: errorMessage,
                            confirmButtonColor: '#dc2626'
                        });

                        sendOtpBtn.innerHTML = originalText;
                        sendOtpBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error',
                        text: 'Unable to send OTP. Please check your internet connection and try again.',
                        confirmButtonColor: '#dc2626'
                    });
                    sendOtpBtn.innerHTML = originalText;
                    sendOtpBtn.disabled = false;
                });
        });

        // Cooldown timer function
        function startCooldownTimer(button, seconds) {
            let remaining = seconds;
            const originalText = 'Resend OTP';

            button.disabled = true;

            const interval = setInterval(() => {
                if (remaining <= 0) {
                    clearInterval(interval);
                    button.disabled = false;
                    button.innerHTML = originalText;
                } else {
                    button.innerHTML = `Resend OTP (${remaining}s)`;
                    remaining--;
                }
            }, 1000);
        }

        // Modify form submission to check if OTP was sent
        document.getElementById("verifyInfoForm").addEventListener("submit", function(e) {
            const otpField = document.getElementById("otp");
            const otpValue = otpField.value.trim();

            if (!otpSent) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'OTP Required',
                    text: 'Please click "Send OTP" button first to receive your verification code.',
                    confirmButtonColor: '#f59e0b'
                });
                return false;
            }

            if (!otpValue || otpValue.length !== 6) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid OTP',
                    text: 'Please enter the 6-digit OTP code sent to your phone.',
                    confirmButtonColor: '#f59e0b'
                });
                otpField.focus();
                return false;
            }
        });
    </script>
@endpush
