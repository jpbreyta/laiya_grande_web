@extends('user.layouts.app')

@section('content')
    @include('user.checkout.form', [
        'checkoutMode' => 'reservation',
        'ajaxSubmit' => true,
        'datesEditable' => true,
        'pageTitle' => 'Reserve Your Stay',
        'pageDescription' => 'Complete the secure reservation form. Availability and rates are verified again before submission.',
        'formAction' => route('user.reservation.store'),
        'otpSendRoute' => route('user.reservation.send-otp'),
        'otpVerifyRoute' => route('user.reservation.verify-otp'),
        'backRoute' => route('booking.index'),
        'submitLabel' => 'Submit Reservation',
        'successRoute' => route('user.reservation.review'),
    ])
@endsection
