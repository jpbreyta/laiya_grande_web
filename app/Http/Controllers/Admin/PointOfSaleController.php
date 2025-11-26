<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointOfSale;
use Illuminate\Http\Request;

class PointOfSaleController extends Controller
{
    public function index()
    {
        // Fetch all transactions with the associated Guest info
        // Ordered by latest first
        $transactions = PointOfSale::with('guestStay')
                        ->orderBy('created_at', 'desc')
                        ->paginate(15);

        return view('admin.pos.index', compact('transactions'));
    }

    // We removed 'create', 'store', 'edit' methods because 
    // you are doing all inputs via the Java POS.
}