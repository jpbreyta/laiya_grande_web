@extends('user.layouts.app')

@section('content')
    @include('user.checkout.form', [
        'checkoutMode' => 'booking',
        'ajaxSubmit' => false,
        'datesEditable' => false,
        'pageTitle' => 'Secure Your Stay',
        'pageDescription' => 'Enter your information, verify your email, and upload payment proof before reviewing the booking.',
        'formAction' => route('user.booking.show-confirm'),
        'otpSendRoute' => route('user.booking.send-otp'),
        'otpVerifyRoute' => route('user.booking.verify-otp'),
        'backRoute' => route('cart.index'),
        'submitLabel' => 'Review Booking',
        'successRoute' => route('home'),
    ])
@endsection
