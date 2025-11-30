@extends('errors::layout')

@section('title', '403 - Forbidden')

@section('content')
    <x-error-card code="403" title="Access Forbidden"
        message="You don't have permission to access this resource. Please contact support if you believe this is an error."
        icon="lock" />
@endsection
