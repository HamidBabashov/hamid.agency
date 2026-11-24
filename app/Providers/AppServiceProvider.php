<?php

namespace App\Providers;

use App\Contracts\DomainRegistrar;
use App\Models\Service;
use App\Services\Domain\Adapters\NamecheapAdapter;
use App\Services\Domain\Adapters\ResellerClubAdapter;
use App\Services\Domain\Adapters\WhmcsAdapter;
use App\Services\Domain\Adapters\WhoisAdapter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Domen provayderi .env-dəki DOMAIN_PROVIDER dəyərinə görə seçilir
        $this->app->bind(DomainRegistrar::class, function () {
            $provider = config('domain.provider');
            $config = config("domain.providers.{$provider}", []);

            return match ($provider) {
                'whois' => new WhoisAdapter((int) ($config['timeout'] ?? 5)),
                'resellerclub' => new ResellerClubAdapter($config),
                'namecheap' => new NamecheapAdapter($config),
                'whmcs' => new WhmcsAdapter($config),
                default => throw new InvalidArgumentException("Unknown domain provider [{$provider}]."),
            };
        });
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');

        // Header və footer menyusu üçün xidmətlər (sorğu başına bir dəfə)
        View::composer(['partials.header', 'partials.footer'], function ($view) {
            $view->with('navServices', once(fn () => Service::active()->get()));
        });

        // Formalar: dəqiqədə 5, gündə 30 göndəriş (IP üzrə)
        RateLimiter::for('forms', fn (Request $request) => [
            Limit::perMinute(5)->by('forms:'.$request->ip()),
            Limit::perDay(30)->by('forms-day:'.$request->ip()),
        ]);

        RateLimiter::for('domain-check', fn (Request $request) => Limit::perMinute(20)->by('domain:'.$request->ip()));

        RateLimiter::for('admin-login', fn (Request $request) => Limit::perMinute(5)->by('login:'.$request->ip()));
    }
}
