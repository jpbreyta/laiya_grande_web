@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Point of Sale Transactions</h1>
        <span class="badge badge-info p-2">Data synced from Java POS</span>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transaction History</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Guest Name</th>
                            <th>Room</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $txn)
                        <tr>
                            <td>{{ $txn->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                @if($txn->guestStay)
                                    {{ $txn->guestStay->guest_name }}
                                @else
                                    <span class="text-danger">Guest Checkout/Unknown</span>
                                @endif
                            </td>
                            <td>
                                @if($txn->guestStay && $txn->guestStay->room)
                                    {{ $txn->guestStay->room->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $txn->item_name }}</td>
                            <td>
                                <span class="badge badge-{{ $txn->item_type == 'rental' ? 'warning' : 'primary' }}">
                                    {{ ucfirst($txn->item_type) }}
                                </span>
                            </td>
                            <td>{{ $txn->quantity }}</td>
                            <td class="font-weight-bold">₱{{ number_format($txn->total_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No transactions found from Java POS yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection