@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-teal-600 via-emerald-500 to-teal-500 text-white shadow-2xl">
                <div class="relative p-8 md:p-12">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                        <div class="space-y-4">
                            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest rounded-full bg-white/15 text-emerald-100 ring-1 ring-white/30">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                Kitchen
                            </span>
                            <div class="space-y-2">
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight">Food &amp; Beverage Catalog</h1>
                                <p class="max-w-2xl text-emerald-50 text-sm md:text-base leading-relaxed">
                                    Manage menu items, stay on top of pricing, and ensure categories are always current for POS and reservations.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('admin.foods.create') }}"
                            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-white/15 px-5 py-3 text-sm font-semibold text-white transition-all duration-200 hover:bg-white hover:text-teal-600">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/20 group-hover:bg-teal-600/10">
                                <i class="fas fa-plus"></i>
                            </span>
                            Add New Food Item
                        </a>
                    </div>
                </div>
            </div>

            @php
                $foodsCount = method_exists($foods, 'total') ? $foods->total() : $foods->count();
            @endphp

            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Menu Overview</h2>
                        <p class="text-sm text-slate-500">All menu items with category tags and pricing.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-emerald-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        {{ $foodsCount }} items
                    </div>
                </div>

                @if ($foods->isEmpty())
                    <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                        <span class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-teal-500">
                            <i class="fas fa-utensils text-xl"></i>
                        </span>
                        <p class="text-base font-semibold text-slate-600">No food items found</p>
                        <p class="mt-1 text-sm text-slate-500">Add your first menu item to populate the catalog.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Category</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($foods as $food)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-slate-700">#{{ $food->id }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-slate-900">{{ $food->name }}</span>
                                                <span class="text-xs text-slate-500">{{ $food->description ?? 'No description provided.' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="inline-flex items-center gap-2 rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-600 border border-teal-100">
                                                <i class="fas fa-tag text-[10px]"></i>
                                                {{ $food->category->name ?? 'Uncategorized' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-800">₱{{ number_format($food->price, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a href="{{ route('admin.foods.show', $food->id) }}"
                                                    class="inline-flex items-center gap-2 rounded-xl border border-teal-100 bg-teal-50 px-3 py-1.5 text-xs font-semibold text-teal-600 transition hover:bg-teal-100">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>
                                                <a href="{{ route('admin.foods.edit', $food->id) }}"
                                                    class="inline-flex items-center gap-2 rounded-xl border border-amber-100 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-600 transition hover:bg-amber-100">
                                                    <i class="fas fa-pen"></i>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST"
                                                    class="inline-flex items-center gap-2"
                                                    onsubmit="return confirm('Are you sure you want to delete this food item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-2 rounded-xl border border-rose-100 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
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

                    <div class="px-6 py-5 border-t border-slate-100">
                        {{ $foods->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
