<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
     
        if ($request->user() && $request->user()->isSeller()) {
            return $next($request);
        }
        
        return response()->json(['message' => 'bukan seller', 'status' => 401]);
}
}
