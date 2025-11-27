@extends('admin.layouts.app')

@php
    $pageTitle = 'Point of Sale Transactions';
@endphp

@section('content')
    <section class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            @include('admin.components.table-controls', [
                'more' => [
                    ['label' => 'Export as CSV', 'route' => '#'],
                    ['label' => 'Export as Excel', 'route' => '#'],
                    ['label' => 'Export as PDF', 'route' => '#'],
                ],
                'title' => $pageTitle,
                'search' => true,
                'entries' => true,
            ])

            <div
                class="bg-gradient-to-r from-sky-50 to-blue-50 border border-blue-100 text-blue-700 px-4 py-3 rounded-lg shadow-sm mb-6 flex items-center gap-3">
                <span
                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-semibold">i</span>
                <div>
                    <p class="font-semibold">Synced from Java POS</p>
                    <p class="text-sm text-blue-600">Transactions are pulled automatically from the on-site POS
                        terminal.</p>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-emerald-500 to-emerald-700 text-left text-white uppercase text-xs font-bold tracking-wider">
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4">Guest</th>
                                <th class="py-3 px-4">Room</th>
                                <th class="py-3 px-4">Item</th>
                                <th class="py-3 px-4">Type</th>
                                <th class="py-3 px-4">Quantity</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transactions as $transaction)
                                <tr
                                    class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                    <td class="py-3 px-4 text-gray-700 font-medium">
                                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-900 font-semibold">
                                        {{ optional($transaction->guestStay)->guest_name ?? 'Guest Checkout/Unknown' }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">
                                        {{ optional(optional($transaction->guestStay)->room)->name ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">
                                        {{ $transaction->item_name }}
                                    </td>
                                    <td class="py-3 px-4">
                                        @php
                                            $typeStyles = [
                                                'rental' => 'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200',
                                                'food' => 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200',
                                                'service' => 'bg-gradient-to-r from-purple-100 to-fuchsia-100 text-purple-800 border border-purple-200',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $typeStyles[$transaction->item_type] ?? 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                            {{ ucfirst($transaction->item_type) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">{{ $transaction->quantity }}</td>
                                    <td
                                        class="py-3 px-4 text-right font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">
                                        ₱{{ number_format($transaction->total_amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 px-4 text-center text-gray-500">
                                        No transactions found from Java POS yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-4 pb-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection