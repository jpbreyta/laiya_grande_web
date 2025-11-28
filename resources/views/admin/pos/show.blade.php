@extends('admin.layouts.app')

@php
    $pageTitle = 'Receipt Details';
@endphp

@section('content')
    <section class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <a href="{{ route('admin.pos.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Transactions
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-700 text-white px-6 py-4">
                    <h1 class="text-2xl font-bold">LAIYA GRANDE RESORT</h1>
                    <p class="text-emerald-100">Guest Services Receipt</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600">Receipt Number</p>
                            <p class="font-mono font-bold text-lg">{{ $transaction->receipt_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date & Time</p>
                            <p class="font-semibold">{{ $transaction->transaction_date->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Guest Name</p>
                            <p class="font-semibold">{{ optional($transaction->guestStay)->guest_name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Room</p>
                            <p class="font-semibold">{{ optional(optional($transaction->guestStay)->room)->name ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold mb-4">Items</h3>
                    <table class="w-full mb-6">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-left py-2">Item</th>
                                <th class="text-center py-2">Type</th>
                                <th class="text-center py-2">Qty</th>
                                <th class="text-right py-2">Price</th>
                                <th class="text-right py-2">Discount</th>
                                <th class="text-right py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->posItems as $item)
                                <tr class="border-b border-gray-200">
                                    <td class="py-3">{{ $item->item_name }}</td>
                                    <td class="py-3 text-center">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                            {{ ucfirst($item->item_type) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">{{ $item->quantity }}</td>
                                    <td class="py-3 text-right">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="py-3 text-right text-red-600">
                                        @if ($item->discount > 0)
                                            -₱{{ number_format($item->discount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-3 text-right font-semibold">₱{{ number_format($item->total_amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="border-t-2 border-gray-300 pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-700">Subtotal:</span>
                            <span class="font-semibold">₱{{ number_format($transaction->subtotal, 2) }}</span>
                        </div>
                        @if ($transaction->discount > 0)
                            <div class="flex justify-between mb-2 text-red-600">
                                <span>Discount:</span>
                                <span class="font-semibold">-₱{{ number_format($transaction->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-xl font-bold border-t-2 border-gray-300 pt-2 mt-2">
                            <span>TOTAL:</span>
                            <span class="text-green-600">₱{{ number_format($transaction->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 text-center text-gray-600">
                        <p>Thank you for your patronage!</p>
                    </div>

                    <div class="mt-6 flex justify-center gap-4">
                        <button onclick="window.print()"
                            class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                            Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
