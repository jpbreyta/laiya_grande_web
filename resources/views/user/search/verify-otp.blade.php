@extends('user.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-sky-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-100">
                    <h1 class="text-3xl font-extrabold mb-6 text-gray-900 tracking-tight">Enter OTP</h1>

                    <form id="verifyOtpForm" method="POST" action="{{ route('search.verifyOtp') }}"
                        class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 rounded-lg shadow-sm border border-gray-200">
                        @csrf
                        <input type="hidden" name="reservation_code"
                            value="{{ request()->input('reservation_code') ?? '' }}" />

                        <div class="mb-6">
                            <label for="otp" class="block text-gray-700 font-semibold mb-2">One-Time Password
                                (OTP)</label>
                            <input type="text" name="otp" id="otp"
                                class="w-full p-3 rounded border border-gray-300" maxlength="6" required autofocus>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded">
                            Verify OTP
                        </button>
                    </form>

                    <form id="resendOtpForm" method="POST" action="{{ route('search.sendOtp') }}" class="mt-4">
                        @csrf
                        <input type="hidden" name="reservation_code"
                            value="{{ request()->input('reservation_code') ?? '' }}" />
                        <button type="submit" id="resendOtpBtn"
                            class="w-full bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold py-3 px-6 rounded">
                            Resend OTP
                        </button>
                        <p id="otpTimer" class="text-gray-500 text-sm mt-2 text-center"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // AJAX submission for Resend OTP
        document.getElementById('resendOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const reservationCode = formData.get('reservation_code');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Sending...';
            submitBtn.disabled = true;


            fetch("{{ route('search.sendOtp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        reservation_code: reservationCode
                    })
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        title: data.success ? 'Success!' : 'Oops...',
                        text: data.message
                    });
                })
                .catch(err => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Try again.'
                    });
                });
        });

        // AJAX submission for OTP verification
        document.getElementById('verifyOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const reservationCode = formData.get('reservation_code');
            const otp = formData.get('otp');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Verifying...';
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
                            title: 'Success!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message
                        });
                    }
                })
                .catch(err => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Try again.'
                    });
                });
        });
    </script>

    <script>
        const resendForm = document.getElementById('resendOtpForm');
        const resendBtn = document.getElementById('resendOtpBtn');
        const timerText = document.getElementById('otpTimer');

        // Set OTP cooldown (in minutes)
        let otpCooldownMinutes = 1; // <-- change this to whatever you want
        let otpCooldown = otpCooldownMinutes * 60; // seconds
        let countdownInterval;

        function startOtpTimer(seconds) {
            resendBtn.disabled = true;
            clearInterval(countdownInterval);
            countdownInterval = setInterval(() => {
                if (seconds <= 0) {
                    clearInterval(countdownInterval);
                    resendBtn.disabled = false;
                    timerText.textContent = '';
                } else {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    timerText.textContent = `Resend available in ${mins}:${secs < 10 ? '0' : ''}${secs}`;
                    seconds--;
                }
            }, 1000);
        }

        // Start the timer on page load (if you want)
        startOtpTimer(otpCooldown);

        resendForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const reservationCode = formData.get('reservation_code');
            const originalText = resendBtn.innerHTML;
            resendBtn.innerHTML = 'Sending...';
            resendBtn.disabled = true;

            fetch("{{ route('search.sendOtp') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        reservation_code: reservationCode
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        title: data.success ? 'Success!' : 'Oops...',
                        text: data.message
                    });
                    resendBtn.innerHTML = originalText;

                    if (data.success) {
                        // Start countdown again after successful resend
                        startOtpTimer(otpCooldown);
                    } else {
                        resendBtn.disabled = false;
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Try again.'
                    });
                    resendBtn.innerHTML = originalText;
                    resendBtn.disabled = false;
                });
        });
    </script>
@endpush
