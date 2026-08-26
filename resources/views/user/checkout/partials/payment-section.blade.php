<section class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg shadow-teal-900/5">
    <header class="border-b border-slate-100 bg-teal-50/40 px-6 py-5 sm:px-8">
        <h2 class="flex items-center gap-3 text-xl font-bold text-teal-900">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-100 text-teal-700">
                <i class="fas fa-wallet" aria-hidden="true"></i>
            </span>
            Payment
        </h2>
    </header>

    <div class="grid gap-6 p-6 sm:p-8 md:grid-cols-12">
        <div class="md:col-span-4">
            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5 text-center">
                <img src="{{ asset('storage/qr_codes/resort_qr.png') }}" alt="Resort payment QR code"
                    class="mx-auto h-40 w-40 rounded-xl bg-white object-contain p-2" loading="lazy" decoding="async">
                <p class="mt-3 text-sm font-semibold text-teal-900">Scan to pay</p>
                <p class="text-xs text-slate-500">Use an approved e-wallet or bank application.</p>
            </div>
        </div>

        <div class="space-y-5 md:col-span-8">
            <div class="flex items-center justify-between rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <div>
                    <p class="text-sm font-medium text-amber-800">Total amount due</p>
                    <p class="text-xs text-amber-700"><span data-nights>{{ $nights }}</span> night(s)</p>
                </div>
                <strong class="text-xl text-teal-950" data-total>PHP {{ number_format($cartTotal, 2) }}</strong>
            </div>

            <label class="block">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Payment method</span>
                <select name="payment_method" required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                    <option value="">Select payment method</option>
                    <option value="gcash" @selected(old('payment_method') === 'gcash')>GCash</option>
                    <option value="paymaya" @selected(old('payment_method') === 'paymaya')>PayMaya</option>
                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank transfer</option>
                </select>
            </label>

            <label class="block">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Payment proof</span>
                <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" required
                    class="block w-full rounded-xl border border-slate-300 bg-white text-sm file:mr-4 file:border-0 file:bg-teal-700 file:px-4 file:py-3 file:font-semibold file:text-white hover:file:bg-teal-800"
                    data-payment-proof>
                <p class="mt-2 text-xs text-slate-500">JPG, PNG, or PDF. Maximum file size: 5 MB.</p>
                <p class="mt-1 text-xs font-medium text-teal-700" data-file-name></p>
            </label>
        </div>
    </div>
</section>
