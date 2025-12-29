<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug: Log that middleware is being called
        \Log::info('AdminAuth middleware called for: ' . $request->url());
        
        if (!Auth::check()) {
            \Log::info('User not authenticated, redirecting to admin.login');
            return redirect()->route('admin.login');
        }

        if (Auth::user()->role !== 'admin') {
            \Log::info('User is not admin, logging out and redirecting');
            Auth::logout();
            return redirect()->route('admin.login')->withErrors(['access' => 'Unauthorized access.']);
        }

        \Log::info('User is authenticated admin, allowing access');
        return $next($request);
    }
}