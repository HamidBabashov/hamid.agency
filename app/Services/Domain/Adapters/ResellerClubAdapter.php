<?php

namespace App\Services\Domain\Adapters;

use App\Contracts\DomainRegistrar;
use App\Services\Domain\DomainCheckResult;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * ResellerClub (LogicBoxes) HTTP API adapteri.
 * Sənəd: https://manage.resellerclub.com/kb/answer/764
 *
 * .env: DOMAIN_PROVIDER=resellerclub, RESELLERCLUB_AUTH_USERID, RESELLERCLUB_API_KEY, RESELLERCLUB_SANDBOX
 * Qeyd: ResellerClub API yalnız icazə verilmiş (whitelist) IP ünvanlarından sorğu qəbul edir.
 */
class ResellerClubAdapter implements DomainRegistrar
{
    public function __construct(protected array $config)
    {
    }

    public function name(): string
    {
        return 'resellerclub';
    }

    public function check(array $domains): array
    {
        if (blank($this->config['auth_userid'] ?? null) || blank($this->config['api_key'] ?? null)) {
            throw new RuntimeException('ResellerClub credentials are not configured.');
        }

        $base = ($this->config['sandbox'] ?? true) ? 'https://test.httpapi.com/api' : 'https://httpapi.com/api';

        $slds = $tlds = [];
        foreach ($domains as $domain) {
            [$sld, $tld] = explode('.', $domain, 2);
            $slds[$sld] = true;
            $tlds[$tld] = true;
        }

        // API eyni adlı parametrlərin təkrarlanmasını tələb edir: domain-name=a&tlds=com&tlds=az
        $query = http_build_query(['auth-userid' => $this->config['auth_userid'], 'api-key' => $this->config['api_key']]);
        foreach (array_keys($slds) as $sld) {
            $query .= '&domain-name='.urlencode($sld);
        }
        foreach (array_keys($tlds) as $tld) {
            $query .= '&tlds='.urlencode($tld);
        }

        $data = Http::timeout(10)->acceptJson()->get("{$base}/domains/available.json?{$query}")->throw()->json();

        $results = [];
        foreach ($domains as $domain) {
            $status = $data[$domain]['status'] ?? null;
            $results[$domain] = match ($status) {
                'available' => DomainCheckResult::available($domain, $this->name()),
                'regthroughus', 'regthroughothers' => DomainCheckResult::taken($domain, $this->name()),
                default => DomainCheckResult::unknown($domain, $this->name()),
            };
        }

        return $results;
    }
}
