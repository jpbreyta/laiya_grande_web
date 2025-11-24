@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-600 via-emerald-500 to-teal-500 text-white shadow-2xl">
                <div class="relative p-8 md:p-10">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                        <div class="space-y-3">
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest rounded-full bg-white/15 text-emerald-100 ring-1 ring-white/30">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                Inventory
                            </span>
                            <div class="space-y-2">
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight">{{ $room->name }}</h1>
                                <p class="max-w-xl text-emerald-50 text-sm md:text-base leading-relaxed">
                                    Detailed overview of amenities, pricing, and availability for this accommodation.
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

            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-6 md:px-8 md:py-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Price</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">₱{{ number_format($room->price, 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Capacity</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $room->capacity }} guests</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Availability</p>
                            <span class="mt-2 inline-flex items-center gap-2 rounded-full px-3 py-1 text-sm font-semibold {{ $room->availability ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                <span class="h-2 w-2 rounded-full {{ $room->availability ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ $room->availability ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Status</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $room->status }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Rate Name</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">{{ $room->rate_name ?? 'Not specified' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm space-y-3">
                            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Short Description</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $room->short_description ?? 'No short description provided.' }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm space-y-3">
                            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Full Description</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">{{ $room->full_description ?? 'No full description provided.' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm space-y-3">
                            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Amenities</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ is_array($room->amenities) ? implode(', ', $room->amenities) : ($room->amenities ?? 'No amenities listed.') }}
                            </p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm space-y-3">
                            <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Gallery URLs</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                {{ is_array($room->images) ? implode(', ', $room->images) : ($room->images ?? 'No gallery images provided.') }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm space-y-3">
                        <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Primary Image</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $room->image ?? 'No primary image set.' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm space-y-4">
                        <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Amenities &amp; Inclusions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @php
                                $featureFields = [
                                    ['label' => 'Has Aircon', 'value' => $room->has_aircon],
                                    ['label' => 'Has Private CR', 'value' => $room->has_private_cr],
                                    ['label' => 'Has Kitchen', 'value' => $room->has_kitchen],
                                    ['label' => 'Has Free Parking', 'value' => $room->has_free_parking],
                                    ['label' => 'No Entrance Fee', 'value' => $room->no_entrance_fee],
                                    ['label' => 'No Corkage Fee', 'value' => $room->no_corkage_fee],
                                ];
                            @endphp
                            @foreach ($featureFields as $feature)
                                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <span class="text-sm font-semibold text-slate-700">{{ $feature['label'] }}</span>
                                    <span class="inline-flex h-8 w-16 items-center justify-center rounded-full text-xs font-semibold {{ $feature['value'] ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $feature['value'] ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
