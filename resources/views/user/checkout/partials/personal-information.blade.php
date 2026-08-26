<section class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg shadow-teal-900/5">
    <header class="border-b border-slate-100 bg-teal-50/40 px-6 py-5 sm:px-8">
        <h2 class="flex items-center gap-3 text-xl font-bold text-teal-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-100 text-teal-700">
                <i class="fas fa-user" aria-hidden="true"></i>
            </span>
            Personal Information
        </h2>
    </header>

    <div class="space-y-6 p-6 sm:p-8">
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="block">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">First name</span>
                <input type="text" name="first_name" value="{{ old('first_name') }}" maxlength="100" autocomplete="given-name" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            </label>
            <label class="block">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Last name</span>
                <input type="text" name="last_name" value="{{ old('last_name') }}" maxlength="100" autocomplete="family-name" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
            </label>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div>
                <label for="checkoutEmail" class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Email address</label>
                <div class="flex gap-2">
                    <input id="checkoutEmail" type="email" name="email" value="{{ old('email') }}" maxlength="255" autocomplete="email" required
                        class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200"
                        data-otp-email>
                    <button type="button" data-send-otp
                        class="shrink-0 rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-800 disabled:cursor-not-allowed disabled:opacity-60">
                        Send OTP
                    </button>
                </div>
                <p class="mt-2 text-xs text-slate-500" data-otp-timer></p>
            </div>

            <div>
                <label for="checkoutOtp" class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Verification code</label>
                <div class="flex gap-2">
                    <input id="checkoutOtp" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6" pattern="[0-9]{6}"
                        class="min-w-0 flex-1 rounded-xl border border-slate-300 px-4 py-3 tracking-[0.35em] outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200"
                        placeholder="000000" data-otp-code disabled>
                    <button type="button" data-verify-otp disabled
                        class="shrink-0 rounded-xl border border-teal-700 px-4 py-3 text-sm font-semibold text-teal-700 transition hover:bg-teal-50 disabled:cursor-not-allowed disabled:opacity-50">
                        Verify
                    </button>
                </div>
                <p class="mt-2 text-xs" data-otp-status aria-live="polite"></p>
            </div>
        </div>

        <label class="block max-w-xl">
            <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Phone number</span>
            <input type="tel" name="phone" value="{{ old('phone') }}" maxlength="20" autocomplete="tel" required pattern="(?:\+?63|0)9[0-9]{9}"
                placeholder="09XXXXXXXXX or +639XXXXXXXXX"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200"
                data-phone>
        </label>
    </div>
</section>
