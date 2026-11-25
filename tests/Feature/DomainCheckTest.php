<?php

namespace Tests\Feature;

use App\Contracts\DomainRegistrar;
use App\Providers\AppServiceProvider;
use App\Services\Domain\DomainAvailabilityService;
use App\Services\Domain\DomainCheckResult;
use App\Services\Domain\InvalidDomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    use RefreshDatabase;

    public FakeRegistrar $registrar;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedContent();
        $this->registrar = new FakeRegistrar(['hamid.com' => DomainCheckResult::TAKEN]);
        $this->app->instance(DomainRegistrar::class, $this->registrar);
    }

    public function test_search_without_extension_checks_featured_extensions(): void
    {
        $response = $this->getJson('/az/domenler/check?domain=hamid')->assertOk();

        $results = collect($response->json('results'))->keyBy('domain');
        $this->assertSame(['hamid.az', 'hamid.com', 'hamid.net', 'hamid.org'], $results->keys()->all());
        $this->assertSame('available', $results['hamid.az']['status']);
        $this->assertSame('taken', $results['hamid.com']['status']);
        $this->assertSame(__('domains.status.taken', [], 'az'), $results['hamid.com']['status_label']);
        $this->assertSame(money(40), $results['hamid.az']['register_price']);
        $this->assertStringContainsString('/az/qiymet-teklifi?service=domain&domain=hamid.az', $results['hamid.az']['order_url']);
    }

    public function test_search_normalizes_input_and_puts_requested_extension_first(): void
    {
        $response = $this->getJson('/en/domains/check?domain='.urlencode('https://www.Hamid.NET/path'))->assertOk();

        $this->assertSame('hamid.net', $response->json('results.0.domain'));
        $this->assertSame(__('domains.status.available', [], 'en'), $response->json('results.0.status_label'));
    }

    public function test_invalid_and_unsupported_domains_return_422(): void
    {
        $this->getJson('/az/domenler/check?domain='.urlencode('bad_name!'))
            ->assertStatus(422)->assertJson(['message' => __('domains.errors.invalid', [], 'az')]);

        $this->getJson('/en/domains/check?domain=hamid.xyz')
            ->assertStatus(422)->assertJson(['message' => __('domains.errors.unsupported', ['ext' => '.xyz'], 'en')]);

        $this->getJson('/az/domenler/check')->assertStatus(422);
    }

    public function test_results_are_cached(): void
    {
        $this->getJson('/az/domenler/check?domain=cache-test.az')->assertOk();
        $calls = $this->registrar->calls;
        $this->getJson('/az/domenler/check?domain=cache-test.az')->assertOk();

        $this->assertSame($calls, $this->registrar->calls);
    }

    public function test_provider_failure_returns_unknown_instead_of_error(): void
    {
        $this->registrar->fail = true;

        $response = $this->getJson('/az/domenler/check?domain=failing.az')->assertOk();
        $this->assertSame('unknown', $response->json('results.0.status'));
    }

    public function test_domain_check_is_rate_limited(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->getJson('/az/domenler/check?domain=a'.$i)->assertOk();
        }

        $this->getJson('/az/domenler/check?domain=last')->assertStatus(429);
    }

    public function test_parser(): void
    {
        $service = app(DomainAvailabilityService::class);

        $this->assertSame(['example', 'com.az'], $service->parse('  WWW.Example.com.az/ '));
        $this->assertSame(['my-site', null], $service->parse('my-site'));

        $this->expectException(InvalidDomainException::class);
        $service->parse('-invalid-');
    }

    public function test_configured_provider_is_resolved_from_env(): void
    {
        $this->app->forgetInstance(DomainRegistrar::class);
        $this->app->offsetUnset(DomainRegistrar::class);
        (new AppServiceProvider($this->app))->register();

        foreach (['whois' => 'whois', 'resellerclub' => 'resellerclub', 'namecheap' => 'namecheap', 'whmcs' => 'whmcs'] as $provider => $name) {
            config(['domain.provider' => $provider]);
            $this->assertSame($name, app(DomainRegistrar::class)->name());
        }
    }
}

class FakeRegistrar implements DomainRegistrar
{
    public int $calls = 0;

    public bool $fail = false;

    public function __construct(private array $statuses = []) {}

    public function name(): string
    {
        return 'fake';
    }

    public function check(array $domains): array
    {
        $this->calls++;

        if ($this->fail) {
            throw new \RuntimeException('Provider down');
        }

        return collect($domains)->mapWithKeys(fn ($d) => [$d => new DomainCheckResult($d, $this->statuses[$d] ?? DomainCheckResult::AVAILABLE, 'fake')])->all();
    }
}
