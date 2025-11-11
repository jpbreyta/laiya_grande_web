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
                    <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                        <span class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-teal-500">
                            <i class="fas fa-box text-xl"></i>
                        </span>
                        <p class="text-base font-semibold text-slate-600">No packages found</p>
                        <p class="mt-1 text-sm text-slate-500">Create your first package to start offering promotions.</p>
                        <a href="{{ route('admin.packages.create') }}"
                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                            <i class="fas fa-plus"></i>
                            Create Package
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Package</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Duration</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Capacity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($packages as $package)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 bg-gradient-to-br from-teal-100 to-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-box text-teal-600"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $package->name }}</p>
                                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                                        {{ Str::limit($package->description ?? 'No description available', 60) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-700">
                                                {{ $package->type ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-clock text-slate-400 text-xs"></i>
                                                <span class="text-sm text-slate-700">
                                                    {{ $package->duration ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-600">
                                                ₱{{ number_format($package->price, 2) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-700">
                                                {{ $package->capacity ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold bg-emerald-50 text-emerald-600">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a href="{{ route('admin.packages.edit', $package->id) }}"
                                                    class="inline-flex items-center gap-2 rounded-xl border border-teal-100 bg-teal-50 px-3 py-1.5 text-xs font-semibold text-teal-600 transition hover:bg-teal-100 hover:shadow-sm">
                                                    <i class="fas fa-pen"></i>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
                                                    class="inline-flex items-center gap-2"
                                                    onsubmit="return confirm('Are you sure you want to delete this package? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-2 rounded-xl border border-rose-100 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100 hover:shadow-sm">
                                                        <i class="fas fa-trash-alt"></i>
                                                        Delete
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