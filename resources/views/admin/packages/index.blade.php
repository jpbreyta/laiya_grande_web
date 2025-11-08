@extends('admin.layouts.app')

@section('content')

    <main class="main-content flex-1 bg-gray-50 p-6 overflow-auto">

        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Packages</h1>
                    <p class="text-gray-600 mt-2">Manage resort packages and promotions</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.packages.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-plus mr-2"></i>Create Package
                    </a>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-teal-primary to-teal-dark text-white p-6 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Total Packages</h3>
                            <p class="text-3xl font-bold">12</p>
                        </div>
                        <i class="fas fa-box text-4xl opacity-80"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Active Packages</h3>
                            <p class="text-3xl font-bold">8</p>
                        </div>
                        <i class="fas fa-check-circle text-4xl opacity-80"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Booked This Month</h3>
                            <p class="text-3xl font-bold">45</p>
                        </div>
                        <i class="fas fa-calendar-check text-4xl opacity-80"></i>
                    </div>
                </div>
            </div>


            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Package Management</h3>
                    <div class="flex items-center space-x-2">
                        <button class="px-3 py-1 bg-teal-primary text-white rounded-lg text-sm">Table View</button>
                        <button class="px-3 py-1 bg-gray-300 text-gray-700 rounded-lg text-sm">Extended View</button>
                    </div>
                </div>


                <div class="overflow-x-auto">
                    <table class="w-full bg-white rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Package Name</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Type</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Duration</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Price</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Capacity</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($packages as $package)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-box text-teal-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $package->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $package->description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $package->type ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $package->duration ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">₱{{ number_format($package->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $package->capacity ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.packages.edit', $package->id) }}" class="text-teal-600 hover:text-teal-800 text-sm">Edit</a>
                                        <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-center text-gray-500">No packages found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Package Performance</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Beach Paradise</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-teal-primary h-2 rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-800">85%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Mountain Retreat</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: 72%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-800">72%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Romantic Escape</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-purple-500 h-2 rounded-full" style="width: 68%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-800">68%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Family Fun</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-32 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: 91%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-800">91%</span>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Revenue</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-sun text-teal-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Beach Paradise</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">₱45,200</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-mountain text-blue-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Mountain Retreat</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">₱32,100</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-heart text-purple-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Romantic Escape</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">₱28,500</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-green-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-800">Family Fun</span>
                            </div>
                            <span class="text-sm font-medium text-gray-800">₱52,800</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection