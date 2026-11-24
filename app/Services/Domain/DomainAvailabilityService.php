<?php

namespace App\Services\Domain;

use App\Contracts\DomainRegistrar;
use App\Models\DomainExtension;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Domen axtarışının əsas məntiqi:
 *  - istifadəçi daxil etdiyi mətni təmizləyir (http://, www., boşluq və s.)
 *  - uzantı yazılmayıbsa, admin paneldə "seçilmiş" uzantıları yoxlayır
 *  - yalnız saytda satılan (aktiv) uzantılara icazə verir
 *  - nəticələri keşləyir və qiymətlə birlikdə qaytarır
 */
class DomainAvailabilityService
{
    public function __construct(private DomainRegistrar $registrar)
    {
    }

    /**
     * @return array<int, array{domain: string, extension: string, status: string, register_price: ?string, renew_price: ?string}>
     */
    public function search(string $input): array
    {
        [$sld, $tld] = $this->parse($input);

        $extensions = DomainExtension::active()->get()->keyBy('extension');

        if ($extensions->isEmpty()) {
            throw new InvalidDomainException(__('domains.errors.no_extensions'));
        }

        $tlds = $this->resolveTlds($tld, $extensions);
        $domains = array_map(fn ($ext) => "{$sld}.{$ext}", $tlds);

        $results = $this->checkWithCache($domains);

        return collect($tlds)->map(function (string $ext) use ($sld, $results, $extensions) {
            $domain = "{$sld}.{$ext}";
            $extension = $extensions->get($ext);

            return [
                'domain' => $domain,
                'extension' => $ext,
                'status' => ($results[$domain] ?? DomainCheckResult::unknown($domain))->status,
                'register_price' => $extension ? money($extension->register_price) : null,
                'renew_price' => $extension ? money($extension->renew_price) : null,
            ];
        })->values()->all();
    }

    /**
     * "https://www.Hamid.AZ/" => ["hamid", "az"], "hamid" => ["hamid", null]
     *
     * @return array{0: string, 1: ?string}
     */
    public function parse(string $input): array
    {
        $value = mb_strtolower(trim($input));
        $value = preg_replace('#^[a-z]+://#', '', $value);
        $value = preg_replace('#[/?\#].*$#', '', $value);
        $value = preg_replace('#^www\.#', '', $value);
        $value = trim($value, '. ');

        if (function_exists('idn_to_ascii') && preg_match('/[^\x20-\x7e]/', $value)) {
            $value = idn_to_ascii($value, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46) ?: $value;
        }

        if ($value === '') {
            throw new InvalidDomainException(__('domains.errors.empty'));
        }

        $parts = explode('.', $value, 2);
        $sld = $parts[0];
        $tld = $parts[1] ?? null;

        if (! preg_match('/^(?!-)[a-z0-9-]{1,63}(?<!-)$/', $sld)) {
            throw new InvalidDomainException(__('domains.errors.invalid'));
        }

        if ($tld !== null && ! preg_match('/^[a-z0-9-]+(\.[a-z0-9-]+)?$/', $tld)) {
            throw new InvalidDomainException(__('domains.errors.invalid'));
        }

        return [$sld, $tld];
    }

    /** @return array<int, string> */
    private function resolveTlds(?string $tld, Collection $extensions): array
    {
        if ($tld !== null) {
            if (! $extensions->has($tld)) {
                throw new InvalidDomainException(__('domains.errors.unsupported', ['ext' => '.'.$tld]));
            }

            // Əvvəl istənilən uzantı, sonra digər seçilmiş uzantılar (təklif kimi)
            $others = $extensions->where('is_featured', true)->keys()->reject(fn ($e) => $e === $tld);

            return collect([$tld])->merge($others)->take(config('domain.max_suggestions'))->values()->all();
        }

        $featured = $extensions->where('is_featured', true)->keys();

        return ($featured->isNotEmpty() ? $featured : $extensions->keys())
            ->take(config('domain.max_suggestions'))
            ->values()
            ->all();
    }

    /** @return array<string, DomainCheckResult> */
    private function checkWithCache(array $domains): array
    {
        $results = [];
        $missing = [];

        foreach ($domains as $domain) {
            $cached = Cache::get($this->cacheKey($domain));
            $cached ? $results[$domain] = new DomainCheckResult($domain, $cached, $this->registrar->name()) : $missing[] = $domain;
        }

        if ($missing) {
            try {
                $fresh = $this->registrar->check($missing);
            } catch (Throwable $e) {
                Log::error('Domain check failed', ['provider' => $this->registrar->name(), 'error' => $e->getMessage()]);
                $fresh = [];
            }

            foreach ($missing as $domain) {
                $result = $fresh[$domain] ?? DomainCheckResult::unknown($domain, $this->registrar->name());
                $results[$domain] = $result;

                if ($result->status !== DomainCheckResult::UNKNOWN) {
                    Cache::put($this->cacheKey($domain), $result->status, config('domain.cache_ttl'));
                }
            }
        }

        return $results;
    }

    private function cacheKey(string $domain): string
    {
        return 'domain-check:'.$this->registrar->name().':'.$domain;
    }
}
