@php
    $checkoutMode = $checkoutMode ?? 'booking';
    $ajaxSubmit = $ajaxSubmit ?? false;
    $datesEditable = $datesEditable ?? false;
@endphp

<section class="min-h-screen bg-slate-50 py-12 sm:py-16"
    data-checkout-page
    data-send-otp-url="{{ $otpSendRoute }}"
    data-verify-otp-url="{{ $otpVerifyRoute }}"
    data-csrf-token="{{ csrf_token() }}"
    data-nightly-subtotal="{{ $cartSubtotal }}"
    data-success-url="{{ $successRoute ?? route('home') }}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6">
        <header class="mb-9 text-center">
            <p class="mb-2 text-sm font-bold uppercase tracking-[0.2em] text-teal-700">Laiya Grande</p>
            <h1 class="text-3xl font-bold text-teal-950 sm:text-4xl">{{ $pageTitle }}</h1>
            <p class="mx-auto mt-3 max-w-2xl text-slate-600">{{ $pageDescription }}</p>
        </header>

        @include('user.checkout.partials.progress', ['activeStep' => 1])

        <div class="grid gap-8 lg:grid-cols-12">
            <main class="lg:col-span-8">
                @include('user.checkout.partials.alerts')

                <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data"
                    class="space-y-7" data-checkout-form data-ajax-submit="{{ $ajaxSubmit ? 'true' : 'false' }}">
                    @csrf

                    @include('user.checkout.partials.personal-information')
                    @include('user.checkout.partials.stay-details', compact('datesEditable'))
                    @include('user.checkout.partials.payment-section')

                    <section class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg shadow-teal-900/5 sm:p-8">
                        <div class="space-y-4 text-sm text-slate-700">
                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="agree_terms" value="1" required
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-teal-700 focus:ring-teal-500">
                                <span>I accept the <button type="button" data-open-dialog class="font-semibold text-teal-700 underline">booking terms</button>.</span>
                            </label>
                            <label class="flex items-start gap-3">
                                <input type="checkbox" name="data_consent" value="1" required
                                    class="mt-1 h-4 w-4 rounded border-slate-300 text-teal-700 focus:ring-teal-500">
                                <span>I consent to the processing of my information for this booking.</span>
                            </label>
                        </div>
                    </section>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row">
                        <a href="{{ $backRoute }}" class="flex-1 rounded-xl border border-slate-300 bg-white px-5 py-4 text-center font-bold text-slate-700 transition hover:bg-slate-50">Go back</a>
                        <button type="submit" class="flex-[2] rounded-xl bg-teal-700 px-5 py-4 font-bold text-white transition hover:bg-teal-800 disabled:cursor-wait disabled:opacity-60" data-submit-button>
                            {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </main>

            @include('user.checkout.partials.cart-summary', ['allowRemove' => true])
        </div>
    </div>

    @include('user.checkout.partials.privacy-dialog')
</section>

@push('scripts')
    <script src="{{ asset('js/resort-checkout.js') }}" defer></script>
@endpush
