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
                                Kitchen
                            </span>
                            <div class="space-y-2">
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight">Add New Food Item</h1>
                                <p class="max-w-xl text-emerald-50 text-sm md:text-base leading-relaxed">
                                    Capture menu details to keep the POS and reservation menus consistent and up to date.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.foods.index') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-white/15 px-5 py-3 text-sm font-semibold text-white transition-all duration-200 hover:bg-white hover:text-teal-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 group-hover:bg-teal-600/10">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            Back to Menu
                        </a>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200">
                <div class="px-6 py-8 md:px-8">
                    <form action="{{ route('admin.foods.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700">Item Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                @error('name')
                                    <p class="mt-2 text-xs font-semibold text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="price" class="block text-sm font-semibold text-slate-700">Price</label>
                                <div class="mt-2 relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-400">₱</span>
                                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required
                                        class="w-full rounded-xl border border-slate-200 bg-white pl-8 pr-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                </div>
                                @error('price')
                                    <p class="mt-2 text-xs font-semibold text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-xs font-semibold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="food_category_id" class="block text-sm font-semibold text-slate-700">Category</label>
                                <select name="food_category_id" id="food_category_id" required
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-300">
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('food_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('food_category_id')
                                    <p class="mt-2 text-xs font-semibold text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                            <a href="{{ route('admin.foods.index') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:from-teal-400 hover:via-emerald-400 hover:to-teal-400">
                                <i class="fas fa-save text-xs"></i>
                                Create Food Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
