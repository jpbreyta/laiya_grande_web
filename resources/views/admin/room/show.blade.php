@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="flex justify-between items-center bg-gray-100 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Room Details</h3>
            <a href="{{ route('admin.room.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
               Back to Rooms
            </a>
        </div>
        <div class="px-6 py-4 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Name</h5>
                    <p class="text-gray-800">{{ $room->name }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Price</h5>
                    <p class="text-gray-800">{{ $room->price }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Capacity</h5>
                    <p class="text-gray-800">{{ $room->capacity }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Availability</h5>
                    <p class="text-gray-800">{{ $room->availability ? 'Available' : 'Not Available' }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Status</h5>
                    <p class="text-gray-800">{{ $room->status }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Rate Name</h5>
                    <p class="text-gray-800">{{ $room->rate_name }}</p>
                </div>
            </div>

            <div>
                <h5 class="text-sm font-semibold text-gray-700">Short Description</h5>
                <p class="text-gray-800">{{ $room->short_description }}</p>
            </div>
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Full Description</h5>
                <p class="text-gray-800">{{ $room->full_description }}</p>
            </div>
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Amenities</h5>
                <p class="text-gray-800">{{ is_array($room->amenities) ? implode(', ', $room->amenities) : $room->amenities }}</p>
            </div>
            <div>
                <h5 class="text-sm font-semibold text-gray-700">Images</h5>
                <p class="text-gray-800">{{ is_array($room->images) ? implode(', ', $room->images) : $room->images }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Image</h5>
                    <p class="text-gray-800">{{ $room->image }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Has Aircon</h5>
                    <p class="text-gray-800">{{ $room->has_aircon ? 'Yes' : 'No' }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Has Private CR</h5>
                    <p class="text-gray-800">{{ $room->has_private_cr ? 'Yes' : 'No' }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Has Kitchen</h5>
                    <p class="text-gray-800">{{ $room->has_kitchen ? 'Yes' : 'No' }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Has Free Parking</h5>
                    <p class="text-gray-800">{{ $room->has_free_parking ? 'Yes' : 'No' }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">No Entrance Fee</h5>
                    <p class="text-gray-800">{{ $room->no_entrance_fee ? 'Yes' : 'No' }}</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">No Corkage Fee</h5>
                    <p class="text-gray-800">{{ $room->no_corkage_fee ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
