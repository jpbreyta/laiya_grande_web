@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#5f9ea0] via-[#99e6b3] to-teal-500 text-white shadow-2xl">
                <div class="relative p-8 md:p-10">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                        <div class="space-y-3">
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest rounded-full bg-white/15 text-emerald-100 ring-1 ring-white/30">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                Kitchen
                            </span>
                            <div class="space-y-2">
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight">{{ $food->name }}</h1>
                                <p class="max-w-xl text-emerald-50 text-sm md:text-base leading-relaxed">
                                    Detailed view of item information, categories and audit timestamps.
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
                <div class="px-6 py-8 md:px-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Item ID</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">#{{ $food->id }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Price</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">₱{{ number_format($food->price, 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Category</p>
                            <span class="mt-2 inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1 text-sm font-semibold text-teal-600 border border-teal-100">
                                <i class="fas fa-tag text-[10px]"></i>
                                {{ $food->category->name ?? 'Uncategorized' }}
                            </span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm space-y-3">
                        <h3 class="text-sm font-semibold text-slate-700 uppercase tracking-widest">Description</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $food->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Created At</p>
                            <p class="mt-2 text-sm font-medium text-slate-700">{{ $food->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white px-5 py-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Last Updated</p>
                            <p class="mt-2 text-sm font-medium text-slate-700">{{ $food->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                        <a href="{{ route('admin.foods.edit', $food->id) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-amber-100 bg-amber-50 px-5 py-2.5 text-sm font-semibold text-amber-600 transition hover:bg-amber-100">
                            <i class="fas fa-pen text-xs"></i>
                            Edit Item
                        </a>
                        <a href="{{ route('admin.foods.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                            Back to Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
