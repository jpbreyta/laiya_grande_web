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
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Payment Details</h1>
                    <p class="text-sm text-slate-500 mt-1">View complete payment transaction information</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.payments.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 px-6 py-3 text-sm font-semibold shadow-sm hover:shadow-md transform hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                        <i class="fas fa-arrow-left"></i>
                        Back to Payments
                    </a>
                </div>
            </div>

            <!-- Payment Information Card -->
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 bg-gradient-to-r from-teal-50 to-emerald-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 flex items-center">
                                <i class="fas fa-money-bill-wave mr-3 text-teal-600"></i>
                                Payment Information
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Reference ID: {{ $payment->reference_id }}</p>
                        </div>
                        @php
                            $statusConfig = [
                                'verified' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-500', 'icon' => 'fa-check-circle', 'border' => 'border-emerald-200'],
                                'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'dot' => 'bg-amber-500', 'icon' => 'fa-clock', 'border' => 'border-amber-200'],
                                'rejected' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'dot' => 'bg-rose-500', 'icon' => 'fa-times-circle', 'border' => 'border-rose-200'],
                            ];
                            $config = $statusConfig[$payment->status] ?? $statusConfig['pending'];
                        @endphp
                        <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
                            <span class="h-2 w-2 rounded-full {{ $config['dot'] }}"></span>
                            <i class="fas {{ $config['icon'] }}"></i>
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Payment Details -->
                        <div class="space-y-4">
                            <h3 class="text-base font-semibold text-slate-900 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-teal-600"></i>
                                Transaction Details
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-hashtag text-teal-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Reference ID</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ $payment->reference_id }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-peso-sign text-emerald-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Amount Paid</p>
                                            <p class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">
                                                ₱{{ number_format($payment->amount_paid ?? 0, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-amber-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Payment Date</p>
                                            <p class="text-sm font-semibold text-slate-900">
                                                {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y h:i A') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-mobile-alt text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Payment Method</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer & Booking Details -->
                        <div class="space-y-4">
                            <h3 class="text-base font-semibold text-slate-900 flex items-center">
                                <i class="fas fa-user mr-2 text-teal-600"></i>
                                Customer & Booking Information
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user text-purple-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Customer Name</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ $payment->customer_name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-phone text-cyan-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-slate-500">Contact Number</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ $payment->contact_number }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($payment->booking)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-calendar-check text-teal-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-slate-500">Booking ID</p>
                                                <p class="text-sm font-semibold text-slate-900">#{{ $payment->booking_id }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($payment->booking->room)
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-door-open text-indigo-600"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-slate-500">Room</p>
                                                    <p class="text-sm font-semibold text-slate-900">{{ $payment->booking->room->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Verification Information -->
                    @if($payment->verified_at)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <h3 class="text-base font-semibold text-slate-900 flex items-center mb-4">
                                <i class="fas fa-shield-check mr-2 text-emerald-600"></i>
                                Verification Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center space-x-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-check-circle text-emerald-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-emerald-600">Verified At</p>
                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ \Carbon\Carbon::parse($payment->verified_at)->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                @if($payment->verifier)
                                    <div class="flex items-center space-x-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-user-shield text-emerald-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs text-emerald-600">Verified By</p>
                                            <p class="text-sm font-semibold text-slate-900">{{ $payment->verifier->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($payment->notes)
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <h3 class="text-base font-semibold text-slate-900 flex items-center mb-3">
                                <i class="fas fa-sticky-note mr-2 text-teal-600"></i>
                                Notes
                            </h3>
                            <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                                <p class="text-sm text-slate-700">{{ $payment->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Additional Actions Card -->
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900 flex items-center">
                        <i class="fas fa-cog mr-3 text-teal-600"></i>
                        Actions
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap items-center gap-3">
                        @if($payment->booking)
                            <a href="{{ route('admin.booking.show', $payment->booking_id) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-teal-100 bg-teal-50 px-4 py-2.5 text-sm font-semibold text-teal-600 transition hover:bg-teal-100 hover:shadow-sm">
                                <i class="fas fa-calendar-check"></i>
                                View Booking
                            </a>
                        @endif
                        <a href="{{ route('admin.payments.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:shadow-sm">
                            <i class="fas fa-list"></i>
                            All Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

