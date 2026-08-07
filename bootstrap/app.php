<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminPlastani::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle critical exceptions and send notifications
        $exceptions->report(function (Throwable $e) {
            // Log critical errors only (not validation exceptions)
            if (!($e instanceof \Illuminate\Validation\ValidationException)) {
                try {
                    $service = app(\App\Services\ErrorNotificationService::class);
                    $service->notifyError($e, 'Critical Application Error');
                } catch (Throwable $notificationError) {
                    \Illuminate\Support\Facades\Log::error('Error notification service failed', [
                        'error' => $notificationError->getMessage(),
                    ]);
                }
            }
        });
    })->create();
