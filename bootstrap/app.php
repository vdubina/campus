<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request, Throwable $exception): bool => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->report(function (Throwable $exception): void {
            $recipient = config('mail.exception_to');

            if (blank($recipient) || app()->runningUnitTests()) {
                return;
            }

            try {
                Mail::raw(
                    implode(PHP_EOL, [
                        'Application exception detected.',
                        '',
                        'App: ' . config('app.name'),
                        'URL: ' . config('app.url'),
                        'Environment: ' . app()->environment(),
                        'Class: ' . $exception::class,
                        'Message: ' . $exception->getMessage(),
                        'File: ' . $exception->getFile() . ':' . $exception->getLine(),
                        '',
                        'Trace:',
                        $exception->getTraceAsString(),
                    ]),
                    function ($message) use ($recipient, $exception): void {
                        $message
                            ->to($recipient)
                            ->subject(sprintf(
                                '[%s][%s] %s',
                                config('app.name'),
                                app()->environment(),
                                $exception::class
                            ));
                    }
                );
            } catch (Throwable $mailException) {
                Log::channel('emergency')->error('Failed to email application exception.', [
                    'original_exception' => $exception::class,
                    'mail_exception' => $mailException->getMessage(),
                ]);
            }
        });
    })->create();
