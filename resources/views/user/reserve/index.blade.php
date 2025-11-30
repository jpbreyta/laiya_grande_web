@extends('user.layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #0f766e;
            border-radius: 10px
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #0d5f5a
        }
    </style>
@endpush

@section('content')
    <section class="min-h-screen bg-slate-50 py-16">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-bold text-teal-900 mb-2">Reserve Your Stay</h1>
                <p class="text-slate-500 text-lg">Complete your details - 24 hours to confirm payment</p>
            </div>

            {{-- Progress Steps --}}
            <div class="max-w-2xl mx-auto mb-12">
                <div class="flex items-center justify-center relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="h-1 w-full bg-gray-200 rounded-full"></div>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div
                            class="h-10 w-10 bg-teal-700 rounded-full flex items-center justify-center border-4 border-slate-50 z-10 shadow-md">
                            <span class="text-white font-bold text-sm">1</span>
                        </div>
                        <span class="absolute -bottom-8 w-max text-sm font-bold text-teal-800 uppercase">Guest
                            Details</span>
                    </div>
                    <div class="relative flex flex-col items-center ml-32 sm:ml-48">
                        <div
                            class="h-10 w-10 bg-white border-2 border-gray-300 rounded-full flex items-center justify-center z-10 shadow-sm">
                            <span class="text-gray-400 font-bold text-sm">2</span>
                        </div>
                        <span class="absolute -bottom-8 w-max text-sm font-medium text-gray-400 uppercase">Review</span>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
                <div class="lg:col-span-8">
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl mb-6">
                            <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.reservation.store') }}" id="reserveForm"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        {{-- Personal Information --}}
                        @include('user.reserve.partials.personal-info')

                        {{-- Reservation Details --}}
                        @include('user.reserve.partials.reservation-details')

                        {{-- Payment Section --}}
                        @include('user.reserve.partials.payment-section')

                        {{-- Form Actions --}}
                        <div class="flex flex-col-reverse sm:flex-row gap-4 pt-4">
                            <a href="{{ route('booking.index') }}"
                                class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-4 rounded-xl text-center hover:bg-gray-50">
                                Go Back
                            </a>
                            <button type="submit" id="submitBtn"
                                class="flex-[2] bg-teal-700 hover:bg-teal-800 text-white font-bold py-4 rounded-xl flex items-center justify-center gap-2">
                                Continue to Review <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Cart Summary Sidebar --}}
                @include('user.reserve.partials.cart-summary')
            </div>
        </div>
    </section>

    {{-- Privacy Modal --}}
    @include('user.reserve.partials.privacy-modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Pass Laravel routes to JavaScript
        window.csrfToken = '{{ csrf_token() }}';
        window.sendOtpRoute = '{{ route('user.reservation.send-otp') }}';
        window.verifyOtpRoute = '{{ route('user.reservation.verify-otp') }}';
        window.bookingIndexRoute = '{{ route('booking.index') }}';
        window.homeRoute = '{{ route('home') }}';
    </script>
    <script src="{{ asset('js/reservation.js') }}"></script>
@endpush
