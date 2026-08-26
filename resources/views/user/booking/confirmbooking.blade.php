@extends('user.layouts.app')

@section('content')
<section class="min-h-screen bg-slate-50 py-12 sm:py-16" data-confirm-page data-csrf-token="{{ csrf_token() }}" data-success-url="{{ route('home') }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <header class="mb-9 text-center">
            <p class="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-teal-700">Final Review</p>
            <h1 class="text-3xl font-bold text-teal-950 sm:text-4xl">Review Your Booking</h1>
            <p class="mt-3 text-slate-600">Confirm the information below before creating the booking.</p>
        </header>

        @include('user.checkout.partials.progress', ['activeStep' => 2])
        @include('user.checkout.partials.alerts')

        <div class="grid gap-8 lg:grid-cols-12">
            <main class="space-y-6 lg:col-span-8">
                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg shadow-teal-900/5 sm:p-8">
                    <h2 class="mb-5 text-xl font-bold text-teal-950">Guest Information</h2>
                    <dl class="grid gap-5 sm:grid-cols-2">
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Name</dt><dd class="mt-1 text-lg text-teal-950">{{ $request->first_name }} {{ $request->last_name }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Email</dt><dd class="mt-1 break-all text-lg text-teal-950">{{ $request->email }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Phone</dt><dd class="mt-1 text-lg text-teal-950">{{ $request->phone }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Guests</dt><dd class="mt-1 text-lg text-teal-950">{{ $request->guests }}</dd></div>
                    </dl>
                </section>

                <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg shadow-teal-900/5 sm:p-8">
                    <h2 class="mb-5 text-xl font-bold text-teal-950">Stay and Payment</h2>
                    <dl class="grid gap-5 sm:grid-cols-2">
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Check-in</dt><dd class="mt-1 text-lg text-teal-950">{{ \Carbon\Carbon::parse($request->check_in)->format('M d, Y') }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Check-out</dt><dd class="mt-1 text-lg text-teal-950">{{ \Carbon\Carbon::parse($request->check_out)->format('M d, Y') }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Payment method</dt><dd class="mt-1 text-lg capitalize text-teal-950">{{ str_replace('_', ' ', $request->payment_method) }}</dd></div>
                        <div><dt class="text-xs font-bold uppercase text-slate-500">Payment proof</dt><dd class="mt-1 text-lg text-teal-950">{{ $paymentProofName ?? basename($paymentProofPath) }}</dd></div>
                    </dl>
                    @if ($request->input('special_request', $request->input('special_requests')))
                        <div class="mt-5 rounded-2xl bg-amber-50 p-4 text-sm text-amber-900">
                            <strong>Special request:</strong> {{ $request->input('special_request', $request->input('special_requests')) }}
                        </div>
                    @endif
                </section>

                <form action="{{ route('user.booking.confirm') }}" method="POST" class="space-y-5" data-ajax-form>
                    @csrf
                    <input type="hidden" name="first_name" value="{{ $request->first_name }}">
                    <input type="hidden" name="last_name" value="{{ $request->last_name }}">
                    <input type="hidden" name="email" value="{{ $request->email }}">
                    <input type="hidden" name="phone" value="{{ $request->phone }}">
                    <input type="hidden" name="guests" value="{{ $request->guests }}">
                    <input type="hidden" name="check_in" value="{{ session('booking_check_in', $request->check_in) }}">
                    <input type="hidden" name="check_out" value="{{ session('booking_check_out', $request->check_out) }}">
                    <input type="hidden" name="special_request" value="{{ $request->input('special_request', $request->input('special_requests')) }}">
                    <input type="hidden" name="payment_method" value="{{ $request->payment_method }}">
                    <input type="hidden" name="payment_proof_temp" value="{{ $paymentProofPath }}">
                    <input type="hidden" name="agree_terms" value="{{ $request->boolean('agree_terms') ? 1 : 0 }}">
                    <input type="hidden" name="data_consent" value="{{ $request->boolean('data_consent') ? 1 : 0 }}">

                    <div class="hidden rounded-2xl border p-4 text-sm" data-form-message role="status" aria-live="polite"></div>
                    <div class="hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" data-client-errors></div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row">
                        <button type="button" data-history-back class="flex-1 rounded-xl border border-slate-300 bg-white px-5 py-4 font-bold text-slate-700 hover:bg-slate-50">Edit Details</button>
                        <button type="submit" data-submit-button class="flex-[2] rounded-xl bg-teal-700 px-5 py-4 font-bold text-white hover:bg-teal-800 disabled:cursor-wait disabled:opacity-60">Complete Booking</button>
                    </div>
                </form>
            </main>

            @include('user.checkout.partials.cart-summary', [
                'allowRemove' => false,
                'cartTotal' => $total,
            ])
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script src="{{ asset('js/resort-checkout.js') }}" defer></script>
@endpush
