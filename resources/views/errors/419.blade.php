@extends('errors::layout')

@section('title', '419 - Page Expired')

@section('content')
    <x-error-card code="419" title="Page Expired"
        message="Your session has expired. Please refresh the page and try again." icon="warning" />
@endsection
