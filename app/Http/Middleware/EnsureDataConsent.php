<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureDataConsent
{
    /**
     * Handle an incoming request.
     * Ensure customer has given data consent before processing
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (Auth::check() && Auth::user()->customer) {
            $customer = Auth::user()->customer;
            
            if (!$customer->data_consent) {
                return redirect()->route('consent.form')
                    ->with('warning', 'Please accept our privacy policy to continue.');
            }
        }

        return $next($request);
    }
}
