@extends('user.layouts.app')

@section('content')
@php
    $images = $room->roomImages->pluck('path')->filter()->values();
    $fallback = asset('images/user/luxury-ocean-view-suite-hotel-room.jpg');
    $cartAddRoute = \Illuminate\Support\Facades\Route::has('cart.add') ? route('cart.add') : url('/cart/add');
@endphp
<section class="min-h-screen bg-slate-50 py-12 sm:py-16" data-room-page data-csrf-token="{{ csrf_token() }}">
    <div class="mx-auto max-w-6xl space-y-7 px-4 sm:px-6">
        <a href="{{ route('user.rooms.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-teal-700 hover:text-teal-900">
            <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to rooms
        </a>

        <section class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg">
            <div class="grid gap-2 bg-slate-100 p-2 md:grid-cols-2">
                @forelse ($images->take(4) as $path)
                    @php($imageUrl = str_starts_with($path, 'http') ? $path : asset($path))
                    <img src="{{ $imageUrl }}" alt="{{ $room->name }}" class="h-64 w-full rounded-2xl object-cover md:h-80" loading="lazy" decoding="async">
                @empty
                    <img src="{{ $fallback }}" alt="{{ $room->name }}" class="h-72 w-full rounded-2xl object-cover md:col-span-2" decoding="async">
                @endforelse
            </div>
        </section>

        <div class="grid gap-7 lg:grid-cols-12">
            <article class="rounded-3xl border border-slate-100 bg-white p-6 shadow-lg lg:col-span-8 sm:p-8">
                <div class="flex flex-col gap-3 border-b border-slate-100 pb-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-wide text-teal-700">{{ $room->code }}</p>
                        <h1 class="mt-1 text-3xl font-bold text-teal-950">{{ $room->name }}</h1>
                        <p class="mt-3 text-slate-600">{{ $room->full_description ?: $room->short_description }}</p>
                    </div>
                    <div class="rounded-2xl bg-teal-50 px-4 py-3 text-center">
                        <p class="text-xs font-bold uppercase text-teal-700">Capacity</p>
                        <p class="text-xl font-bold text-teal-950">{{ $room->capacity }} guests</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="text-lg font-bold text-teal-950">Amenities</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @forelse ($room->amenities as $amenity)
                            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-sm text-slate-700">{{ $amenity->name }}</span>
                        @empty
                            <span class="text-sm text-slate-500">Amenities will be confirmed by the resort.</span>
                        @endforelse
                    </div>
                </div>
            </article>

            <aside class="lg:col-span-4">
                <form action="{{ $cartAddRoute }}" method="POST" class="sticky top-24 rounded-3xl border border-slate-100 bg-white p-6 shadow-lg" data-cart-add-form data-success-url="{{ route('cart.index') }}">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">
                    <h2 class="text-xl font-bold text-teal-950">Choose a rate</h2>
                    <p class="mt-1 text-sm text-slate-500">The server validates the selected rate before adding it.</p>

                    <label class="mt-5 block">
                        <span class="mb-2 block text-xs font-bold uppercase text-slate-500">Room rate</span>
                        <select name="room_rate_id" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                            @foreach ($room->activeRates as $rate)
                                <option value="{{ $rate->id }}">{{ $rate->name }} — PHP {{ number_format($rate->price, 2) }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="mt-4 block">
                        <span class="mb-2 block text-xs font-bold uppercase text-slate-500">Quantity</span>
                        <input type="number" name="quantity" value="1" min="1" max="{{ min(20, $room->inventory_count) }}" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                    </label>

                    <div class="mt-4 hidden rounded-xl border p-3 text-sm" data-form-message></div>
                    <div class="mt-4 hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700" data-client-errors></div>

                    <button type="submit" data-submit-button class="mt-5 w-full rounded-xl bg-teal-700 px-5 py-4 font-bold text-white hover:bg-teal-800 disabled:cursor-wait disabled:opacity-60">
                        <i class="fas fa-cart-plus mr-2" aria-hidden="true"></i>Add to Cart
                    </button>
                </form>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script src="{{ asset('js/resort-checkout.js') }}" defer></script>
@endpush
