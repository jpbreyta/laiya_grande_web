<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function welcome(Request $request)
    {
        // Log the request metadata
        Log::info("Request received: {$request->method()} {$request->path()}");

        // Return JSON response with welcome message
        return response()->json(['message' => 'Welcome to the Laravel API Service!']);
    }
}
