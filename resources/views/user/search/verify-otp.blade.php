@extends('user.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-sky-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-100">

                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-100 rounded-full mb-4">
                            @if (request('method') === 'email')
                                <i class="fas fa-envelope text-teal-600 text-2xl"></i>
                            @else
                                <i class="fas fa-sms text-teal-600 text-2xl"></i>
                            @endif
                        </div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">
                            Enter Verification Code
                        </h1>
                        <p class="text-gray-600 text-lg">
                            @if (request('method') === 'email')
                                We've sent a 6-digit code to your email address
                            @else
                                We've sent a 6-digit code to your phone number
                            @endif
                        </p>
                        <div class="mt-4 px-4 py-2 bg-teal-50 rounded-lg border border-teal-200">
                            <p class="text-sm text-teal-700 font-medium">
                                <i class="fas fa-bookmark mr-2"></i>
                                Booking Code: <span class="font-bold">{{ request('reservation_code') }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- OTP Form -->
                    <form id="verifyOtpForm" method="POST" action="{{ route('search.verifyOtp') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="reservation_code"
                            value="{{ request()->input('reservation_code') ?? '' }}" />

                        <div>
                            <label for="otp" class="block text-gray-700 font-semibold mb-3 text-center">
                                Enter 6-Digit Verification Code
                            </label>
                            <div class="flex justify-center">
                                <input type="text" name="otp" id="otp"
                                    class="w-48 p-4 text-center text-2xl font-bold tracking-widest rounded-lg border-2 border-gray-300 focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition-all duration-200"
                                    maxlength="6" placeholder="000000" required autofocus autocomplete="off">
                            </div>
                            <p class="text-sm text-gray-500 text-center mt-2">
                                Code expires in 10 minutes
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                            <i class="fas fa-check-circle mr-3"></i>
                            Verify Code
                        </button>
                    </form>

                    <!-- Resend Section -->
                    <div class="mt-8 text-center">
                        <p class="text-gray-600 mb-4">Didn't receive the code?</p>
                        <button type="button" id="resendOtpBtn"
                            class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                            <i class="fas fa-redo mr-2"></i>
                            Resend Code
                        </button>
                        <p id="otpTimer" class="text-gray-500 text-sm mt-2"></p>
                    </div>

                    <!-- Back Link -->
                    <div class="text-center mt-6">
                        <a href="{{ route('search.selectOtpMethod', ['reservation_code' => request('reservation_code')]) }}"
                            class="inline-flex items-center text-gray-600 hover:text-teal-600 font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Change verification method
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // AJAX submission for OTP verification
        document.getElementById('verifyOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const reservationCode = formData.get('reservation_code');
            const otp = formData.get('otp');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Validate OTP format
            if (!otp || otp.length !== 6 || !/^\d{6}$/.test(otp)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Code',
                    text: 'Please enter a valid 6-digit verification code.',
                    confirmButtonColor: '#0d9488'
                });
                return;
            }

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Verifying...';
            submitBtn.disabled = true;

            fetch("{{ route('search.verifyOtp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        reservation_code: reservationCode,
                        otp: otp
                    })
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verification Successful!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Verification Failed',
                            text: data.message,
                            confirmButtonColor: '#dc2626'
                        });
                        // Clear the input for retry
                        document.getElementById('otp').value = '';
                        document.getElementById('otp').focus();
                    }
                })
                .catch(err => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error',
                        text: 'Something went wrong. Please try again.',
                        confirmButtonColor: '#dc2626'
                    });
                });
        });

        // Resend OTP functionality
        const resendBtn = document.getElementById('resendOtpBtn');
        const timerText = document.getElementById('otpTimer');
        let otpCooldown = 60; // 60 seconds cooldown
        let countdownInterval;

        function startOtpTimer(seconds) {
            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
            clearInterval(countdownInterval);

            countdownInterval = setInterval(() => {
                if (seconds <= 0) {
                    clearInterval(countdownInterval);
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    timerText.textContent = '';
                } else {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    timerText.textContent = `Resend available in ${mins}:${secs < 10 ? '0' : ''}${secs}`;
                    seconds--;
                }
            }, 1000);
        }

        // Start the timer on page load
        startOtpTimer(otpCooldown);

        resendBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const reservationCode = "{{ request('reservation_code') }}";
            const method = "{{ request('method', 'sms') }}";
            const originalText = resendBtn.innerHTML;

            resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            resendBtn.disabled = true;

            fetch("{{ route('search.sendOtpByMethod') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        reservation_code: reservationCode,
                        otp_method: method
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        title: data.success ? 'Code Resent!' : 'Failed to Resend',
                        text: data.message,
                        confirmButtonColor: data.success ? '#0d9488' : '#dc2626'
                    });

                    resendBtn.innerHTML = originalText;

                    if (data.success) {
                        // Start countdown again after successful resend
                        startOtpTimer(otpCooldown);
                        // Clear and focus the OTP input
                        document.getElementById('otp').value = '';
                        document.getElementById('otp').focus();
                    } else {
                        resendBtn.disabled = false;
                        resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error',
                        text: 'Unable to resend code. Please try again.',
                        confirmButtonColor: '#dc2626'
                    });
                    resendBtn.innerHTML = originalText;
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
        });

        // Auto-format OTP input (numbers only, auto-advance)
        document.getElementById('otp').addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/\D/g, '');

            // Auto-submit when 6 digits are entered
            if (this.value.length === 6) {
                // Small delay to show the complete code before submitting
                setTimeout(() => {
                    document.getElementById('verifyOtpForm').dispatchEvent(new Event('submit'));
                }, 500);
            }
        });

        // Prevent paste of non-numeric content
        document.getElementById('otp').addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const numericPaste = paste.replace(/\D/g, '').substring(0, 6);
            this.value = numericPaste;

            if (numericPaste.length === 6) {
                setTimeout(() => {
                    document.getElementById('verifyOtpForm').dispatchEvent(new Event('submit'));
                }, 500);
            }
        });
    </script>
@endpush
