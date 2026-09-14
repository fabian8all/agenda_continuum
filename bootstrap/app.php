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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'verify.auth' => \App\Http\Middleware\VerifyAuthSaml::class,
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);

        // El IdP hace un POST sin token CSRF al Assertion Consumer Service.
        $middleware->validateCsrfTokens(except: [
            'saml2/*/acs',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
