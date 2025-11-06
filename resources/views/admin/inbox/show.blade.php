@extends('admin.inbox.layouts.app')

@section('content')

    <!-- Preview pane (static, no <aside>) -->
    <div class="flex-1 p-6 overflow-y-auto bg-gray-50">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-start gap-4">
                <img src="https://ui-avatars.com/api/?name=J+S&background=2C5F5F&color=fff"
                     class="w-12 h-12 rounded-full" alt="avatar">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <div class="font-semibold text-lg text-gray-900">
                            Reservation request — Jules Santiago
                        </div>
                        <div class="ml-auto text-sm text-gray-400">Nov 6, 2025</div>
                    </div>
                    <div class="text-sm text-gray-500 mt-1">jules@example.com</div>
                </div>
            </div>

            <hr class="my-4 border-gray-200" />

            <div class="text-sm text-gray-700 leading-7">
                <p>Hello Admin,</p>
                <p class="mt-3">
                    I'd like to reserve a room for 2 adults from
                    <strong>Dec 20, 2025</strong> to
                    <strong>Dec 23, 2025</strong>.
                    Prefer a sea-view room if available. My reservation code is
                    <span class="font-mono text-sm">RSV-20251106224512-1A2B3C</span>.
                    Please confirm availability and total cost.
                </p>

                <p class="mt-3">Thanks,<br>Jules</p>

                <div class="mt-6 p-4 bg-gray-50 rounded">
                    <div class="text-xs text-gray-500">Details</div>
                    <div class="mt-2 text-sm text-gray-700 grid grid-cols-2 gap-2">
                        <div><strong>Guests:</strong> 2</div>
                        <div><strong>Room:</strong> Sea-view (requested)</div>
                        <div><strong>Check-in:</strong> Dec 20, 2025</div>
                        <div><strong>Check-out:</strong> Dec 23, 2025</div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition">Confirm</button>
                <button class="px-4 py-2 rounded bg-yellow-500 text-white hover:bg-yellow-600 transition">Reply</button>
                <button class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600 transition">Delete</button>
            </div>
        </div>
    </div>

@endsection
