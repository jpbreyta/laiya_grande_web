@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Top Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Package Management</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage your resort packages and promotions</p>
                </div>
                <a href="{{ route('admin.packages.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-plus"></i>
                    Create New Package
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Packages Card -->
                <div class="bg-gradient-to-br from-[#5f9ea0] to-[#99e6b3] rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-100 text-sm font-medium mb-1">Total Packages</p>
                            <p class="text-3xl font-bold text-white">{{ $packages->count() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Packages Card -->
                <div class="bg-gradient-to-br from-[#5f9ea0] to-[#6495ed] rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-teal-100 text-sm font-medium mb-1">Active Packages</p>
                            <p class="text-3xl font-bold text-white">{{ $packages->count() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-check-circle text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Value Card -->
                <div class="bg-gradient-to-br from-[#5f9ea0] to-[#b0e0e6] rounded-2xl p-6 shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-cyan-100 text-sm font-medium mb-1">Total Value</p>
                            <p class="text-2xl font-bold text-white">₱{{ number_format($packages->sum('price'), 2) }}</p>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-peso-sign text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Table Card -->
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">All Packages</h2>
                        <p class="text-sm text-slate-500">View and manage all your resort packages.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-teal-600">
                        <span class="h-2 w-2 rounded-full bg-teal-500"></span>
                        {{ $packages->count() }} {{ Str::plural('package', $packages->count()) }}
                    </div>
                </div>

                @if ($packages->isEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs font-bold tracking-wider">
                                    <th class="py-3 px-4">ID</th>
                                    <th class="py-3 px-4">Package Name</th>
                                    <th class="py-3 px-4">Type</th>
                                    <th class="py-3 px-4">Duration</th>
                                    <th class="py-3 px-4">Price</th>
                                    <th class="py-3 px-4">Capacity</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                            <p>No packages found.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs font-bold tracking-wider">
                                    <th class="py-3 px-4">ID</th>
                                    <th class="py-3 px-4">Package Name</th>
                                    <th class="py-3 px-4">Type</th>
                                    <th class="py-3 px-4">Duration</th>
                                    <th class="py-3 px-4">Price</th>
                                    <th class="py-3 px-4">Capacity</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($packages as $package)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-xs font-semibold text-gray-700">{{ $package->id }}</td>
                                        
                                        <td class="py-3 px-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $package->name }}</div>
                                            <div class="text-xs text-gray-500">{{ Str::limit($package->description ?? 'No description', 50) }}</div>
                                        </td>

                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $package->type ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $package->duration ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 font-bold text-emerald-600">₱{{ number_format($package->price, 2) }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $package->capacity ?? 'N/A' }}</td>
                                        
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </td>

                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.packages.edit', $package->id) }}"
                                                    class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition"
                                                    title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this package?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition"
                                                        title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Performance & Revenue Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Package Performance Card -->
                <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h3 class="text-lg font-semibold text-slate-900">Package Performance</h3>
                        <p class="text-sm text-slate-500 mt-1">Booking performance overview</p>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Beach Paradise</span>
                            <div class="flex items-center space-x-3">
                                <div class="w-32 bg-slate-200 rounded-full h-2.5">
                                    <div class="bg-gradient-to-r from-teal-500 to-emerald-600 h-2.5 rounded-full" style="width: 85%"></div>
                                </div>
                                <span class="text-sm font-semibold text-slate-900 w-12 text-right">85%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Mountain Retreat</span>
                            <div class="flex items-center space-x-3">
                                <div class="w-32 bg-slate-200 rounded-full h-2.5">
                                    <div class="bg-gradient-to-r from-teal-400 to-emerald-600 h-2.5 rounded-full" style="width: 72%"></div>
                                </div>
                                <span class="text-sm font-semibold text-slate-900 w-12 text-right">72%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Romantic Escape</span>
                            <div class="flex items-center space-x-3">
                                <div class="w-32 bg-slate-200 rounded-full h-2.5">
                                    <div class="bg-gradient-to-r from-cyan-500 to-teal-600 h-2.5 rounded-full" style="width: 68%"></div>
                                </div>
                                <span class="text-sm font-semibold text-slate-900 w-12 text-right">68%</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Family Fun</span>
                            <div class="flex items-center space-x-3">
                                <div class="w-32 bg-slate-200 rounded-full h-2.5">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2.5 rounded-full" style="width: 91%"></div>
                                </div>
                                <span class="text-sm font-semibold text-slate-900 w-12 text-right">91%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue Card -->
                <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h3 class="text-lg font-semibold text-slate-900">Monthly Revenue</h3>
                        <p class="text-sm text-slate-500 mt-1">Revenue breakdown by package</p>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-teal-50 to-emerald-50 rounded-xl border border-teal-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-sun text-teal-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-semibold text-slate-900">Beach Paradise</span>
                            </div>
                            <span class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">₱45,200</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-mountain text-blue-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-semibold text-slate-900">Mountain Retreat</span>
                            </div>
                            <span class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">₱32,100</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-cyan-50 to-teal-50 rounded-xl border border-cyan-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-heart text-cyan-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-semibold text-slate-900">Romantic Escape</span>
                            </div>
                            <span class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-teal-600">₱28,500</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-green-600 text-sm"></i>
                                </div>
                                <span class="text-sm font-semibold text-slate-900">Family Fun</span>
                            </div>
                            <span class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">₱52,800</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection