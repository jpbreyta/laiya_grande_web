@extends('admin.layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Foods Management</h1>

        <div class="mb-4">
            <a href="{{ route('admin.foods.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Food Item
            </a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Category</th>
                        <th class="py-3 px-6 text-left">Price</th>
                        <th class="py-3 px-6 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($foods as $food)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $food->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $food->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $food->category ? $food->category->name : 'N/A' }}</td>
                            <td class="py-3 px-6 text-left">${{ number_format($food->price, 2) }}</td>
                            <td class="py-3 px-6 text-left">
                                <a href="{{ route('admin.foods.edit', $food->id) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                |
                                <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline"
                                        onclick="return confirm('Are you sure you want to delete this food item?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $foods->links() }}
            </div>
        </div>
    </div>
@endsection
