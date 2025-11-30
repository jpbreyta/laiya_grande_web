@extends('errors::layout')

@section('title', '429 - Too Many Requests')

@section('content')
    <x-error-card code="429" title="Too Many Requests"
        message="You've made too many requests. Please wait a moment and try again." icon="warning" :showBack="false" />
@endsection
