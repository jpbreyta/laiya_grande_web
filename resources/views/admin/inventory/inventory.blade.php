@extends('admin.layouts.app')

@php
    $pageTitle = 'Inventory Management';
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
                'button' => [
                    'type' => 'add',
                    'text' => 'Add New Item',
                    'route' => 'admin.inventory',
                    'color' => 'bg-gradient-to-r from-emerald-500 to-green-600',
                    'icon' => 'fa-plus',
                ],
            ])

            @if (session('success'))
                <div
                    class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 text-blue-700 px-4 py-3 rounded-lg shadow-sm mb-6">
                    {{ session('info') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-emerald-500 to-emerald-700 text-left text-white uppercase text-xs font-bold tracking-wider">
                                <th class="py-3 px-4">
                                    <input type="checkbox"
                                        class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
                                </th>
                                <th class="py-3 px-4">Item Name</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Quantity</th>
                                <th class="py-3 px-4">Unit</th>
                                <th class="py-3 px-4">Unit Price</th>
                                <th class="py-3 px-4">Total Value</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Last Updated</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $inventoryItems = [
                                    [
                                        'id' => 1,
                                        'name' => 'Towels',
                                        'category' => 'Linens',
                                        'quantity' => 150,
                                        'unit' => 'pcs',
                                        'unit_price' => 250.0,
                                        'status' => 'in-stock',
                                        'updated_at' => now(),
                                    ],
                                    [
                                        'id' => 2,
                                        'name' => 'Shampoo',
                                        'category' => 'Toiletries',
                                        'quantity' => 25,
                                        'unit' => 'bottles',
                                        'unit_price' => 150.0,
                                        'status' => 'low-stock',
                                        'updated_at' => now()->subDays(2),
                                    ],
                                    [
                                        'id' => 3,
                                        'name' => 'Bed Sheets',
                                        'category' => 'Linens',
                                        'quantity' => 0,
                                        'unit' => 'sets',
                                        'unit_price' => 800.0,
                                        'status' => 'out-of-stock',
                                        'updated_at' => now()->subDays(5),
                                    ],
                                    [
                                        'id' => 4,
                                        'name' => 'Pillows',
                                        'category' => 'Linens',
                                        'quantity' => 200,
                                        'unit' => 'pcs',
                                        'unit_price' => 350.0,
                                        'status' => 'in-stock',
                                        'updated_at' => now()->subDay(),
                                    ],
                                    [
                                        'id' => 5,
                                        'name' => 'Soap',
                                        'category' => 'Toiletries',
                                        'quantity' => 80,
                                        'unit' => 'bars',
                                        'unit_price' => 50.0,
                                        'status' => 'in-stock',
                                        'updated_at' => now(),
                                    ],
                                    [
                                        'id' => 6,
                                        'name' => 'Toilet Paper',
                                        'category' => 'Toiletries',
                                        'quantity' => 15,
                                        'unit' => 'rolls',
                                        'unit_price' => 30.0,
                                        'status' => 'low-stock',
                                        'updated_at' => now()->subDays(3),
                                    ],
                                ];
                            @endphp

                            @forelse ($inventoryItems as $item)
                                <tr class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-indigo-50/20 transition-all duration-200"
                                    style="cursor: pointer;">
                                    <td class="py-3 px-4">
                                        <input type="checkbox"
                                            class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded"
                                            onclick="event.stopPropagation();">
                                    </td>
                                    <td class="py-3 px-4 font-medium text-gray-900">{{ $item['name'] }}</td>
                                    <td class="py-3 px-4 text-gray-700">{{ $item['category'] }}</td>
                                    <td class="py-3 px-4 text-gray-700 font-semibold">{{ $item['quantity'] }}</td>
                                    <td class="py-3 px-4 text-gray-700">{{ $item['unit'] }}</td>
                                    <td class="py-3 px-4 text-gray-700">₱{{ number_format($item['unit_price'], 2) }}</td>
                                    <td
                                        class="py-3 px-4 font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">
                                        ₱{{ number_format($item['quantity'] * $item['unit_price'], 2) }}</td>

                                    <td class="py-3 px-4">
                                        @php
                                            $statusStyles = [
                                                'in-stock' =>
                                                    'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200',
                                                'low-stock' =>
                                                    'bg-gradient-to-r from-amber-100 to-yellow-100 text-amber-800 border border-amber-200',
                                                'out-of-stock' =>
                                                    'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200',
                                            ];
                                            $statusDots = [
                                                'in-stock' => 'bg-green-500',
                                                'low-stock' => 'bg-amber-500',
                                                'out-of-stock' => 'bg-red-500',
                                            ];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-sm {{ $statusStyles[$item['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                            <span
                                                class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $statusDots[$item['status']] ?? 'bg-gray-500' }}"></span>
                                            {{ ucfirst(str_replace('-', ' ', $item['status'])) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-gray-700">
                                        {{ \Carbon\Carbon::parse($item['updated_at'])->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-6 px-4 text-center text-gray-500">
                                        No inventory items found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
