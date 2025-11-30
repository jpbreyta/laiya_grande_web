@php
    $reservation = session('reservation_data', []);
@endphp

<div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-100">
    <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
        <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                <i class="fas fa-user"></i>
            </span>
            Personal Information
        </h5>
    </div>
    <div class="p-8">
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase">Full Name</p>
                <p class="text-lg font-semibold text-teal-900">
                    {{ $reservation['first_name'] ?? 'N/A' }} {{ $reservation['last_name'] ?? 'N/A' }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase">Email Address</p>
                <p class="text-lg font-semibold text-teal-900 flex items-center gap-2">
                    <i class="fas fa-envelope text-teal-600 text-sm"></i>
                    {{ $reservation['email'] ?? 'N/A' }}
                </p>
                <span class="inline-flex items-center gap-1 text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fas fa-check-circle"></i> Verified
                </span>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase">Phone Number</p>
                <p class="text-lg font-semibold text-teal-900 flex items-center gap-2">
                    <i class="fas fa-phone text-teal-600 text-sm"></i>
                    {{ $reservation['phone'] ?? 'N/A' }}
                </p>
            </div>
            @if (!empty($reservation['special_requests']))
                <div class="space-y-1">
                    <p class="text-xs font-bold text-slate-500 uppercase">Special Requests</p>
                    <p class="text-sm text-slate-700 italic">
                        "{{ $reservation['special_requests'] }}"
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
