<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is logged in as a customer
        if ($request->user() && $request->user()->role === 'customer') {
            return $next($request);
        }

        // Redirect or return response based on your requirement
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}
