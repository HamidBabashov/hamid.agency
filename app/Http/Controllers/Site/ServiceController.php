<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\DomainExtension;
use App\Models\HostingPlan;
use App\Models\Service;
use App\Support\LocaleUrls;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services.index', ['services' => Service::active()->get()]);
    }

    public function show(string $slug): View
    {
        $locale = App::getLocale();
        $service = Service::where('is_active', true)->where("slug_{$locale}", $slug)->firstOrFail();

        // Dil dəyişdirici və hreflang üçün digər dildəki slug
        LocaleUrls::set(collect(config('agency.locales'))
            ->mapWithKeys(fn ($l) => [$l => $service->url($l)])
            ->all());

        return view('services.show', [
            'service' => $service,
            'plans' => $service->key === 'hosting' ? HostingPlan::active()->get() : collect(),
            'extensions' => $service->key === 'domain' ? DomainExtension::active()->get() : collect(),
            'otherServices' => Service::active()->where('id', '!=', $service->id)->get(),
        ]);
    }
}
