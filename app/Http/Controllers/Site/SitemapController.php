<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /** Səhifələr və hər dil üçün alternativ ünvanlar (hreflang). */
    public function __invoke(): Response
    {
        $xml = Cache::remember('sitemap.xml', 3600, function () {
            $locales = config('agency.locales');
            $entries = [];

            foreach (['home', 'services.index', 'hosting', 'domains', 'portfolio', 'about', 'contact', 'quote', 'privacy', 'terms'] as $name) {
                $entries[] = collect($locales)->mapWithKeys(fn ($l) => [$l => lroute($name, [], $l)])->all();
            }

            foreach (Service::active()->get() as $service) {
                $entries[] = collect($locales)->mapWithKeys(fn ($l) => [$l => $service->url($l)])->all();
            }

            return view('sitemap', ['entries' => $entries, 'codes' => config('agency.locale_codes')])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
