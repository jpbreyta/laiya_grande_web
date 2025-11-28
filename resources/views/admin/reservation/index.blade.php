@extends('admin.layouts.app')

@php
    $pageTitle = 'Reservations Management';
    $currentStatus = request('status', 'all');
@endphp

@section('content')
    <section class="p-4 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">{{ $pageTitle }}</h1>
                
                <div class="flex gap-2">
                    <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow flex items-center gap-2">
                        <i class="fas fa-file-import"></i> Import CSV
                    </button>

                    <div class="relative group">
                        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 flex items-center gap-2">
                            <i class="fas fa-download"></i> Export <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block z-50 border border-gray-100">
                            <a href="{{ route('admin.reservation.export-csv', ['status' => $currentStatus]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">CSV</a>
                            <a href="{{ route('admin.reservation.export-csv', ['status' => $currentStatus]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Excel (csv)</a>
                            <a href="{{ route('admin.reservation.export-pdf', ['status' => $currentStatus]) }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print / PDF</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 overflow-x-auto">
                <nav class="flex space-x-2 bg-white p-1 rounded-xl shadow-sm border border-gray-200">
                    @php
                        $tabs = [
                            'all' => 'All',
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'paid' => 'Paid',
                            'cancelled' => 'Cancelled',
                            'archived' => 'Archived'
                        ];
                    @endphp
                    @foreach($tabs as $key => $label)
                        <a href="{{ route('admin.reservation.index', ['status' => $key]) }}" 
                           class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $currentStatus === $key ? 'bg-teal-600 text-white shadow' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                           {{ $label }}
                        </a>
                    @endforeach
                </nav>
            </div>

            @if (session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-emerald-700">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm mb-6 flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-700">&times;</button>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-600 uppercase text-xs font-bold tracking-wider">
                                <th class="py-3 px-4">Ref #</th>
                                <th class="py-3 px-4">Guest Name</th>
                                <th class="py-3 px-4">Room</th>
                                <th class="py-3 px-4">Dates</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($reservations as $reservation)
                                <tr class="hover:bg-gray-50 transition-colors" 
                                    style="cursor: pointer;"
                                    onclick="window.location.href='{{ route('admin.reservation.show', $reservation->id) }}'">
                                    
                                    <td class="py-3 px-4 text-xs font-mono text-gray-500">
                                        {{ $reservation->reservation_number ?? $reservation->id }}
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        {{-- Normalization: Accessing Customer relationship --}}
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ $reservation->customer->firstname ?? 'Unknown' }} {{ $reservation->customer->lastname ?? '' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $reservation->customer->email ?? 'No email' }}
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        {{ $reservation->room->name ?? 'N/A' }}
                                    </td>

                                    <td class="py-3 px-4 text-sm text-gray-700">
                                        <div>In: {{ \Carbon\Carbon::parse($reservation->check_in)->format('M d') }}</div>
                                        <div>Out: {{ \Carbon\Carbon::parse($reservation->check_out)->format('M d') }}</div>
                                    </td>

                                    <td class="py-3 px-4 font-bold text-emerald-600">
                                        ₱{{ number_format($reservation->total_price, 2) }}
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        @php
                                            $colors = [
                                                'pending'   => 'bg-amber-100 text-amber-800 border border-amber-200',
                                                'confirmed' => 'bg-green-100 text-green-800 border border-green-200',
                                                'paid'      => 'bg-blue-100 text-blue-800 border border-blue-200',
                                                'cancelled' => 'bg-red-100 text-red-800 border border-red-200',
                                            ];
                                            $color = $colors[$reservation->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>

                                    <td class="py-3 px-4 text-center" onclick="event.stopPropagation();">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($currentStatus === 'archived')
                                                <form action="{{ route('admin.reservation.restore', $reservation->id) }}" method="POST">
                                                    @csrf @method('POST')
                                                    <button type="submit" class="text-emerald-600 hover:text-emerald-900 text-xs font-bold uppercase" title="Restore">Restore</button>
                                                </form>
                                                <form action="{{ route('admin.reservation.force-delete', $reservation->id) }}" method="POST" onsubmit="return confirm('Permanently delete?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs font-bold uppercase ml-2" title="Delete Forever">Delete</button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.reservation.show', $reservation->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.reservation.edit', $reservation->id) }}" class="text-emerald-600 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 p-2 rounded-lg transition" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                {{-- Archive Button --}}
                                                <form action="{{ route('admin.reservation.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to archive this reservation?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Archive">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                            <p>No {{ $currentStatus == 'all' ? '' : $currentStatus }} reservations found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-4 border-t border-gray-100">
                        {{ $reservations->appends(['status' => $currentStatus])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="importModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 flex">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Import Reservations</h3>
            <form action="{{ route('admin.reservation.import-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload CSV File</label>
                    <input type="file" name="csv_file" accept=".csv" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-lg cursor-pointer">
                    <p class="text-xs text-gray-500 mt-2">Format: Firstname, Lastname, Email, Phone, RoomID, CheckIn, CheckOut, Guests, Total, Status</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
@endsection