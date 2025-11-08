@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="flex justify-between items-center bg-gray-100 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Edit Room</h3>
            <a href="{{ route('admin.room.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
               Back to Rooms
            </a>
        </div>
        <div class="px-6 py-6 space-y-6">
            <form action="{{ route('admin.room.update', $room) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" required
                            value="{{ old('name', $room->name) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <input type="number" step="0.01" id="price" name="price" required
                            value="{{ old('price', $room->price) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" id="capacity" name="capacity" required
                            value="{{ old('capacity', $room->capacity) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="availability" class="block text-sm font-medium text-gray-700">Availability</label>
                        <select id="availability" name="availability" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="1" {{ old('availability', $room->availability) ? 'selected' : '' }}>Available</option>
                            <option value="0" {{ !old('availability', $room->availability) ? 'selected' : '' }}>Not Available</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="short_description" class="block text-sm font-medium text-gray-700">Short Description</label>
                    <textarea id="short_description" name="short_description"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('short_description', $room->short_description) }}</textarea>
                </div>

                <div>
                    <label for="full_description" class="block text-sm font-medium text-gray-700">Full Description</label>
                    <textarea id="full_description" name="full_description"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('full_description', $room->full_description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="amenities" class="block text-sm font-medium text-gray-700">Amenities (comma separated)</label>
                        <input type="text" id="amenities" name="amenities[]"
                            value="{{ old('amenities', is_array($room->amenities) ? implode(',', $room->amenities) : $room->amenities) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700">Images (comma separated)</label>
                        <input type="text" id="images" name="images[]"
                            value="{{ old('images', is_array($room->images) ? implode(',', $room->images) : $room->images) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                        <input type="text" id="image" name="image"
                            value="{{ old('image', $room->image) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="rate_name" class="block text-sm font-medium text-gray-700">Rate Name</label>
                        <input type="text" id="rate_name" name="rate_name"
                            value="{{ old('rate_name', $room->rate_name) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <input type="text" id="status" name="status" required
                            value="{{ old('status', $room->status) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="has_aircon" name="has_aircon" value="1"
                            {{ old('has_aircon', $room->has_aircon) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="has_aircon" class="text-sm text-gray-700">Has Aircon</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="has_private_cr" name="has_private_cr" value="1"
                            {{ old('has_private_cr', $room->has_private_cr) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="has_private_cr" class="text-sm text-gray-700">Has Private CR</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="has_kitchen" name="has_kitchen" value="1"
                            {{ old('has_kitchen', $room->has_kitchen) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="has_kitchen" class="text-sm text-gray-700">Has Kitchen</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="has_free_parking" name="has_free_parking" value="1"
                            {{ old('has_free_parking', $room->has_free_parking) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="has_free_parking" class="text-sm text-gray-700">Has Free Parking</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="no_entrance_fee" name="no_entrance_fee" value="1"
                            {{ old('no_entrance_fee', $room->no_entrance_fee) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="no_entrance_fee" class="text-sm text-gray-700">No Entrance Fee</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="no_corkage_fee" name="no_corkage_fee" value="1"
                            {{ old('no_corkage_fee', $room->no_corkage_fee) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="no_corkage_fee" class="text-sm text-gray-700">No Corkage Fee</label>
                    </div>
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium mt-4">
                    Update Room
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
