<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route middleware aliases here
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         * Handle expired session / CSRF token mismatch
         * Happens when site stays open for a long time
         */
        $exceptions->render(function (TokenMismatchException $e, $request) {

            // For AJAX / fetch / API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'reload' => true,
                    'message' => 'Session expired. Reloading...'
                ], 419);
            }

            // For normal browser requests
            return redirect()->refresh();
        });

    })
    ->create();
