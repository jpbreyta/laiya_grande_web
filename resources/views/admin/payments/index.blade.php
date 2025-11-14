@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Top Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Payment Management</h1>
                    <p class="text-sm text-slate-500 mt-1">View and manage all payment transactions</p>
                </div>
                <a href="{{ route('admin.test-payment-ocr') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-camera"></i>
                    Test OCR
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Payments Card -->
                <div class="bg-gradient-to-br from-[#5f9ea0] to-[#99e6b3] rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-100 text-sm font-medium mb-1">Total Payments</p>
                            <p class="text-3xl font-bold text-white">{{ $payments->total() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Verified Payments Card -->
                <div class="bg-gradient-to-br from-[#5f9ea0] to-[#6495ed] rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-100 text-sm font-medium mb-1">Verified</p>
                            <p class="text-3xl font-bold text-white">{{ $payments->where('status', 'verified')->count() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-check-circle text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments Card -->
                <div class="bg-gradient-to-br from-[#5f9ea0] to-[#b0e0e6] rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-cyan-100 text-sm font-medium mb-1">Pending</p>
                            <p class="text-3xl font-bold text-white">{{ $payments->where('status', 'pending')->count() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-clock text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium mb-1">Total Revenue</p>
                            <p class="text-2xl font-bold text-white">₱{{ number_format($payments->where('status', 'verified')->sum('amount_paid') ?? 0, 2) }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-peso-sign text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments Table Card -->
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">All Payments</h2>
                        <p class="text-sm text-slate-500">View and manage all payment transactions.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-teal-600">
                        <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                        {{ $payments->total() }} {{ Str::plural('payment', $payments->total()) }}
                    </div>
                </div>

                @if ($payments->isEmpty())
                    <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                        <span class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-teal-500">
                            <i class="fas fa-money-bill-wave text-xl"></i>
                        </span>
                        <p class="text-base font-semibold text-slate-600">No payments found</p>
                        <p class="mt-1 text-sm text-slate-500">Payment records will appear here once transactions are processed.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Reference ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Customer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Booking</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Payment Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Method</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-hashtag text-teal-600 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">{{ $payment->reference_id }}</p>
                                                    <p class="text-xs text-slate-500">ID: {{ $payment->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $payment->customer_name }}</p>
                                                <p class="text-xs text-slate-500 mt-1">
                                                    <i class="fas fa-phone text-xs mr-1"></i>{{ $payment->contact_number }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-calendar-check text-slate-400 text-xs"></i>
                                                <span class="text-sm text-slate-700">
                                                    Booking #{{ $payment->booking_id }}
                                                </span>
                                            </div>
                                            @if($payment->booking && $payment->booking->room)
                                                <p class="text-xs text-slate-500 mt-1">
                                                    <i class="fas fa-door-open text-xs mr-1"></i>{{ $payment->booking->room->name ?? 'N/A' }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">
                                                ₱{{ number_format($payment->amount_paid ?? 0, 2) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-clock text-slate-400 text-xs"></i>
                                                <span class="text-sm text-slate-700">
                                                    {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}
                                                </span>
                                            </div>
                                            @if($payment->payment_date)
                                                <p class="text-xs text-slate-500 mt-1">
                                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('h:i A') }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold bg-blue-50 text-blue-600">
                                                <i class="fas fa-mobile-alt text-xs"></i>
                                                {{ ucfirst($payment->payment_method ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusConfig = [
                                                    'verified' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-500', 'icon' => 'fa-check-circle'],
                                                    'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'dot' => 'bg-amber-500', 'icon' => 'fa-clock'],
                                                    'rejected' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'dot' => 'bg-rose-500', 'icon' => 'fa-times-circle'],
                                                ];
                                                $config = $statusConfig[$payment->status] ?? $statusConfig['pending'];
                                            @endphp
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                                <span class="h-2 w-2 rounded-full {{ $config['dot'] }}"></span>
                                                <i class="fas {{ $config['icon'] }} text-xs"></i>
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a href="{{ route('admin.payments.show', $payment->id) }}"
                                                    class="inline-flex items-center gap-2 rounded-xl border border-teal-100 bg-teal-50 px-3 py-1.5 text-xs font-semibold text-teal-600 transition hover:bg-teal-100 hover:shadow-sm">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($payments->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100">
                            {{ $payments->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>
@endsection

