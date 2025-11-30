@php
    $cartTotal = collect(session('cart', []))->sum(fn($item) => ($item['room_price'] ?? 0) * ($item['quantity'] ?? 0));
@endphp

<div class="bg-white shadow-xl rounded-3xl overflow-hidden border border-slate-100">
    <div class="px-8 py-6 border-b border-slate-100 bg-teal-50/30 flex justify-between items-center">
        <h5 class="text-xl font-bold text-teal-900 flex items-center gap-3">
            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-600 text-sm">
                <i class="fas fa-wallet"></i>
            </span>
            Payment
        </h5>
    </div>
    <div class="p-8">
        <div class="grid md:grid-cols-12 gap-8">
            {{-- QR Code --}}
            <div
                class="md:col-span-4 flex flex-col items-center justify-center p-6 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                <div class="bg-white p-2 rounded-xl shadow-sm mb-3">
                    <img src="{{ asset('storage/qr_codes/resort_qr.png') }}" alt="Scan to Pay"
                        class="w-40 h-40 object-contain">
                </div>
                <p class="text-sm font-semibold text-teal-800">Scan QR to Pay</p>
            </div>

            {{-- Payment Details --}}
            <div class="md:col-span-8 space-y-6">
                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                    <div class="flex flex-col">
                        <span class="text-yellow-800 font-medium">Total Amount Due</span>
                        <span class="text-xs text-yellow-600" id="nightsInfo">1 night(s)</span>
                    </div>
                    <span id="amountDueReserve" class="text-xl font-bold text-teal-900">
                        PHP {{ number_format($cartTotal, 2) }}
                    </span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Mode of Payment *</label>
                    <div class="relative">
                        <select name="payment_method" id="paymentMethodSelect" required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none appearance-none bg-white">
                            <option value="" disabled selected>Select a payment method</option>
                            <option value="gcash">GCash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                {{-- Bank Transfer Fields --}}
                <div id="bankFieldsReserve" class="hidden space-y-4 p-5 bg-slate-50 rounded-xl border border-slate-200">
                    <h6 class="text-sm font-bold text-teal-800 border-b border-slate-200 pb-2 mb-2">
                        Bank Transfer Details
                    </h6>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-slate-500 font-bold mb-1 block">Bank Name *</label>
                            <input type="text" name="bank_name"
                                class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="e.g., BDO">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-bold mb-1 block">Account Name *</label>
                            <input type="text" name="bank_account_name"
                                class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Account Holder">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-bold mb-1 block">Account Number *</label>
                            <input type="text" name="bank_account_number"
                                class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="0000-0000-0000">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-bold mb-1 block">Reference # *</label>
                            <input type="text" name="bank_reference"
                                class="w-full rounded-lg border-gray-300 text-sm py-2 px-3 focus:ring-teal-500 focus:border-teal-500"
                                placeholder="Transaction Ref #">
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-xl">
                    <ul class="list-disc pl-5 text-xs text-blue-800 space-y-1">
                        <li>Use your email as Reference/Note in your transfer.</li>
                        <li>Reservations are held for <strong>24 hours</strong> pending payment.</li>
                    </ul>
                </div>

                <div>
                    <label for="payment_proof" class="block text-xs font-bold text-slate-500 uppercase mb-2">
                        Upload Payment Proof *
                    </label>
                    <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-xl cursor-pointer">
                </div>
            </div>
        </div>
    </div>
</div>
