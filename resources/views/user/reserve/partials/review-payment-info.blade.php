@php
    $reservation = session('reservation_data', []);
    $paymentMethod = $reservation['payment_method'] ?? 'N/A';
@endphp

<div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-100">
    <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30">
        <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                <i class="fas fa-wallet"></i>
            </span>
            Payment Information
        </h5>
    </div>
    <div class="p-8">
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase">Payment Method</p>
                <p class="text-lg font-semibold text-teal-900 flex items-center gap-2">
                    @if ($paymentMethod === 'gcash')
                        <i class="fas fa-mobile-alt text-teal-600"></i>
                        GCash
                    @elseif($paymentMethod === 'bank_transfer')
                        <i class="fas fa-university text-teal-600"></i>
                        Bank Transfer
                    @else
                        {{ $paymentMethod }}
                    @endif
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase">Payment Status</p>
                <span
                    class="inline-flex items-center gap-2 text-sm font-semibold text-yellow-700 bg-yellow-50 px-3 py-2 rounded-lg border border-yellow-200">
                    <i class="fas fa-clock"></i>
                    Pending Verification
                </span>
            </div>
        </div>

        @if ($paymentMethod === 'bank_transfer' && !empty($reservation['bank_name']))
            <div class="mt-6 pt-6 border-t border-slate-200">
                <p class="text-xs font-bold text-slate-500 uppercase mb-4">Bank Transfer Details</p>
                <div class="grid md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl">
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500">Bank Name</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $reservation['bank_name'] ?? 'N/A' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500">Account Name</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $reservation['bank_account_name'] ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500">Account Number</p>
                        <p class="text-sm font-semibold text-slate-900">
                            {{ $reservation['bank_account_number'] ?? 'N/A' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500">Reference Number</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $reservation['bank_reference'] ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-6 pt-6 border-t border-slate-200">
            <div class="flex items-center gap-3 text-sm text-slate-600">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>Payment proof has been uploaded and is under review</span>
            </div>
        </div>

        <div class="mt-6 bg-blue-50 p-4 rounded-xl border border-blue-200">
            <h6 class="text-sm font-bold text-blue-900 mb-2 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>
                What Happens Next?
            </h6>
            <ul class="text-xs text-blue-800 space-y-2">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                    <span>Our team will verify your payment within 2-4 hours</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                    <span>You'll receive a confirmation email once approved</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-blue-600 mt-0.5"></i>
                    <span>Your booking voucher will be sent to your email</span>
                </li>
            </ul>
        </div>
    </div>
</div>
