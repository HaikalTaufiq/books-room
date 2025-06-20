<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        // middleware bawaan Laravel
        'auth' => \App\Http\Middleware\Authenticate::class,
        'isAdmin' => \App\Http\Middleware\IsAdmin::class,
        'isEmployee' => \App\Http\Middleware\IsEmployee::class,
    ];
}
