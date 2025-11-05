@extends('admin.layouts.app')

@section('content')


    <div class="container">
        <h1>Edit Package</h1>

        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Package Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $package->name }}" required>    