<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Src\Domains\Common\Exceptions\TooManyAttemptsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        $exceptions->render(function (TooManyAttemptsException $e, Request $request) {
            return response()
                ->json([
                    'message' => 'Too many requests.',
                    'errors' => [
                        'rate_limit' => [
                            'Too many requests. Please retry later.',
                        ],
                    ],
                    'meta' => [
                        'retry_after_seconds' => $e->retryAfterSeconds,
                    ],
                ], 429)
                ->header('Retry-After', (string) $e->retryAfterSeconds);
        });

    })->create();
