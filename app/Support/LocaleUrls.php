<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * Cari səhifənin digər dillərdəki ünvanları (hreflang və dil dəyişdirici üçün).
 */
class LocaleUrls
{
    private static ?array $override = null;

    /** Kontroller xüsusi URL-lər təyin edə bilər (məs. dilə görə fərqli slug). */
    public static function set(array $urls): void
    {
        self::$override = $urls;
    }

    public static function reset(): void
    {
        self::$override = null;
    }

    /** @return array<string, string> locale => url */
    public static function all(): array
    {
        if (self::$override !== null) {
            return self::$override;
        }

        $base = current_route_base();
        $route = Route::current();
        $urls = [];

        foreach (config('agency.locales') as $locale) {
            $urls[$locale] = ($base && $route && Route::has("{$locale}.{$base}"))
                ? route("{$locale}.{$base}", $route->parameters())
                : route("{$locale}.home");
        }

        return $urls;
    }
}
