<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PosTransaction;

class PointOfSaleController extends Controller
{
    public function index()
    {
        // Fetch all transactions grouped by receipt
        $transactions = PosTransaction::with(['guestStay.room'])
                        ->orderBy('transaction_date', 'desc')
                        ->paginate(15);

        return view('admin.pos.index', compact('transactions'));
    }

    public function showTransaction($id)
    {
        // Show detailed receipt
        $transaction = PosTransaction::with(['guestStay.room', 'posItems'])
                        ->findOrFail($id);

        return view('admin.pos.show', compact('transaction'));
    }
}