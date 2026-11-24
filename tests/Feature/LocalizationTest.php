<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    private const PAGES = ['home', 'services.index', 'hosting', 'domains', 'portfolio', 'about', 'contact', 'quote', 'privacy', 'terms'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedContent();
    }

    public function test_root_redirects_to_default_locale(): void
    {
        $this->get('/')->assertRedirect('/az');
    }

    public function test_root_redirects_to_remembered_locale(): void
    {
        $this->withUnencryptedCookie('locale', 'en')->get('/')->assertRedirect('/en');
    }

    public function test_visiting_a_locale_remembers_it_in_a_cookie(): void
    {
        $this->get('/en')->assertCookie('locale', 'en', false);
    }

    public function test_all_pages_render_in_both_languages_with_seo_tags(): void
    {
        foreach (config('agency.locales') as $locale) {
            foreach (self::PAGES as $page) {
                $response = $this->get(lroute($page, [], $locale));
                $response->assertOk();
                $response->assertSee('<html lang="'.$locale.'"', false);
                $response->assertSee('hreflang="az"', false);
                $response->assertSee('hreflang="en"', false);
                $response->assertSee('hreflang="x-default"', false);
                $response->assertSee('property="og:title"', false);
                $response->assertSee('"@type":"Organization"', false);
                $response->assertSee('<meta name="description" content="', false);
            }
        }
    }

    public function test_page_titles_are_localized(): void
    {
        $this->get('/az/hosting')->assertSee(__('seo.hosting.title', [], 'az'));
        $this->get('/en/hosting')->assertSee(__('seo.hosting.title', [], 'en'));
        $this->get('/az/elaqe')->assertOk();
        $this->get('/en/contact')->assertOk();
    }

    public function test_service_pages_use_localized_slugs_and_alternates(): void
    {
        foreach (Service::all() as $service) {
            $az = $this->get($service->url('az'))->assertOk();
            $az->assertSee('hreflang="en" href="'.$service->url('en').'"', false);
            $az->assertSee('"@type":"Service"', false);

            $this->get($service->url('en'))->assertOk()
                ->assertSee('hreflang="az" href="'.$service->url('az').'"', false);
        }

        // Başqa dilin slug-ı bu dildə işləmir
        $this->get('/az/xidmetler/web-design')->assertNotFound();
    }

    public function test_inactive_service_is_hidden(): void
    {
        $service = Service::first();
        $service->update(['is_active' => false]);

        $this->get($service->url('az'))->assertNotFound();
    }

    public function test_sitemap_lists_both_languages(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));

        foreach (self::PAGES as $page) {
            $response->assertSee('<loc>'.lroute($page, [], 'az').'</loc>', false);
            $response->assertSee('<loc>'.lroute($page, [], 'en').'</loc>', false);
        }
    }

    public function test_unknown_page_returns_localized_404(): void
    {
        $this->get('/en/does-not-exist')->assertNotFound()->assertSee(__('site.errors.404_title', [], 'en'));
        $this->get('/az/movcud-deyil')->assertNotFound()->assertSee(__('site.errors.404_title', [], 'az'));
    }
}
