<?php

namespace App\Services\Domain\Adapters;

use App\Contracts\DomainRegistrar;
use App\Services\Domain\DomainCheckResult;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * WHMCS API adapteri (DomainWhois əmri).
 * Sənəd: https://developers.whmcs.com/api-reference/domainwhois/
 *
 * .env: DOMAIN_PROVIDER=whmcs, WHMCS_URL, WHMCS_API_IDENTIFIER, WHMCS_API_SECRET
 * Qeyd: WHMCS-də API Credentials yaradın və serverin IP-sini "API IP Access Restriction"-a əlavə edin.
 */
class WhmcsAdapter implements DomainRegistrar
{
    public function __construct(protected array $config)
    {
    }

    public function name(): string
    {
        return 'whmcs';
    }

    public function check(array $domains): array
    {
        foreach (['url', 'identifier', 'secret'] as $key) {
            if (blank($this->config[$key] ?? null)) {
                throw new RuntimeException('WHMCS credentials are not configured.');
            }
        }

        $endpoint = rtrim($this->config['url'], '/').'/includes/api.php';

        $responses = Http::pool(fn (Pool $pool) => array_map(
            fn (string $domain) => $pool->as($domain)->asForm()->timeout(10)->post($endpoint, [
                'action' => 'DomainWhois',
                'identifier' => $this->config['identifier'],
                'secret' => $this->config['secret'],
                'domain' => $domain,
                'responsetype' => 'json',
            ]),
            $domains
        ));

        $results = [];
        foreach ($domains as $domain) {
            $response = $responses[$domain] ?? null;
            $data = ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) ? $response->json() : null;

            $results[$domain] = match (($data['result'] ?? null) === 'success' ? ($data['status'] ?? null) : null) {
                'available' => DomainCheckResult::available($domain, $this->name()),
                'unavailable' => DomainCheckResult::taken($domain, $this->name()),
                default => DomainCheckResult::unknown($domain, $this->name()),
            };
        }

        return $results;
    }
}
