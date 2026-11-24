<?php

namespace App\Services\Domain\Adapters;

use App\Contracts\DomainRegistrar;
use App\Services\Domain\DomainCheckResult;

/**
 * API açarı tələb etməyən pulsuz yoxlama:
 *  1) DNS-də NS/SOA qeydi varsa — domen məşğuldur
 *  2) WHOIS serveri məlumdursa (port 43) — cavab mətninə görə qərar verilir
 *  3) Əks halda "unknown" — admin sifarişdən sonra əl ilə təsdiqləyir
 *
 * Qeyd: bəzi shared hostinqlərdə 43-cü port bağlı ola bilər; o zaman yalnız DNS işləyir.
 * Dəqiq nəticə üçün reseller API-yə keçin (DOMAIN_PROVIDER=resellerclub və s.).
 */
class WhoisAdapter implements DomainRegistrar
{
    /** @var array<string, string> */
    protected array $servers = [
        'com' => 'whois.verisign-grs.com',
        'net' => 'whois.verisign-grs.com',
        'org' => 'whois.publicinterestregistry.org',
        'info' => 'whois.nic.info',
        'biz' => 'whois.nic.biz',
        'io' => 'whois.nic.io',
        'co' => 'whois.nic.co',
        'me' => 'whois.nic.me',
        'xyz' => 'whois.nic.xyz',
        'online' => 'whois.nic.online',
        'store' => 'whois.nic.store',
        'tech' => 'whois.nic.tech',
        'app' => 'whois.nic.google',
        'dev' => 'whois.nic.google',
        'eu' => 'whois.eu',
        'ru' => 'whois.tcinet.ru',
    ];

    /** WHOIS cavabında "boşdur" mənasını verən ifadələr */
    protected array $availablePatterns = [
        'no match for',
        'not found',
        'no data found',
        'no entries found',
        'status: free',
        'status: available',
        'is available for registration',
        'the queried object does not exist',
        'no object found',
        'domain not found',
    ];

    public function __construct(protected int $timeout = 5)
    {
    }

    public function name(): string
    {
        return 'whois';
    }

    public function check(array $domains): array
    {
        $results = [];

        foreach ($domains as $domain) {
            $results[$domain] = $this->checkOne($domain);
        }

        return $results;
    }

    protected function checkOne(string $domain): DomainCheckResult
    {
        if ($this->hasDnsRecords($domain)) {
            return DomainCheckResult::taken($domain, $this->name());
        }

        $tld = substr($domain, strpos($domain, '.') + 1);
        $server = $this->servers[$tld] ?? null;

        if (! $server) {
            return DomainCheckResult::unknown($domain, $this->name());
        }

        $response = $this->query($server, $domain);

        if ($response === null) {
            return DomainCheckResult::unknown($domain, $this->name());
        }

        $haystack = mb_strtolower($response);
        foreach ($this->availablePatterns as $pattern) {
            if (str_contains($haystack, $pattern)) {
                return DomainCheckResult::available($domain, $this->name());
            }
        }

        return DomainCheckResult::taken($domain, $this->name());
    }

    protected function hasDnsRecords(string $domain): bool
    {
        return @checkdnsrr($domain.'.', 'NS') || @checkdnsrr($domain.'.', 'SOA');
    }

    protected function query(string $server, string $domain): ?string
    {
        $socket = @fsockopen($server, 43, $errno, $errstr, $this->timeout);
        if (! $socket) {
            return null;
        }

        stream_set_timeout($socket, $this->timeout);
        fwrite($socket, $domain."\r\n");

        $response = '';
        while (! feof($socket) && strlen($response) < 65536) {
            $chunk = fgets($socket, 1024);
            if ($chunk === false) {
                break;
            }
            $response .= $chunk;
        }
        fclose($socket);

        return $response !== '' ? $response : null;
    }
}
