@extends('errors::layout')

@section('title', '500 - Server Error')

@section('content')
    <x-error-card code="500" title="Server Error"
        message="Oops! Something went wrong on our end. Our team has been notified and we're working to fix it."
        icon="server" :showBack="false" />
@endsection
