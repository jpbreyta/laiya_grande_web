@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <!-- Header Section -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-600 via-emerald-500 to-teal-500 text-white shadow-2xl">
                <div class="relative p-8 md:p-10">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                        <div class="space-y-3">
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest rounded-full bg-white/15 text-emerald-100 ring-1 ring-white/30">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                Packages
                            </span>
                            <div class="space-y-2">
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight">Create New Package</h1>
                                <p class="max-w-xl text-emerald-50 text-sm md:text-base leading-relaxed">
                                    Add a new resort package with all the details to attract more guests and boost bookings.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.packages.index') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-white/15 px-5 py-3 text-sm font-semibold text-white transition-all duration-200 hover:bg-white hover:text-teal-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 group-hover:bg-teal-600/10">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            Back to Packages
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200">
                <div class="px-6 py-8 md:px-8">
                    <form action="{{ route('admin.packages.store') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information -->
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 pb-4">
                                <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-teal-100 to-emerald-100 text-teal-600">
                                        <i class="fas fa-info-circle text-sm"></i>
                                    </span>
                                    Basic Information
                                </h3>
                                <p class="text-sm text-slate-500 mt-1 ml-10">Essential details about your package</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Package Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                        placeholder="e.g., Beach Paradise Package"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                    @error('name')
                                        <p class="mt-2 text-xs font-semibold text-rose-500 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Package Type
                                    </label>
                                    <select id="type" name="type"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                        <option value="">Select Type</option>
                                        <option value="Day Tour" {{ old('type') == 'Day Tour' ? 'selected' : '' }}>Day Tour</option>
                                        <option value="Overnight" {{ old('type') == 'Overnight' ? 'selected' : '' }}>Overnight</option>
                                        <option value="Weekend" {{ old('type') == 'Weekend' ? 'selected' : '' }}>Weekend</option>
                                        <option value="Week Package" {{ old('type') == 'Week Package' ? 'selected' : '' }}>Week Package</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="price" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Price <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400 font-medium">₱</span>
                                        <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}" required
                                            placeholder="0.00"
                                            class="w-full rounded-xl border border-slate-200 bg-white pl-8 pr-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                    </div>
                                    @error('price')
                                        <p class="mt-2 text-xs font-semibold text-rose-500 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="duration" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Duration
                                    </label>
                                    <input type="text" id="duration" name="duration" value="{{ old('duration') }}"
                                        placeholder="e.g., 2 Days 1 Night"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                </div>

                                <div>
                                    <label for="capacity" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Capacity (Number of Guests)
                                    </label>
                                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}"
                                        placeholder="e.g., 4"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                </div>

                                <div>
                                    <label for="image_path" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Image URL
                                    </label>
                                    <input type="text" id="image_path" name="image_path" value="{{ old('image_path') }}"
                                        placeholder="https://example.com/image.jpg"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 pb-4">
                                <h3 class="text-lg font-semibold text-slate-900 flex items-center gap-2">
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600">
                                        <i class="fas fa-align-left text-sm"></i>
                                    </span>
                                    Description
                                </h3>
                                <p class="text-sm text-slate-500 mt-1 ml-10">Describe what makes this package special</p>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Package Description <span class="text-rose-500">*</span>
                                </label>
                                <textarea id="description" name="description" rows="6" required
                                    placeholder="Enter a detailed description of the package, including what's included, activities, amenities, and any special features..."
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm transition focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300 resize-none">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-xs font-semibold text-rose-500 flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-6 border-t border-slate-200">
                            <a href="{{ route('admin.packages.index') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:shadow-sm">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 via-emerald-600 to-teal-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:from-teal-500 hover:via-emerald-500 hover:to-teal-500 hover:shadow-xl transform hover:scale-[1.02]">
                                <i class="fas fa-save"></i>
                                Create Package
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

