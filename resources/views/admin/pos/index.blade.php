@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-slate-100 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <header class="space-y-2 text-center lg:text-left">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Retail Desk</p>
                        <h1 class="text-4xl font-bold text-slate-900">Laiya Grande POS System</h1>
                        <p class="text-base text-slate-500">Keep every walk-in sale quick, tidy, and accountable.</p>
                    </div>
                    <div class="flex items-center lg:justify-end">
                        <a href="{{ route('admin.pos.transactions') }}"
                            class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:border-slate-300">
                            <i class="fas fa-history text-slate-500"></i>
                            View Transactions
                        </a>
                    </div>
                </div>
            </header>

            <div class="grid gap-8 lg:grid-cols-1">
                <!-- POS Workspace -->
                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm">
            <div class="mb-6">
                            <label for="search-input" class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Search Items</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input id="search-input" type="text" placeholder="Search items..."
                                    class="w-full rounded-2xl border border-slate-200 bg-white/80 py-3 pl-12 pr-4 text-sm text-slate-700 shadow-inner focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200" />
                </div>
            </div>

                        <div class="flex flex-wrap items-end gap-4">
                            <div class="flex-1 min-w-[150px]">
                                <label for="pos-item-name" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Item Name</label>
                                <input id="pos-item-name" type="text"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200" />
                            </div>
                            <div class="flex-1 min-w-[150px]">
                                <label for="pos-item-price" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Item Price</label>
                                <input id="pos-item-price" type="number" min="0" step="0.01"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200" />
                            </div>
                            <div class="flex-1 min-w-[150px]">
                                <label for="pos-item-quantity" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Item Quantity</label>
                                <input id="pos-item-quantity" type="number" min="1" step="1" value="1"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200" />
                            </div>
                            <div class="flex-1 min-w-[150px]">
                                <label for="pos-item-discount" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Add Discount (%)</label>
                                <input id="pos-item-discount" type="number" min="0" max="100" step="0.01"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-800 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-200" />
                            </div>
                            <div class="flex flex-wrap items-stretch gap-3 w-full sm:w-auto">
                                <button id="add-to-receipt"
                                    class="flex-1 whitespace-nowrap rounded-2xl bg-teal-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-200 transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-200">
                                    Add to Receipt
                                </button>
                                <button id="clear-form"
                                    class="flex-1 whitespace-nowrap rounded-2xl border border-sky-200 bg-sky-50/80 px-6 py-3 text-sm font-semibold text-sky-700 shadow-inner transition hover:bg-white focus:outline-none focus:ring-2 focus:ring-sky-200">
                                    Clear
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-lg">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-slate-400">Receipt</p>
                                <h2 class="text-2xl font-semibold text-slate-900">Transaction Details</h2>
                        </div>
                            <button id="clear-receipt" class="rounded-2xl border border-slate-200 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-500 transition hover:border-slate-300 hover:text-slate-700">
                                Clear Receipt
                                        </button>
                        </div>
                        <div id="receipt-display" class="h-[520px] rounded-2xl border border-dashed border-slate-200 bg-white text-slate-700 overflow-y-auto p-6">
                            <div id="receipt-placeholder" class="flex h-full flex-col items-center justify-center text-center text-slate-400">
                                <i class="fas fa-receipt text-4xl mb-3"></i>
                                <p class="text-sm font-medium">No items yet</p>
                                <p class="text-xs">Add products to preview the printed slip.</p>
                            </div>
                            <div id="receipt-entries" class="space-y-4 hidden"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            const $receiptEntries = $('#receipt-entries');
            const $receiptPlaceholder = $('#receipt-placeholder');

            function toggleReceiptEmptyState(isEmpty) {
                if (isEmpty) {
                    $receiptPlaceholder.removeClass('hidden').addClass('flex');
                    $receiptEntries.addClass('hidden').empty();
                    } else {
                    $receiptPlaceholder.addClass('hidden').removeClass('flex');
                    $receiptEntries.removeClass('hidden');
                }
            }

            function formatCurrency(value) {
                return parseFloat(value).toFixed(2);
            }

            function buildReceiptRow(data) {
                const hideOriginal = data.discount <= 0 ? 'hidden' : '';
                return `
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/90 p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">${data.name}</p>
                                <p class="text-xs text-slate-500">Qty: ${data.quantity} • Discount: ${formatCurrency(data.discount)}%</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400 line-through ${hideOriginal}">₱${data.originalTotal}</p>
                                <p class="text-lg font-bold text-slate-900">₱${data.total}</p>
                            </div>
                        </div>
                    </div>
                `;
            }

            $('#add-to-receipt').on('click', function() {
                const name = $('#pos-item-name').val().trim();
                const price = parseFloat($('#pos-item-price').val());
                const quantity = parseInt($('#pos-item-quantity').val(), 10);
                const discount = parseFloat($('#pos-item-discount').val()) || 0;

                if (!name || isNaN(price) || price <= 0 || isNaN(quantity) || quantity <= 0) {
                    alert('Please provide a valid item name, price, and quantity.');
                    return;
                }

                const originalTotal = price * quantity;
                const discountedTotal = originalTotal - originalTotal * (discount / 100);

                toggleReceiptEmptyState(false);
                $receiptEntries.append(buildReceiptRow({
                    name,
                    quantity,
                    discount,
                    originalTotal: formatCurrency(originalTotal),
                    total: formatCurrency(discountedTotal)
                }));
            });

            $('#clear-form').on('click', function() {
                $('#pos-item-name').val('');
                $('#pos-item-price').val('');
                $('#pos-item-quantity').val(1);
                $('#pos-item-discount').val('');
            });

            $('#clear-receipt').on('click', function() {
                toggleReceiptEmptyState(true);
            });

        });
    </script>
@endsection
