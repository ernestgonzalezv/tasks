<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e) {
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $message = implode('. ', array_merge(...array_values($e->errors())));
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'data' => null,
                ], 400);
            }

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'data' => null,
            ], 500);
        });
    })->create();
