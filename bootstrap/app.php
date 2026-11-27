<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\ProtectAgainstSpam;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

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

        // Admin və müştəri hissələri üçün ayrı giriş/yönləndirmə ünvanları
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin', 'admin/*')) {
                return route('admin.login');
            }

            $locale = in_array($request->segment(1), config('agency.locales'), true) ? $request->segment(1) : config('agency.default_locale');

            return lroute('login', [], $locale);
        });
        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('admin', 'admin/*')) {
                return $request->user()?->is_admin ? route('admin.dashboard') : lroute('account', [], config('agency.default_locale'));
            }

            return lroute('account', [], app()->getLocale());
        });

        $middleware->validateCsrfTokens(except: ['_deploy/post-deploy']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
