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

        /* Print Styles */
        @media print {
            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .shadow-xl {
                box-shadow: none !important;
            }

            .sticky {
                position: relative !important;
            }

            button,
            a[href] {
                display: none !important;
            }

            .bg-slate-50 {
                background: white !important;
            }
        }
    </style>
@endpush

@section('content')
    <section class="min-h-screen bg-slate-50 py-16">
        <div class="container mx-auto px-4 max-w-7xl">
            <div class="mb-10 text-center">
                <h1 class="text-4xl font-bold text-teal-900 mb-2">Review Your Reservation</h1>
                <p class="text-slate-500 text-lg">Please review your details before confirming</p>
            </div>

            {{-- Progress Steps --}}
            <div class="max-w-2xl mx-auto mb-12">
                <div class="flex items-center justify-center relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="h-1 w-full bg-teal-600 rounded-full"></div>
                    </div>
                    <div class="relative flex flex-col items-center">
                        <div
                            class="h-10 w-10 bg-teal-700 rounded-full flex items-center justify-center border-4 border-slate-50 z-10 shadow-md">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="absolute -bottom-8 w-max text-sm font-bold text-teal-800 uppercase">Guest
                            Details</span>
                    </div>
                    <div class="relative flex flex-col items-center ml-32 sm:ml-48">
                        <div
                            class="h-10 w-10 bg-teal-700 rounded-full flex items-center justify-center border-4 border-slate-50 z-10 shadow-md">
                            <span class="text-white font-bold text-sm">2</span>
                        </div>
                        <span class="absolute -bottom-8 w-max text-sm font-bold text-teal-800 uppercase">Review</span>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-12 gap-8 lg:gap-12">
                <div class="lg:col-span-8 space-y-6">

                    {{-- Success Message --}}
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-r-xl">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-green-900">Reservation Submitted Successfully!</h3>
                                <p class="mt-2 text-sm text-green-700">
                                    Your reservation has been received. Please complete payment within 24 hours to confirm
                                    your booking.
                                </p>
                                <div class="mt-3 bg-white p-3 rounded-lg border border-green-200">
                                    <p class="text-xs text-green-600 font-semibold">Reservation Number:</p>
                                    <p class="text-xl font-bold text-green-900" id="reservationNumber">
                                        {{ session('reservation_number', 'N/A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 24-Hour Deadline Warning --}}
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-r-xl">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-yellow-900">Payment Deadline</h3>
                                <p class="mt-2 text-sm text-yellow-700">
                                    You have <strong>24 hours</strong> from now to complete your payment.
                                    Your reservation will be automatically cancelled if payment is not received within this
                                    timeframe.
                                </p>
                                <div class="mt-3 flex items-center gap-2 text-sm text-yellow-800">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Deadline: <strong
                                            id="deadlineTime">{{ now()->addHours(24)->format('M d, Y h:i A') }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Personal Information Review --}}
                    @include('user.reserve.partials.review-personal-info')

                    {{-- Reservation Details Review --}}
                    @include('user.reserve.partials.review-reservation-details')

                    {{-- Payment Information Review --}}
                    @include('user.reserve.partials.review-payment-info')

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="{{ route('home') }}"
                            class="flex-1 bg-white border border-gray-300 text-gray-700 font-bold py-4 rounded-xl text-center hover:bg-gray-50">
                            <i class="fas fa-home"></i> Back to Home
                        </a>
                        <button onclick="printReservation()"
                            class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-bold py-4 rounded-xl">
                            <i class="fas fa-print"></i> Print Details
                        </button>
                    </div>
                </div>

                {{-- Reservation Summary Sidebar --}}
                @include('user.reserve.partials.review-summary')
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function printReservation() {
            window.print();
        }

        // Show success message on page load
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#0f766e'
                });
            @endif
        });
    </script>
@endpush
