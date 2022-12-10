<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChangeHeader
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if (str_contains($response->headers->get('content-type'), 'text/html')) {
            $response->header('Cache-Control', 'no-store, private');
        }
        return $response;
    }
}
