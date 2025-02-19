<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Modules\PaymentScheduleAPI\Infrastructure\Middleware\ApiVersionMiddleware;
use Modules\PaymentScheduleAPI\Infrastructure\Middleware\RefreshTokenMiddleware;
use Modules\Shared\Infrastructure\Middleware\ResponseTimeMiddleware;
use Modules\Shared\Infrastructure\Middleware\LogHttpErrors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', ApiVersionMiddleware::class);
        $middleware->appendToGroup('api', RefreshTokenMiddleware::class);
        $middleware->appendToGroup('api', ResponseTimeMiddleware::class);
        $middleware->appendToGroup('web', ResponseTimeMiddleware::class);
        $middleware->appendToGroup('api', LogHttpErrors::class);
        $middleware->appendToGroup('web', LogHttpErrors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
