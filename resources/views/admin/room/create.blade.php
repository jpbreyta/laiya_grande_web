@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-600 via-emerald-500 to-teal-500 text-white shadow-2xl">
                <div class="relative p-8 md:p-10">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                        <div class="space-y-3">
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest rounded-full bg-white/15 text-emerald-100 ring-1 ring-white/30">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                Inventory
                            </span>
                            <div class="space-y-2">
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight">Create New Room</h1>
                                <p class="max-w-xl text-emerald-50 text-sm md:text-base leading-relaxed">
                                    Capture essential details, amenities, and availability to keep your room catalog up to date.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.room.index') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-white/15 px-5 py-3 text-sm font-semibold text-white transition-all duration-200 hover:bg-white hover:text-teal-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 group-hover:bg-teal-600/10">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            Back to Rooms
                        </a>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200">
                <div class="px-6 py-8 md:px-8">
                    <form action="{{ route('admin.room.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700">Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-semibold text-slate-700">Price</label>
                                <input type="number" step="0.01" id="price" name="price" value="{{ old('price') }}" required
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                            </div>
                            <div>
                                <label for="capacity" class="block text-sm font-semibold text-slate-700">Capacity</label>
                                <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}" required
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                            </div>
                            <div>
                                <label for="availability" class="block text-sm font-semibold text-slate-700">Availability</label>
                                <select id="availability" name="availability" required
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                    <option value="1" {{ old('availability', '1') == '1' ? 'selected' : '' }}>Available</option>
                                    <option value="0" {{ old('availability') == '0' ? 'selected' : '' }}>Not Available</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="short_description" class="block text-sm font-semibold text-slate-700">Short Description</label>
                                <textarea id="short_description" name="short_description" rows="3"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">{{ old('short_description') }}</textarea>
                            </div>
                            <div>
                                <label for="full_description" class="block text-sm font-semibold text-slate-700">Full Description</label>
                                <textarea id="full_description" name="full_description" rows="3"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">{{ old('full_description') }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="amenities" class="block text-sm font-semibold text-slate-700">Amenities (comma separated)</label>
                                <input type="text" id="amenities" name="amenities[]" value="{{ old('amenities') }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                            </div>
                            <div>
                                <label for="images" class="block text-sm font-semibold text-slate-700">Images (comma separated)</label>
                                <input type="text" id="images" name="images[]" value="{{ old('images') }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                            </div>
                            <div>
                                <label for="image" class="block text-sm font-semibold text-slate-700">Hero Image URL</label>
                                <input type="text" id="image" name="image" value="{{ old('image') }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-teal-300 focus:ring-2">
                            </div>
                            <div>
                                <label for="rate_name" class="block text-sm font-semibold text-slate-700">Rate Name</label>
                                <input type="text" id="rate_name" name="rate_name" value="{{ old('rate_name') }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-teal-300 focus:ring-2">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700">Status</label>
                                <input type="text" id="status" name="status" value="{{ old('status') }}" required
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-teal-300 focus:ring-2">
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 sm:px-6">
                            <h3 class="text-sm font-semibold text-slate-700 mb-3">Amenities &amp; Inclusions</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-slate-600">
                                @php
                                    $featureFields = [
                                        ['id' => 'has_aircon', 'label' => 'Has Aircon'],
                                        ['id' => 'has_private_cr', 'label' => 'Has Private CR'],
                                        ['id' => 'has_kitchen', 'label' => 'Has Kitchen'],
                                        ['id' => 'has_free_parking', 'label' => 'Has Free Parking'],
                                        ['id' => 'no_entrance_fee', 'label' => 'No Entrance Fee'],
                                        ['id' => 'no_corkage_fee', 'label' => 'No Corkage Fee'],
                                    ];
                                @endphp
                                @foreach ($featureFields as $field)
                                    <label for="{{ $field['id'] }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-3 shadow-sm border border-slate-200">
                                        <input type="checkbox" id="{{ $field['id'] }}" name="{{ $field['id'] }}" value="1"
                                            {{ old($field['id']) ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500">
                                        <span class="font-medium">{{ $field['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                            <a href="{{ route('admin.room.index') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:from-teal-400 hover:via-emerald-400 hover:to-teal-400">
                                <i class="fas fa-save text-xs"></i>
                                Create Room
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
