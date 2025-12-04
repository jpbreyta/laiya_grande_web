@extends('errors::layout')

@section('title', '401 - Unauthorized')

@section('content')
    <x-error-card code="401" title="Unauthorized"
        message="You need to be logged in to access this page. Please sign in to continue." icon="lock" />
@endsection
