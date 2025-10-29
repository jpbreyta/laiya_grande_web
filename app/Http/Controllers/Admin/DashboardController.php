<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard page.
     */
    public function index()
    {
        // You can pass data here later, like stats or user info
        return view('admin.dashboard.index');
    }
}
