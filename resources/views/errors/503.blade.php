@extends('errors::layout')

@section('title', '503 - Service Unavailable')

@section('content')
    <x-error-card code="503" title="Service Unavailable"
        message="We're currently performing maintenance. We'll be back shortly. Thank you for your patience!" icon="warning"
        :showBack="false" />
@endsection
