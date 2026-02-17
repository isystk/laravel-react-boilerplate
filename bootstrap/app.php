<?php

use App\Http\Middleware\AccessControlMiddleware;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
            ->append(SecurityHeadersMiddleware::class);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'access_control' => AccessControlMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
