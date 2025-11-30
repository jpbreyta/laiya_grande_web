// Reservation Form JavaScript
const PRIVACY_KEY = 'reserve_privacy_accepted';
const OTP_KEY = 'reserve_otp_timer';
let otpTimer = null;

// Privacy Modal Functions
function checkPrivacy() {
    return localStorage.getItem(PRIVACY_KEY) === 'true';
}

function disableForm() {
    document.querySelectorAll('#reserveForm input, #reserveForm select, #reserveForm textarea, #reserveForm button[type="submit"]').forEach(el => {
        el.disabled = true;
        el.classList.add('opacity-50', 'cursor-not-allowed');
    });
}

function enableForm() {
    document.querySelectorAll('#reserveForm input, #reserveForm select, #reserveForm textarea, #reserveForm button[type="submit"]').forEach(el => {
        el.disabled = false;
        el.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

function showPrivacy() {
    document.getElementById('privacyModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hidePrivacy() {
    document.getElementById('privacyModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// OTP Functions
async function sendOTP() {
    const email = document.getElementById('emailInput').value;
    const sendBtn = document.getElementById('sendOtpBtn');
    const otpInput = document.getElementById('otpInput');

    if (!email || !email.includes('@')) {
        Swal.fire({
            title: 'Invalid Email',
            text: 'Please enter a valid email',
            icon: 'warning',
            confirmButtonColor: '#0f766e'
        });
        return;
    }

    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

    try {
        const res = await fetch(window.sendOtpRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ email })
        });

        const data = await res.json();

        if (data.success) {
            otpInput.disabled = false;
            otpInput.focus();
            const sentAt = Date.now();
            localStorage.setItem(OTP_KEY, JSON.stringify({
                sentAt,
                expiresAt: sentAt + 60000
            }));
            startOtpTimer();
            Swal.fire({
                title: 'OTP Sent!',
                text: 'Check your email',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            sendBtn.disabled = false;
            sendBtn.innerHTML = 'Send OTP';
            Swal.fire({
                title: 'Failed',
                text: data.message || 'Failed to send OTP',
                icon: 'error',
                confirmButtonColor: '#0f766e'
            });
        }
    } catch (e) {
        sendBtn.disabled = false;
        sendBtn.innerHTML = 'Send OTP';
        Swal.fire({
            title: 'Error',
            text: 'Error sending OTP',
            icon: 'error',
            confirmButtonColor: '#0f766e'
        });
    }
}

function startOtpTimer() {
    const sendBtn = document.getElementById('sendOtpBtn');
    const timerData = JSON.parse(localStorage.getItem(OTP_KEY));
    if (!timerData) return;
    
    if (otpTimer) clearInterval(otpTimer);
    
    otpTimer = setInterval(() => {
        const now = Date.now();
        const remaining = timerData.expiresAt - now;
        
        if (remaining <= 0) {
            clearInterval(otpTimer);
            localStorage.removeItem(OTP_KEY);
            sendBtn.disabled = false;
            sendBtn.innerHTML = 'Resend OTP';
            sendBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
            sendBtn.classList.add('bg-teal-600', 'hover:bg-teal-700');
            return;
        }
        
        const seconds = Math.ceil(remaining / 1000);
        sendBtn.disabled = true;
        sendBtn.innerHTML = `Wait ${seconds}s`;
        sendBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        sendBtn.classList.remove('bg-teal-600', 'hover:bg-teal-700');
    }, 100);
}

// Phone Validation
function validatePhone() {
    const phoneInput = document.getElementById('phoneInput');
    const phoneDisplay = document.getElementById('phoneDisplay');
    const phoneError = document.getElementById('phoneError');
    const phone = phoneInput.value;

    if (!phone) {
        phoneError.className = 'text-red-600 text-xs mt-1 flex items-center gap-1';
        phoneError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Phone required';
        phoneError.classList.remove('hidden');
        phoneDisplay.classList.add('border-red-500');
        return false;
    }

    const digits = phone.replace(/\D/g, '');
    if (digits.length !== 11 || !digits.startsWith('09')) {
        phoneError.className = 'text-red-600 text-xs mt-1 flex items-center gap-1';
        phoneError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Invalid format';
        phoneError.classList.remove('hidden');
        phoneDisplay.classList.add('border-red-500');
        return false;
    }

    phoneError.classList.add('hidden');
    phoneDisplay.classList.remove('border-red-500');
    phoneDisplay.classList.add('border-green-500');
    return true;
}

// Date and Price Calculations
function updateDays() {
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');
    const daysCount = document.getElementById('days_count_hidden');
    
    const checkInDate = new Date(checkIn.value);
    const checkOutDate = new Date(checkOut.value);

    if (!isNaN(checkInDate) && !isNaN(checkOutDate)) {
        const diffTime = checkOutDate - checkInDate;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const nights = diffDays > 0 ? diffDays : 1;
        daysCount.value = nights;
        updateTotals(nights);
    } else {
        updateTotals(1);
    }
}

function updateTotals(nights) {
    let subtotal = 0;
    
    document.querySelectorAll('.item-total').forEach(el => {
        const price = parseFloat(el.dataset.price);
        const qty = parseInt(el.dataset.qty);
        const itemTotal = price * qty * nights;
        el.textContent = "PHP " + itemTotal.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        subtotal += price * qty;
    });

    const grandTotal = subtotal * nights;

    const subtotalEl = document.getElementById('subtotalPerNight');
    if (subtotalEl) subtotalEl.textContent = "PHP " + subtotal.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    const nightsDisplayEl = document.getElementById('nightsDisplay');
    if (nightsDisplayEl) nightsDisplayEl.textContent = nights + " night(s)";

    const grandTotalEl = document.getElementById('grandTotal');
    if (grandTotalEl) grandTotalEl.textContent = "PHP " + grandTotal.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    const amountDueEl = document.getElementById('amountDueReserve');
    if (amountDueEl) amountDueEl.textContent = "PHP " + grandTotal.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    const nightsInfoEl = document.getElementById('nightsInfo');
    if (nightsInfoEl) nightsInfoEl.textContent = nights + " night(s)";
}

function recalculateTotals() {
    const daysCountInput = document.getElementById('days_count_hidden');
    const nights = parseInt(daysCountInput?.value) || 1;
    updateTotals(nights);
}

// Cart Management
async function removeFromCart(roomId) {
    const result = await Swal.fire({
        title: 'Remove Room?',
        text: 'Remove this room from reservation?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, remove',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });

    if (!result.isConfirmed) return;

    Swal.fire({
        title: 'Removing...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    try {
        const res = await fetch(`/cart/remove/${roomId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            }
        });

        const data = await res.json();

        if (data.success) {
            const cartItem = document.querySelector(`.cart-item[data-room-id="${roomId}"]`);
            if (cartItem) {
                cartItem.style.transition = 'all 0.3s ease';
                cartItem.style.opacity = '0';
                cartItem.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    cartItem.remove();
                    const remaining = document.querySelectorAll('.cart-item');
                    
                    if (remaining.length === 0) {
                        Swal.fire({
                            title: 'Cart Empty',
                            text: 'Redirecting...',
                            icon: 'info',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.reload());
                    } else {
                        recalculateTotals();
                        Swal.fire({
                            title: 'Removed!',
                            text: 'Room removed',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }, 300);
            }
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Failed to remove',
                icon: 'error',
                confirmButtonColor: '#0f766e'
            });
        }
    } catch (e) {
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred',
            icon: 'error',
            confirmButtonColor: '#0f766e'
        });
    }
}

// Initialize on DOM Load
document.addEventListener('DOMContentLoaded', function() {
    // Privacy Modal
    if (!checkPrivacy()) {
        disableForm();
        showPrivacy();
    }

    document.getElementById('acceptBtn').addEventListener('click', function() {
        localStorage.setItem(PRIVACY_KEY, 'true');
        hidePrivacy();
        enableForm();
        Swal.fire({
            title: 'Thank You!',
            text: 'You can now proceed',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    });

    document.getElementById('declineBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Terms Declined',
            text: 'You must accept to proceed',
            icon: 'warning',
            confirmButtonColor: '#0f766e'
        }).then(() => window.location.href = window.bookingIndexRoute);
    });

    // Date Restrictions
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('check_in').min = today;
    document.getElementById('check_out').min = today;

    // Payment Method Toggle
    const paySelect = document.getElementById('paymentMethodSelect');
    const bankWrap = document.getElementById('bankFieldsReserve');

    function syncBank() {
        const isBank = paySelect && paySelect.value === 'bank_transfer';
        if (bankWrap) {
            bankWrap.classList.toggle('hidden', !isBank);
            bankWrap.querySelectorAll('input').forEach(inp => inp.toggleAttribute('required', isBank));
        }
    }

    if (paySelect) {
        paySelect.addEventListener('change', syncBank);
        syncBank();
    }

    // OTP Timer Restoration
    const timerData = localStorage.getItem(OTP_KEY);
    if (timerData) {
        const data = JSON.parse(timerData);
        if (Date.now() < data.expiresAt) {
            startOtpTimer();
            document.getElementById('otpInput').disabled = false;
        } else {
            localStorage.removeItem(OTP_KEY);
        }
    }

    // OTP Verification
    document.getElementById('otpInput').addEventListener('input', async function() {
        const otp = this.value;
        const otpStatus = document.getElementById('otpStatus');
        const emailInput = document.getElementById('emailInput');
        const email = emailInput.value;
        const sendOtpBtn = document.getElementById('sendOtpBtn');

        if (otp.length === 6) {
            try {
                const res = await fetch(window.verifyOtpRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.csrfToken
                    },
                    body: JSON.stringify({ email, otp })
                });
                const data = await res.json();

                if (data.success) {
                    otpStatus.className = 'text-green-600 text-xs mt-1 flex items-center gap-1';
                    otpStatus.innerHTML = '<i class="fas fa-check-circle"></i> Verified!';
                    otpStatus.classList.remove('hidden');
                    document.getElementById('otpVerified').value = '1';
                    this.classList.add('border-green-500', 'bg-green-50');
                    emailInput.readOnly = true;
                    emailInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    this.readOnly = true;
                    this.classList.add('bg-gray-100', 'cursor-not-allowed');
                    sendOtpBtn.disabled = true;
                    sendOtpBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                    sendOtpBtn.classList.remove('bg-teal-600', 'hover:bg-teal-700', 'bg-gray-400');
                    sendOtpBtn.innerHTML = '<i class="fas fa-check"></i> Verified';
                    localStorage.removeItem(OTP_KEY);
                    if (otpTimer) clearInterval(otpTimer);
                    Swal.fire({
                        title: 'Verified!',
                        text: 'Email verified',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    otpStatus.className = 'text-red-600 text-xs mt-1 flex items-center gap-1';
                    otpStatus.innerHTML = '<i class="fas fa-times-circle"></i> ' + (data.message || 'Invalid OTP');
                    otpStatus.classList.remove('hidden');
                    document.getElementById('otpVerified').value = '0';
                    this.classList.remove('border-green-500', 'bg-green-50');
                }
            } catch (e) {
                otpStatus.className = 'text-red-600 text-xs mt-1 flex items-center gap-1';
                otpStatus.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error verifying';
                otpStatus.classList.remove('hidden');
                document.getElementById('otpVerified').value = '0';
            }
        }
    });

    // Phone Formatting
    const phoneDisplay = document.getElementById('phoneDisplay');
    const phoneInput = document.getElementById('phoneInput');

    phoneDisplay.addEventListener('input', function() {
        let digits = this.value.replace(/\D/g, '');
        if (digits.startsWith('63')) digits = digits.slice(2);
        if (digits.startsWith('0')) digits = digits.slice(1);

        let hasZero = this.value.replace(/\D/g, '').startsWith('0');
        if (hasZero) {
            if (digits.length > 10) digits = digits.slice(0, 10);
        } else {
            if (digits.length > 10) digits = digits.slice(0, 10);
        }

        let formatted = '+63';
        if (digits.length > 0) formatted += ' ' + digits.slice(0, 3);
        if (digits.length > 3) formatted += ' ' + digits.slice(3, 6);
        if (digits.length > 6) formatted += ' ' + digits.slice(6, 10);

        this.value = formatted;

        if (digits.length === 10) {
            phoneInput.value = '0' + digits;
            validatePhone();
        } else if (digits.length > 0) {
            phoneInput.value = '0' + digits;
            document.getElementById('phoneError').classList.add('hidden');
        } else {
            phoneInput.value = '';
            document.getElementById('phoneError').classList.add('hidden');
        }
    });

    // Date Change Handlers
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');

    checkIn.addEventListener('change', function() {
        if (this.value) {
            const checkInDate = new Date(this.value);
            checkInDate.setDate(checkInDate.getDate() + 1);
            const minCheckOut = checkInDate.toISOString().split('T')[0];
            checkOut.min = minCheckOut;

            if (checkOut.value && checkOut.value <= this.value) {
                checkOut.value = '';
            }
        }
        updateDays();
    });

    checkOut.addEventListener('change', updateDays);

    // Initialize totals
    updateTotals(1);

    // Form Submission
    document.getElementById('reserveForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const otpVerified = document.getElementById('otpVerified').value;
        if (otpVerified !== '1') {
            Swal.fire({
                title: 'Email Not Verified',
                text: 'Please verify email with OTP',
                icon: 'warning',
                confirmButtonColor: '#0f766e'
            });
            return false;
        }

        const phoneValid = validatePhone();
        if (!phoneValid) {
            Swal.fire({
                title: 'Invalid Phone',
                text: 'Enter valid PH mobile number',
                icon: 'warning',
                confirmButtonColor: '#0f766e'
            });
            return false;
        }

        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Submitting your reservation',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        // Submit form via AJAX
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': window.csrfToken
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    html: `<p>${data.message}</p><p class="mt-2 font-bold">Reservation #: ${data.reservation_number}</p>`,
                    icon: 'success',
                    confirmButtonColor: '#0f766e',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Redirect to review page if URL provided, otherwise home
                    window.location.href = data.redirect_url || window.homeRoute;
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Failed to create reservation',
                    icon: 'error',
                    confirmButtonColor: '#0f766e'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred. Please try again.',
                icon: 'error',
                confirmButtonColor: '#0f766e'
            });
        });
    });
});
