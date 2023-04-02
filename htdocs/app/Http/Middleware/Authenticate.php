<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        if (! $request->expectsJson()) {

            $uri = $request->path();

            if(Str::startsWith($uri, ['admin/'])) {
                // URIが以下adminから始まる場合
                return '/admin/login';
            }

            return '/login';
        }
    }
}
