@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Food Item Details</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <strong class="text-gray-700">ID:</strong>
                <span class="text-gray-600">{{ $food->id }}</span>
            </div>

            <div class="mb-4">
                <strong class="text-gray-700">Name:</strong>
                <span class="text-gray-600">{{ $food->name }}</span>
            </div>

            <div class="mb-4">
                <strong class="text-gray-700">Description:</strong>
                <span class="text-gray-600">{{ $food->description ?: 'N/A' }}</span>
            </div>

            <div class="mb-4">
                <strong class="text-gray-700">Price:</strong>
                <span class="text-gray-600">${{ number_format($food->price, 2) }}</span>
            </div>

            <div class="mb-4">
                <strong class="text-gray-700">Category:</strong>
                <span class="text-gray-600">{{ $food->category ? $food->category->name : 'N/A' }}</span>
            </div>

            <div class="mb-4">
                <strong class="text-gray-700">Created At:</strong>
                <span class="text-gray-600">{{ $food->created_at->format('Y-m-d H:i:s') }}</span>
            </div>

            <div class="mb-4">
                <strong class="text-gray-700">Updated At:</strong>
                <span class="text-gray-600">{{ $food->updated_at->format('Y-m-d H:i:s') }}</span>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.foods.edit', $food->id) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Edit
                </a>
                <a href="{{ route('admin.foods.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Back to List
                </a>
            </div>
        </div>
    </div>
@endsection
