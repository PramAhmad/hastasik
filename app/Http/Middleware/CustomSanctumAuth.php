<?php

// app/Http/Middleware/CustomSanctumAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomSanctumAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('sanctum')->guest()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return $next($request);
    }
}
