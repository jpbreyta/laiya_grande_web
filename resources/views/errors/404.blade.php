@extends('errors::layout')

@section('title', '404 - Page Not Found')

@section('content')
    <x-error-card code="404" title="Page Not Found"
        message="The page you're looking for doesn't exist or has been moved. Don't worry, let's get you back on track!"
        icon="file" />
@endsection
