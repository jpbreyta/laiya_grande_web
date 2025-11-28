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
                    <p class="text-sm text-blue-600">Transactions are pulled automatically from the on-site POS terminal.</p>
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
                                <th class="py-3 px-4">Receipt #</th>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4">Guest</th>
                                <th class="py-3 px-4">Room</th>
                                <th class="py-3 px-4 text-center">Items</th>
                                <th class="py-3 px-4 text-right">Subtotal</th>
                                <th class="py-3 px-4 text-right">Discount</th>
                                <th class="py-3 px-4 text-right">Total</th>
                                <th class="py-3 px-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transactions as $transaction)
                                <tr
                                    class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200">
                                    <td class="py-3 px-4 text-gray-900 font-mono text-sm">
                                        {{ $transaction->receipt_number }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-700 font-medium">
                                        {{ $transaction->transaction_date->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-900 font-semibold">
                                        {{ optional($transaction->guestStay)->guest_name ?? 'Unknown' }}
                                    </td>
                                    <td class="py-3 px-4 text-gray-700">
                                        {{ optional(optional($transaction->guestStay)->room)->name ?? '-' }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                            {{ $transaction->items_count }} items
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-semibold text-gray-700">
                                        ₱{{ number_format($transaction->subtotal, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-semibold text-red-600">
                                        @if ($transaction->discount > 0)
                                            -₱{{ number_format($transaction->discount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td
                                        class="py-3 px-4 text-right font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">
                                        ₱{{ number_format($transaction->total, 2) }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('admin.pos.show', $transaction->id) }}"
                                            class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-bold rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                                            View Receipt
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-6 px-4 text-center text-gray-500">
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
