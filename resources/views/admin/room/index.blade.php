@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="flex justify-between items-center bg-gray-100 px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Rooms</h3>
            <a href="{{ route('admin.room.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
               Add New Room
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($rooms as $room)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $room->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $room->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $room->price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $room->capacity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $room->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('admin.room.show', $room) }}" 
                                   class="bg-teal-500 hover:bg-teal-600 text-white px-3 py-1 rounded-md text-xs">View</a>
                                <a href="{{ route('admin.room.edit', $room) }}" 
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md text-xs">Edit</a>
                                <form action="{{ route('admin.room.destroy', $room) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
