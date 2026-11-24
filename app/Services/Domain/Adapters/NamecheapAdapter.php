<?php

namespace App\Services\Domain\Adapters;

use App\Contracts\DomainRegistrar;
use App\Services\Domain\DomainCheckResult;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Namecheap API adapteri (namecheap.domains.check).
 * Sənəd: https://www.namecheap.com/support/api/methods/domains/check/
 *
 * .env: DOMAIN_PROVIDER=namecheap, NAMECHEAP_API_USER, NAMECHEAP_API_KEY, NAMECHEAP_USERNAME,
 *       NAMECHEAP_CLIENT_IP (serverin IP-si, Namecheap-də whitelist edilməlidir), NAMECHEAP_SANDBOX
 * Qeyd: Namecheap .az uzantısını dəstəkləmir — belə domenlər "unknown" qayıdır.
 */
class NamecheapAdapter implements DomainRegistrar
{
    public function __construct(protected array $config)
    {
    }

    public function name(): string
    {
        return 'namecheap';
    }

    public function check(array $domains): array
    {
        foreach (['api_user', 'api_key', 'username', 'client_ip'] as $key) {
            if (blank($this->config[$key] ?? null)) {
                throw new RuntimeException('Namecheap credentials are not configured.');
            }
        }

        $url = ($this->config['sandbox'] ?? true)
            ? 'https://api.sandbox.namecheap.com/xml.response'
            : 'https://api.namecheap.com/xml.response';

        $body = Http::timeout(10)->get($url, [
            'ApiUser' => $this->config['api_user'],
            'ApiKey' => $this->config['api_key'],
            'UserName' => $this->config['username'],
            'ClientIp' => $this->config['client_ip'],
            'Command' => 'namecheap.domains.check',
            'DomainList' => implode(',', $domains),
        ])->throw()->body();

        $results = array_fill_keys($domains, null);

        $xml = @simplexml_load_string($body);
        if ($xml !== false) {
            $xml->registerXPathNamespace('nc', 'http://api.namecheap.com/xml.response');
            foreach ($xml->xpath('//nc:DomainCheckResult') ?: [] as $node) {
                $domain = strtolower((string) $node['Domain']);
                if (array_key_exists($domain, $results)) {
                    $results[$domain] = strtolower((string) $node['Available']) === 'true'
                        ? DomainCheckResult::available($domain, $this->name())
                        : DomainCheckResult::taken($domain, $this->name());
                }
            }
        }

        foreach ($results as $domain => $result) {
            $results[$domain] = $result ?? DomainCheckResult::unknown($domain, $this->name());
        }

        return $results;
    }
}
