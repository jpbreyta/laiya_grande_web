@extends('admin.inbox.layouts.app')

@section('content')
    <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold text-gray-800">Admin Inbox</h1>
    </div>
    <!-- Inbox Sub-header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <section class="mt-2 border-r border-gray-200 overflow-y-auto">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center gap-3 text-sm">
                    <input type="checkbox" class="rounded" />
                    <button class="px-3 py-1 rounded hover:bg-gray-200 text-gray-700">Refresh</button>
                    <button class="px-3 py-1 rounded hover:bg-gray-200 text-gray-700">More</button>
                </div>
            </div>

            <ul class="divide-y divide-gray-200">
                <!-- static mail items -->
                <li class="mail-item p-4 flex items-start gap-3 hover:bg-gray-50 cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name=J+S&background=2C5F5F&color=fff"
                        class="w-10 h-10 rounded-full" alt="avatar">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <div class="font-medium text-gray-900">Jules Santiago</div>
                            <div class="text-xs text-gray-500 ml-2">
                                <jules@example.com>
                            </div>
                            <div class="ml-auto text-xs text-gray-400">Nov 6</div>
                        </div>
                        <div class="text-sm text-gray-700 mt-1">Reservation request — 2 adults, check-in Dec 20
                        </div>
                        <div class="text-xs text-gray-400 mt-2">RSV-20251106224512-1A2B3C</div>
                    </div>
                </li>

                <li class="mail-item p-4 flex items-start gap-3 bg-gray-50 cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name=A+M&background=1E3A5F&color=fff"
                        class="w-10 h-10 rounded-full" alt="avatar">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <div class="font-medium text-gray-900">Aimee Mercado</div>
                            <div class="text-xs text-gray-500 ml-2">
                                <aimee@client.com>
                            </div>
                            <div class="ml-auto text-xs text-gray-400">Nov 5</div>
                        </div>
                        <div class="text-sm text-gray-700 mt-1">Payment confirmation for booking
                            BK-20251031120000-ABC123</div>
                        <div class="text-xs text-gray-400 mt-2">Booking: BK-20251031120000-ABC123</div>
                    </div>
                </li>

                <li class="mail-item p-4 flex items-start gap-3 hover:bg-gray-50 cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name=T+R&background=E74C3C&color=fff"
                        class="w-10 h-10 rounded-full" alt="avatar">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <div class="font-medium text-gray-900">Troy Rivera</div>
                            <div class="text-xs text-gray-500 ml-2">
                                <troy@travel.com>
                            </div>
                            <div class="ml-auto text-xs text-gray-400">Oct 29</div>
                        </div>
                        <div class="text-sm text-gray-700 mt-1">Question about extra bed and breakfast options</div>
                        <div class="text-xs text-gray-400 mt-2">Ref: room 204</div>
                    </div>
                </li>

                <!-- more static items (duplicate for visuals) -->
                <li class="mail-item p-4 flex items-start gap-3 hover:bg-gray-50 cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name=G+P&background=059669&color=fff"
                        class="w-10 h-10 rounded-full" alt="avatar">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <div class="font-medium text-gray-900">Guest Payments</div>
                            <div class="text-xs text-gray-500 ml-2">
                                <noreply@payments.com>
                            </div>
                            <div class="ml-auto text-xs text-gray-400">Oct 12</div>
                        </div>
                        <div class="text-sm text-gray-700 mt-1">Refund processed for BK-20250915090000-Z9Y8X7</div>
                        <div class="text-xs text-gray-400 mt-2">Amount: ₱3,200</div>
                    </div>
                </li>

                <li class="mail-item p-4 flex items-start gap-3 hover:bg-gray-50 cursor-pointer">
                    <img src="https://ui-avatars.com/api/?name=Sys+N&background=374151&color=fff"
                        class="w-10 h-10 rounded-full" alt="avatar">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <div class="font-medium text-gray-900">System Notification</div>
                            <div class="text-xs text-gray-500 ml-2"><no-reply@site.com></div>
                            <div class="ml-auto text-xs text-gray-400">Sep 30</div>
                        </div>
                        <div class="text-sm text-gray-700 mt-1">New user registered: hearthy.delacion@example.com
                        </div>
                        <div class="text-xs text-gray-400 mt-2">User ID: 1024</div>
                    </div>
                </li>
            </ul>
        </section>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Expand/collapse preview on small screens
        (function() {
            list.forEach(item => {
                item.addEventListener('click', () => {
                    document.querySelectorAll('.mail-item').forEach(i => i.classList.remove(
                        'bg-gray-50'));
                    item.classList.add('bg-gray-50');
                })
            })
        })();
    </script>
@endsection
