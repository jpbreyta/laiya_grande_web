@extends('user.layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-sky-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-gray-100">

                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-teal-100 rounded-full mb-4">
                            <i class="fas fa-shield-alt text-teal-600 text-2xl"></i>
                        </div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">
                            Verify Your Identity
                        </h1>
                        <p class="text-gray-600 text-lg">
                            Choose how you'd like to receive your verification code
                        </p>
                        <div class="mt-4 px-4 py-2 bg-teal-50 rounded-lg border border-teal-200">
                            <p class="text-sm text-teal-700 font-medium">
                                <i class="fas fa-bookmark mr-2"></i>
                                Booking Code: <span class="font-bold">{{ $reservationCode }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- OTP Method Selection -->
                    <div class="space-y-4 mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Select verification method:</h3>

                        <!-- Email Option -->
                        <div class="relative">
                            <input type="radio" id="email_method" name="otp_method" value="email" class="peer sr-only"
                                checked>
                            <label for="email_method"
                                class="flex items-center p-6 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:border-teal-300 peer-checked:border-teal-500 peer-checked:bg-teal-50 transition-all duration-200 shadow-sm hover:shadow-md">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mr-4">
                                    <i class="fas fa-envelope text-blue-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900">Email Verification</h4>
                                    <p class="text-gray-600 text-sm mt-1">
                                        We'll send a 6-digit code to your registered email address
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-teal-500 peer-checked:bg-teal-500 flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- SMS Option -->
                        <div class="relative">
                            <input type="radio" id="sms_method" name="otp_method" value="sms" class="peer sr-only">
                            <label for="sms_method"
                                class="flex items-center p-6 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:border-teal-300 peer-checked:border-teal-500 peer-checked:bg-teal-50 transition-all duration-200 shadow-sm hover:shadow-md">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mr-4">
                                    <i class="fas fa-sms text-green-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900">SMS Verification</h4>
                                    <p class="text-gray-600 text-sm mt-1">
                                        We'll send a 6-digit code to your registered phone number
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <div
                                        class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-teal-500 peer-checked:bg-teal-500 flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <button type="button" id="sendOtpBtn"
                            class="w-full bg-gradient-to-r from-teal-600 to-cyan-500 hover:from-teal-700 hover:to-cyan-600 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                            <i class="fas fa-paper-plane mr-3"></i>
                            Send Verification Code
                        </button>

                        <div class="text-center">
                            <a href="{{ route('search.index') }}"
                                class="inline-flex items-center text-gray-600 hover:text-teal-600 font-medium transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Search
                            </a>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="mt-8 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-amber-600 mt-0.5 mr-3"></i>
                            <div class="text-sm text-amber-800">
                                <p class="font-semibold mb-1">Security Notice</p>
                                <p>Your verification code will expire in 10 minutes. Never share this code with anyone.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('sendOtpBtn').addEventListener('click', function() {
            const selectedMethod = document.querySelector('input[name="otp_method"]:checked');
            const sendBtn = this;
            const originalBtnHtml = sendBtn.innerHTML;

            if (!selectedMethod) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please Select Method',
                    text: 'Please choose either Email or SMS verification method.',
                    confirmButtonColor: '#0d9488'
                });
                return;
            }

            // Disable button and show loading
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Sending verification code...';
            sendBtn.classList.add('opacity-70', 'cursor-not-allowed');

            // Send OTP request
            fetch("{{ route('search.sendOtpByMethod') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        reservation_code: "{{ $reservationCode }}",
                        otp_method: selectedMethod.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = originalBtnHtml;
                    sendBtn.classList.remove('opacity-70', 'cursor-not-allowed');

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verification Code Sent!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            didClose: () => {
                                window.location.href = data.redirect_url;
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Send Code',
                            text: data.message || 'Please try again later.',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                })
                .catch(error => {
                    // Reset button
                    sendBtn.disabled = false;
                    sendBtn.innerHTML = originalBtnHtml;
                    sendBtn.classList.remove('opacity-70', 'cursor-not-allowed');

                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error',
                        text: 'Unable to send verification code. Please check your internet connection and try again.',
                        confirmButtonColor: '#dc2626'
                    });
                });
        });

        // Add visual feedback for radio button selection
        document.querySelectorAll('input[name="otp_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove checked styles from all labels
                document.querySelectorAll('label[for$="_method"]').forEach(label => {
                    label.classList.remove('border-teal-500', 'bg-teal-50');
                    label.classList.add('border-gray-200');
                });

                // Add checked styles to selected label
                const selectedLabel = document.querySelector(`label[for="${this.id}"]`);
                selectedLabel.classList.remove('border-gray-200');
                selectedLabel.classList.add('border-teal-500', 'bg-teal-50');

                // Update radio button visual
                document.querySelectorAll('.peer-checked\\:border-teal-500').forEach(circle => {
                    circle.classList.remove('border-teal-500', 'bg-teal-500');
                    circle.classList.add('border-gray-300');
                    circle.querySelector('i').classList.add('opacity-0');
                });

                const selectedCircle = selectedLabel.querySelector('.peer-checked\\:border-teal-500');
                selectedCircle.classList.remove('border-gray-300');
                selectedCircle.classList.add('border-teal-500', 'bg-teal-500');
                selectedCircle.querySelector('i').classList.remove('opacity-0');
            });
        });

        // Initialize the first option as selected
        document.getElementById('email_method').dispatchEvent(new Event('change'));
    </script>
@endpush
