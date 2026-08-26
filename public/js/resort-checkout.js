(() => {
    'use strict';

    const money = new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2,
    });

    const csrfToken = (root) => root?.dataset.csrfToken
        || document.querySelector('meta[name="csrf-token"]')?.content
        || '';

    const setMessage = (container, message, type = 'success') => {
        if (!container) return;
        container.textContent = message;
        container.classList.remove('hidden', 'border-red-200', 'bg-red-50', 'text-red-700', 'border-emerald-200', 'bg-emerald-50', 'text-emerald-700');
        const classes = type === 'error'
            ? ['border-red-200', 'bg-red-50', 'text-red-700']
            : ['border-emerald-200', 'bg-emerald-50', 'text-emerald-700'];
        container.classList.add(...classes);
    };

    const escapeHtml = (value) => String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('\"', '&quot;')
        .replaceAll("'", '&#039;');

    const clearErrors = (form) => {
        const container = form.closest('section')?.querySelector('[data-client-errors]') || form.querySelector('[data-client-errors]');
        if (container) {
            container.classList.add('hidden');
            container.innerHTML = '';
        }
        form.querySelectorAll('[aria-invalid="true"]').forEach((element) => element.removeAttribute('aria-invalid'));
    };

    const showErrors = (form, errors = {}, fallback = 'Please review the submitted information.') => {
        const container = form.closest('section')?.querySelector('[data-client-errors]') || form.querySelector('[data-client-errors]');
        if (!container) return;

        const messages = Object.values(errors).flat().filter(Boolean);
        container.innerHTML = `<strong>${escapeHtml(fallback)}</strong>${messages.length ? `<ul class="mt-2 list-disc pl-5">${messages.map((message) => `<li>${escapeHtml(message)}</li>`).join('')}</ul>` : ''}`;
        container.classList.remove('hidden');

        Object.keys(errors).forEach((name) => {
            form.querySelector(`[name="${CSS.escape(name)}"]`)?.setAttribute('aria-invalid', 'true');
        });
        container.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    const requestJson = async (url, options = {}) => {
        const response = await fetch(url, {
            ...options,
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...options.headers,
            },
        });

        let data = {};
        try {
            data = await response.json();
        } catch (_) {
            data = { message: 'The server returned an unexpected response.' };
        }

        if (!response.ok) {
            const error = new Error(data.message || 'The request could not be completed.');
            error.data = data;
            error.status = response.status;
            throw error;
        }

        return data;
    };

    const setSubmitting = (form, submitting) => {
        const button = form.querySelector('[data-submit-button]');
        if (!button) return;
        if (!button.dataset.label) button.dataset.label = button.textContent.trim();
        button.disabled = submitting;
        button.textContent = submitting ? 'Please wait…' : button.dataset.label;
    };

    const initDialogs = () => {
        const dialog = document.getElementById('privacyDialog');
        if (!dialog) return;
        document.querySelectorAll('[data-open-dialog]').forEach((button) => button.addEventListener('click', () => dialog.showModal()));
        dialog.querySelectorAll('[data-close-dialog]').forEach((button) => button.addEventListener('click', () => dialog.close()));
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });
    };

    const initCheckout = () => {
        const root = document.querySelector('[data-checkout-page]');
        if (!root) return;
        const form = root.querySelector('[data-checkout-form]');
        if (!form) return;

        const email = form.querySelector('[data-otp-email]');
        const code = form.querySelector('[data-otp-code]');
        const sendButton = form.querySelector('[data-send-otp]');
        const verifyButton = form.querySelector('[data-verify-otp]');
        const otpStatus = form.querySelector('[data-otp-status]');
        const timer = form.querySelector('[data-otp-timer]');
        let verifiedEmail = null;
        let countdownId = null;

        const resetVerification = () => {
            verifiedEmail = null;
            if (otpStatus) {
                otpStatus.textContent = '';
                otpStatus.className = 'mt-2 text-xs';
            }
            if (code) code.value = '';
        };

        email?.addEventListener('input', () => {
            if (verifiedEmail && email.value.trim().toLowerCase() !== verifiedEmail) resetVerification();
        });

        sendButton?.addEventListener('click', async () => {
            if (!email?.reportValidity()) return;
            sendButton.disabled = true;
            const original = sendButton.textContent;
            sendButton.textContent = 'Sending…';
            try {
                const data = await requestJson(root.dataset.sendOtpUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(root),
                    },
                    body: JSON.stringify({
                        email: email.value.trim(),
                        first_name: form.elements.first_name?.value || null,
                        last_name: form.elements.last_name?.value || null,
                        phone: form.elements.phone?.value || null,
                    }),
                });
                code.disabled = false;
                verifyButton.disabled = false;
                code.focus();
                if (otpStatus) {
                    otpStatus.textContent = data.message || 'Verification code sent.';
                    otpStatus.className = 'mt-2 text-xs text-emerald-700';
                }

                let remaining = 60;
                clearInterval(countdownId);
                countdownId = setInterval(() => {
                    remaining -= 1;
                    if (timer) timer.textContent = remaining > 0 ? `You can request another code in ${remaining}s.` : '';
                    if (remaining <= 0) {
                        clearInterval(countdownId);
                        sendButton.disabled = false;
                        sendButton.textContent = original;
                    }
                }, 1000);
            } catch (error) {
                sendButton.disabled = false;
                sendButton.textContent = original;
                if (otpStatus) {
                    otpStatus.textContent = error.message;
                    otpStatus.className = 'mt-2 text-xs text-red-700';
                }
            }
        });

        verifyButton?.addEventListener('click', async () => {
            if (!code?.checkValidity() || code.value.length !== 6) {
                code?.reportValidity();
                return;
            }
            verifyButton.disabled = true;
            const original = verifyButton.textContent;
            verifyButton.textContent = 'Checking…';
            try {
                const data = await requestJson(root.dataset.verifyOtpUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken(root),
                    },
                    body: JSON.stringify({ email: email.value.trim(), otp: code.value }),
                });
                verifiedEmail = email.value.trim().toLowerCase();
                email.readOnly = true;
                code.readOnly = true;
                if (otpStatus) {
                    otpStatus.textContent = data.message || 'Email verified.';
                    otpStatus.className = 'mt-2 text-xs font-semibold text-emerald-700';
                }
                verifyButton.textContent = 'Verified';
            } catch (error) {
                verifyButton.disabled = false;
                verifyButton.textContent = original;
                if (otpStatus) {
                    otpStatus.textContent = error.message;
                    otpStatus.className = 'mt-2 text-xs text-red-700';
                }
            }
        });

        const checkIn = form.querySelector('[data-check-in]');
        const checkOut = form.querySelector('[data-check-out]');
        const updateTotals = () => {
            if (!checkIn || !checkOut || !checkIn.value || !checkOut.value) return;
            const start = new Date(`${checkIn.value}T00:00:00Z`);
            const end = new Date(`${checkOut.value}T00:00:00Z`);
            const nights = Math.max(1, Math.round((end - start) / 86400000));
            const subtotal = Number(root.dataset.nightlySubtotal || 0);
            root.querySelectorAll('[data-nights]').forEach((element) => { element.textContent = nights; });
            root.querySelectorAll('[data-total]').forEach((element) => { element.textContent = money.format(subtotal * nights); });
            root.querySelectorAll('[data-item-total]').forEach((element) => {
                element.textContent = money.format(Number(element.dataset.lineSubtotal || 0) * nights);
            });
        };

        checkIn?.addEventListener('change', () => {
            if (checkIn.value) {
                const minimum = new Date(`${checkIn.value}T00:00:00Z`);
                minimum.setUTCDate(minimum.getUTCDate() + 1);
                checkOut.min = minimum.toISOString().slice(0, 10);
                if (checkOut.value && checkOut.value <= checkIn.value) checkOut.value = '';
            }
            updateTotals();
        });
        checkOut?.addEventListener('change', updateTotals);
        updateTotals();

        form.querySelector('[data-payment-proof]')?.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            const label = form.querySelector('[data-file-name]');
            if (label) label.textContent = file ? `Selected: ${file.name}` : '';
        });

        form.addEventListener('submit', async (event) => {
            clearErrors(form);
            if (!form.reportValidity()) {
                event.preventDefault();
                return;
            }
            if (!verifiedEmail || email.value.trim().toLowerCase() !== verifiedEmail) {
                event.preventDefault();
                showErrors(form, {}, 'Verify the booking email before continuing.');
                return;
            }
            if (form.dataset.ajaxSubmit !== 'true') return;

            event.preventDefault();
            setSubmitting(form, true);
            try {
                const data = await requestJson(form.action, {
                    method: form.method || 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken(root) },
                    body: new FormData(form),
                });
                const message = root.querySelector('[data-form-message]');
                setMessage(message, data.message || 'Reservation submitted successfully.');
                window.location.assign(data.redirect_url || root.dataset.successUrl);
            } catch (error) {
                showErrors(form, error.data?.errors, error.message);
            } finally {
                setSubmitting(form, false);
            }
        });

        root.querySelectorAll('[data-remove-cart]').forEach((button) => {
            button.addEventListener('click', async () => {
                if (!window.confirm('Remove this room from the cart?')) return;
                button.disabled = true;
                try {
                    await requestJson(button.dataset.removeUrl, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken(root),
                        },
                    });
                    window.location.reload();
                } catch (error) {
                    button.disabled = false;
                    showErrors(form, error.data?.errors, error.message);
                }
            });
        });
    };

    const initAjaxForms = () => {
        document.querySelectorAll('[data-ajax-form]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                clearErrors(form);
                if (!form.reportValidity()) return;
                setSubmitting(form, true);
                const root = form.closest('[data-confirm-page]') || document.body;
                try {
                    const data = await requestJson(form.action, {
                        method: form.method || 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken(root) },
                        body: new FormData(form),
                    });
                    setMessage(form.querySelector('[data-form-message]') || root.querySelector('[data-form-message]'), data.message || 'Submitted successfully.');
                    const redirect = data.redirect_url || data.redirect || root.dataset.successUrl;
                    if (redirect) setTimeout(() => window.location.assign(redirect), 900);
                } catch (error) {
                    showErrors(form, error.data?.errors, error.message);
                } finally {
                    setSubmitting(form, false);
                }
            });
        });
    };

    const initCartAdd = () => {
        document.querySelectorAll('[data-cart-add-form]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                clearErrors(form);
                if (!form.reportValidity()) return;
                setSubmitting(form, true);
                const root = form.closest('[data-room-page]') || document.body;
                try {
                    const data = await requestJson(form.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken(root) },
                        body: new FormData(form),
                    });
                    setMessage(form.querySelector('[data-form-message]'), data.message || 'Room added to cart.');
                    if (form.dataset.successUrl) setTimeout(() => window.location.assign(form.dataset.successUrl), 600);
                } catch (error) {
                    showErrors(form, error.data?.errors, error.message);
                } finally {
                    setSubmitting(form, false);
                }
            });
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        initDialogs();
        initCheckout();
        initAjaxForms();
        initCartAdd();
        document.querySelectorAll('[data-history-back]').forEach((button) => button.addEventListener('click', () => history.back()));
        document.querySelectorAll('[data-print-page]').forEach((button) => button.addEventListener('click', () => window.print()));
    });
})();
