<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

if (! function_exists('lroute')) {
    /**
     * Dilə uyğun marşrut URL-i: lroute('contact') => /az/elaqe və ya /en/contact
     */
    function lroute(string $name, array $parameters = [], ?string $locale = null, bool $absolute = true): string
    {
        $locale ??= App::getLocale();

        return route("{$locale}.{$name}", $parameters, $absolute);
    }
}

if (! function_exists('current_route_base')) {
    /** "az.contact" => "contact" */
    function current_route_base(): ?string
    {
        $name = Route::currentRouteName();
        if (! $name) {
            return null;
        }

        $parts = explode('.', $name, 2);

        return in_array($parts[0], config('agency.locales'), true) ? ($parts[1] ?? null) : null;
    }
}

if (! function_exists('money')) {
    function money($amount): string
    {
        return number_format((float) $amount, 2, '.', ' ').' '.config('agency.currency_symbol');
    }
}

if (! function_exists('upload_url')) {
    function upload_url(?string $path): ?string
    {
        return $path ? asset('uploads/'.$path) : null;
    }
}
