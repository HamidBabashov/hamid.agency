<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\ProtectAgainstSpam;
use App\Http\Middleware\SetLocale;
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
            'locale' => SetLocale::class,
            'spam' => ProtectAgainstSpam::class,
            'admin' => EnsureUserIsAdmin::class,
        ]);

        // Dil cookie-si JS tərəfindən də oxuna bilsin, şifrələnməsin
        $middleware->encryptCookies(except: ['locale']);

        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));

        $middleware->validateCsrfTokens(except: ['_deploy/post-deploy']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
