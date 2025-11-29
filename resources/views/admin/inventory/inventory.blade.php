@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Top Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Inventory Management</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage your inventory items and stock levels</p>
                </div>
                <div class="flex gap-2">
                    <div class="relative">
                        <button id="exportDropdownBtn" type="button"
                            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow flex items-center gap-2">
                            <i class="fas fa-download"></i> Export <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="exportDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50 border border-gray-100">
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-md">
                                <i class="fas fa-file-csv mr-2"></i>Export CSV
                            </a>
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-md">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </a>
                        </div>
                    </div>
                    <a href="#"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                        <i class="fas fa-plus"></i>
                        Add New Item
                    </a>
                </div>
            </div>

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

            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Inventory Overview</h2>
                        <p class="text-sm text-slate-500">All inventory items with stock levels and values.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs font-bold tracking-wider">
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">Item Name</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Quantity</th>
                                <th class="py-3 px-4">Unit Price</th>
                                <th class="py-3 px-4">Total Value</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Last Updated</th>
                                <th class="py-3 px-4 text-center">Action</th>
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
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-xs font-semibold text-gray-700">{{ $item['id'] }}</td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $item['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $item['unit'] }}</div>
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-700">{{ $item['category'] }}</td>
                                    <td class="py-3 px-4 text-sm font-semibold text-gray-700">{{ $item['quantity'] }} {{ $item['unit'] }}</td>
                                    <td class="py-3 px-4 text-sm text-gray-700">₱{{ number_format($item['unit_price'], 2) }}</td>
                                    <td class="py-3 px-4 font-bold text-emerald-600">
                                        ₱{{ number_format($item['quantity'] * $item['unit_price'], 2) }}</td>

                                    <td class="py-3 px-4">
                                        @php
                                            $colors = [
                                                'in-stock' => 'bg-green-100 text-green-800',
                                                'low-stock' => 'bg-yellow-100 text-yellow-800',
                                                'out-of-stock' => 'bg-red-100 text-red-800',
                                            ];
                                            $color = $colors[$item['status']] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ ucfirst(str_replace('-', ' ', $item['status'])) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($item['updated_at'])->format('M d, Y') }}</td>

                                    <td class="py-3 px-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                            <p>No inventory items found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script>
        const exportDropdownBtn = document.getElementById('exportDropdownBtn');
        const exportDropdown = document.getElementById('exportDropdown');

        if (exportDropdownBtn && exportDropdown) {
            exportDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                exportDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!exportDropdown.contains(e.target) && e.target !== exportDropdownBtn) {
                    exportDropdown.classList.add('hidden');
                }
            });
        }
    </script>
@endsection
