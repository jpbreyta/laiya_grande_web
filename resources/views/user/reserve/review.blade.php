@extends('user.layouts.app')

@section('content')
@php($reservation = $reservation ?? session('reservation_data', []))
<section class="min-h-screen bg-slate-50 py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <header class="mb-9 text-center print:hidden">
            <p class="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-teal-700">Reservation Received</p>
            <h1 class="text-3xl font-bold text-teal-950 sm:text-4xl">Review Your Reservation</h1>
            <p class="mt-3 text-slate-600">Keep the reservation number for payment and status inquiries.</p>
        </header>

        @include('user.checkout.partials.progress', ['activeStep' => 2])

        <div class="grid gap-8 lg:grid-cols-12">
            <main class="space-y-6 lg:col-span-8">
                <section class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6">
                    <div class="flex gap-4">
                        <i class="fas fa-circle-check mt-1 text-2xl text-emerald-600" aria-hidden="true"></i>
                        <div>
                            <h2 class="text-xl font-bold text-emerald-950">Reservation submitted successfully</h2>
                            <p class="mt-1 text-sm text-emerald-800">Payment verification is pending. The reservation remains subject to the stated expiration period.</p>
                            <p class="mt-4 rounded-xl bg-white px-4 py-3 font-mono text-lg font-bold text-emerald-950">{{ $reservation['reservation_number'] ?? session('reservation_number', 'N/A') }}</p>
                        </div>
                    </div>
                </section>

                @include('user.reserve.partials.review-personal-info', compact('reservation'))
                @include('user.reserve.partials.review-reservation-details', compact('reservation'))
                @include('user.reserve.partials.review-payment-info', compact('reservation'))

                <div class="flex flex-col gap-3 print:hidden sm:flex-row">
                    <a href="{{ route('home') }}" class="flex-1 rounded-xl border border-slate-300 bg-white px-5 py-4 text-center font-bold text-slate-700 hover:bg-slate-50">Back to Home</a>
                    <button type="button" data-print-page class="flex-1 rounded-xl bg-teal-700 px-5 py-4 font-bold text-white hover:bg-teal-800">Print Details</button>
                </div>
            </main>

            @include('user.reserve.partials.review-summary', compact('reservation'))
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script src="{{ asset('js/resort-checkout.js') }}" defer></script>
@endpush
