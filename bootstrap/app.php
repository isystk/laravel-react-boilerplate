<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware
            ->trustProxies(at: '*')
            ->validateCsrfTokens(except: [
                'api/*',
            ])
            ->redirectGuestsTo(function (Request $request) {
                if (request()->routeIs('admin.*')) {
                    return $request->expectsJson() ? null : route('admin.login');
                }

                return null;
            });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
