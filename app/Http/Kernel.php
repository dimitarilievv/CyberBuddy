<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Trust Render/edge proxy headers (X-Forwarded-Proto, etc.) so Request::isSecure() works.
        \App\Http\Middleware\TrustProxies::class,

        // ...existing code...
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // ...existing code...
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // ...existing code...
        ],
        'api' => [
            // ...existing code...
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // ...existing code...
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // ...existing code...
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        // ...existing code...
    ];
}
