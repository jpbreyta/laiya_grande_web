<div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-100">
    <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
        <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                <i class="fas fa-user"></i>
            </span>
            Personal Information
        </h5>
    </div>
    <div class="p-8 space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">First Name *</label>
                <input type="text" name="first_name" required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                    placeholder="Enter first name" value="{{ old('first_name') }}">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Last Name *</label>
                <input type="text" name="last_name" required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                    placeholder="Enter last name" value="{{ old('last_name') }}">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email Address *</label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="email" id="emailInput" name="email" required
                            class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                            placeholder="name@example.com" value="{{ old('email') }}">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                    <button type="button" id="sendOtpBtn" onclick="sendOTP()"
                        class="px-4 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-semibold text-sm whitespace-nowrap">
                        Send OTP
                    </button>
                </div>
                <small id="otpTimer" class="text-teal-600 text-xs mt-1 hidden flex items-center gap-1">
                    <i class="fas fa-clock"></i><span id="timerText"></span>
                </small>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Enter OTP Code *</label>
                <div class="relative">
                    <input type="text" id="otpInput" name="otp_code" required maxlength="6"
                        class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                        placeholder="Enter 6-digit code" disabled>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-key text-gray-400"></i>
                    </div>
                </div>
                <small id="otpStatus" class="text-xs mt-1 hidden"></small>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Phone Number *</label>
                <div class="relative">
                    <input type="tel" id="phoneDisplay"
                        class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none"
                        placeholder="+63 912 345 6789">
                    <input type="hidden" id="phoneInput" name="phone" required value="{{ old('phone') }}">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                </div>
                <small id="phoneError" class="text-xs mt-1 hidden"></small>
                <small class="text-slate-500 text-xs mt-1 block">
                    <i class="fas fa-info-circle"></i> Enter PH mobile: 09XX or 9XX
                </small>
            </div>
            <div></div>
        </div>

        <input type="hidden" id="otpVerified" name="otp_verified" value="0">
    </div>
</div>
