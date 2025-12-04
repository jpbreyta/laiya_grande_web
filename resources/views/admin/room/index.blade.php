@extends('admin.layouts.app')

@section('content')
    <section class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Room Management</h1>
                    <p class="text-sm text-slate-500 mt-1">Manage your room inventory and availability</p>
                </div>
                <div class="flex gap-2">
                    <div class="relative">
                        <button id="exportDropdownBtn" type="button"
                            class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow flex items-center gap-2">
                            <i class="fas fa-download"></i> Export <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="exportDropdown"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-50 border border-gray-100">
                            <a href="{{ route('admin.room.export-csv') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-t-md">
                                <i class="fas fa-file-csv mr-2"></i>Export CSV
                            </a>
                            <a href="{{ route('admin.room.export-pdf') }}" target="_blank"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-b-md">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('admin.room.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white px-6 py-3 text-sm font-semibold shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 whitespace-nowrap">
                        <i class="fas fa-plus"></i>
                        Add New Room
                    </a>
                </div>
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
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs font-bold tracking-wider">
                                    <th class="py-3 px-4">ID</th>
                                    <th class="py-3 px-4">Room Name</th>
                                    <th class="py-3 px-4">Price</th>
                                    <th class="py-3 px-4">Capacity</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                            <p>No rooms found.</p>
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
                                    <th class="py-3 px-4">Room Name</th>
                                    <th class="py-3 px-4">Price</th>
                                    <th class="py-3 px-4">Capacity</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($rooms as $room)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-xs font-semibold text-gray-700">{{ $room->id }}</td>
                                        
                                        @php
                                            $short = $room->short_description ?? '';
                                            $shortWithoutPrice = trim(\Illuminate\Support\Str::before($short, '₱'));
                                            $displayShortDescription = $shortWithoutPrice !== '' ? $shortWithoutPrice : $short;
                                        @endphp
                                        <td class="py-3 px-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $room->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $displayShortDescription ?: 'No description' }}</div>
                                        </td>

                                        <td class="py-3 px-4 font-bold text-emerald-600">₱{{ number_format($room->price, 2) }}</td>
                                        <td class="py-3 px-4 text-sm text-gray-700">{{ $room->capacity }} guests</td>
                                        
                                        <td class="py-3 px-4">
                                            @php
                                                $color = $room->availability ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                                {{ $room->availability ? 'Available' : 'Not Available' }}
                                            </span>
                                        </td>

                                        <td class="py-3 px-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('admin.room.show', $room) }}"
                                                    class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition"
                                                    title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.room.edit', $room) }}"
                                                    class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 p-2 rounded-lg transition"
                                                    title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form action="{{ route('admin.room.destroy', $room) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this room?');">
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
        </div>
    </section>

    <script>
        const exportDropdownBtn = document.getElementById('exportDropdownBtn');
        const exportDropdown = document.getElementById('exportDropdown');

        if (exportDropdownBtn && exportDropdown) {
            exportDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                exportDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!exportDropdown.contains(e.target) && e.target !== exportDropdownBtn) {
                    exportDropdown.classList.add('hidden');
                }
            });
        }
    </script>
@endsection
