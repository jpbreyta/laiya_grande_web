@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Room</h3>
                        <a href="{{ route('admin.room.index') }}" class="btn btn-secondary float-right">Back to Rooms</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.room.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="short_description">Short Description</label>
                                <textarea class="form-control" id="short_description" name="short_description">{{ old('short_description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="full_description">Full Description</label>
                                <textarea class="form-control" id="full_description" name="full_description">{{ old('full_description') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price"
                                    value="{{ old('price') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="capacity">Capacity</label>
                                <input type="number" class="form-control" id="capacity" name="capacity"
                                    value="{{ old('capacity') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="availability">Availability</label>
                                <select class="form-control" id="availability" name="availability" required>
                                    <option value="1" {{ old('availability') ? 'selected' : '' }}>Available</option>
                                    <option value="0" {{ !old('availability') ? 'selected' : '' }}>Not Available
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amenities">Amenities (comma separated)</label>
                                <input type="text" class="form-control" id="amenities" name="amenities[]"
                                    value="{{ old('amenities') }}">
                            </div>
                            <div class="form-group">
                                <label for="images">Images (comma separated)</label>
                                <input type="text" class="form-control" id="images" name="images[]"
                                    value="{{ old('images') }}">
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="text" class="form-control" id="image" name="image"
                                    value="{{ old('image') }}">
                            </div>
                            <div class="form-group">
                                <label for="rate_name">Rate Name</label>
                                <input type="text" class="form-control" id="rate_name" name="rate_name"
                                    value="{{ old('rate_name') }}">
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <input type="text" class="form-control" id="status" name="status"
                                    value="{{ old('status') }}" required>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="has_aircon" name="has_aircon"
                                    value="1" {{ old('has_aircon') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_aircon">Has Aircon</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="has_private_cr" name="has_private_cr"
                                    value="1" {{ old('has_private_cr') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_private_cr">Has Private CR</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="has_kitchen" name="has_kitchen"
                                    value="1" {{ old('has_kitchen') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_kitchen">Has Kitchen</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="has_free_parking"
                                    name="has_free_parking" value="1"
                                    {{ old('has_free_parking') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_free_parking">Has Free Parking</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="no_entrance_fee"
                                    name="no_entrance_fee" value="1" {{ old('no_entrance_fee') ? 'checked' : '' }}>
                                <label class="form-check-label" for="no_entrance_fee">No Entrance Fee</label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="no_corkage_fee"
                                    name="no_corkage_fee" value="1" {{ old('no_corkage_fee') ? 'checked' : '' }}>
                                <label class="form-check-label" for="no_corkage_fee">No Corkage Fee</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Room</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
