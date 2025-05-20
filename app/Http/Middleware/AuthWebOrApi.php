<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthWebOrApi
{
    public function handle(Request $request, Closure $next): \Illuminate\Http\JsonResponse
    {
        if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
