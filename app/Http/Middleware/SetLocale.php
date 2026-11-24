<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const COOKIE = 'locale';

    public function handle(Request $request, Closure $next, ?string $locale = null): Response
    {
        $locales = config('agency.locales');
        $locale = in_array($locale, $locales, true) ? $locale : config('agency.default_locale');

        \App\Support\LocaleUrls::reset();
        App::setLocale($locale);
        Carbon::setLocale($locale);

        // Seçilmiş dili yadda saxla (1 il)
        if ($request->cookie(self::COOKIE) !== $locale) {
            Cookie::queue(self::COOKIE, $locale, 60 * 24 * 365, null, null, null, false);
        }

        return $next($request);
    }
}
