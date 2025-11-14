@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Top Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Room Management</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage your room inventory and availability</p>
                </div>
                <a href="{{ route('admin.room.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                    <i class="fas fa-plus"></i>
                    Add New Room
                </a>
            </div>

            <div class="rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Rooms Overview</h2>
                        <p class="text-sm text-slate-500">All registered rooms with current status and quick actions.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-1 text-xs font-semibold uppercase tracking-widest text-emerald-600">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        {{ $rooms->count() }} rooms
                    </div>
                </div>

                @if ($rooms->isEmpty())
                    <div class="px-6 py-16 flex flex-col items-center justify-center text-center">
                        <span class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-teal-500">
                            <i class="fas fa-bed text-xl"></i>
                        </span>
                        <p class="text-base font-semibold text-slate-600">No rooms found</p>
                        <p class="mt-1 text-sm text-slate-500">Add your first room to start managing inventory.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Capacity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-widest">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($rooms as $room)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-medium text-slate-700">#{{ $room->id }}</td>
                                        @php
                                            $short = $room->short_description ?? '';
                                            $shortWithoutPrice = trim(\Illuminate\Support\Str::before($short, '₱'));
                                            $displayShortDescription = $shortWithoutPrice !== '' ? $shortWithoutPrice : $short;
                                        @endphp
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-slate-900">{{ $room->name }}</span>
                                                <span class="text-xs text-slate-500">
                                                    {{ $displayShortDescription ?: 'No short description' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-800">₱{{ number_format($room->price, 2) }}</td>
                                        <td class="px-6 py-4 text-sm text-slate-600">{{ $room->capacity }} guests</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $room->availability ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                                <span class="h-2 w-2 rounded-full {{ $room->availability ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                                {{ $room->availability ? 'Available' : 'Not Available' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <a href="{{ route('admin.room.show', $room) }}"
                                                    class="inline-flex items-center gap-2 rounded-xl border border-teal-100 bg-teal-50 px-3 py-1.5 text-xs font-semibold text-teal-600 transition hover:bg-teal-100">
                                                    <i class="fas fa-eye"></i>
                                                    View
                                                </a>
                                                <a href="{{ route('admin.room.edit', $room) }}"
                                                    class="inline-flex items-center gap-2 rounded-xl border border-amber-100 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-600 transition hover:bg-amber-100">
                                                    <i class="fas fa-pen"></i>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.room.destroy', $room) }}" method="POST"
                                                    class="inline-flex items-center gap-2"
                                                    onsubmit="return confirm('Are you sure you want to delete this room?');">
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
                @endif
            </div>
        </div>
    </section>
@endsection
