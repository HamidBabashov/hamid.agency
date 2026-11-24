<?php

namespace Tests\Feature;

use App\Http\Requests\QuoteRequest;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Tərcümələrin tamlığını yoxlayır:
 *  1) lang/az və lang/en eyni fayllara və eyni açarlara malikdir
 *  2) Koddakı bütün __('...') açarları hər iki dildə mövcuddur
 *  3) Heç bir tərcümə boş deyil
 */
class TranslationCompletenessTest extends TestCase
{
    public function test_both_locales_have_the_same_files(): void
    {
        $az = collect(File::files(lang_path('az')))->map->getFilename()->sort()->values()->all();
        $en = collect(File::files(lang_path('en')))->map->getFilename()->sort()->values()->all();

        $this->assertSame($az, $en);
    }

    public function test_both_locales_have_identical_keys_and_no_empty_values(): void
    {
        foreach (File::files(lang_path('az')) as $file) {
            $name = $file->getFilename();
            $az = Arr::dot(require lang_path('az/'.$name));
            $en = Arr::dot(require lang_path('en/'.$name));

            $this->assertSame([], array_values(array_diff(array_keys($az), array_keys($en))), "Missing in en/{$name}");
            $this->assertSame([], array_values(array_diff(array_keys($en), array_keys($az))), "Missing in az/{$name}");

            foreach (['az' => $az, 'en' => $en] as $locale => $values) {
                foreach ($values as $key => $value) {
                    if (is_string($value)) {
                        $this->assertNotSame('', trim($value), "Empty translation {$locale}/{$name}: {$key}");
                    }
                }
            }
        }
    }

    public function test_every_translation_key_used_in_code_exists(): void
    {
        $files = collect(File::allFiles(resource_path('views')))
            ->merge(File::allFiles(app_path()))
            ->filter(fn ($f) => str_ends_with($f->getFilename(), '.php'));

        $missing = [];

        foreach ($files as $file) {
            preg_match_all("/(?:__|trans|@lang)\(\s*'([a-z_]+\.[A-Za-z0-9_.]+)'/", $file->getContents(), $matches);

            foreach ($matches[1] as $key) {
                // Dinamik açarlar ('site.service_types.'.$x) nöqtə ilə bitir — prefiks kimi yoxlanılır
                $key = rtrim($key, '.');
                foreach (config('agency.locales') as $locale) {
                    if (! trans()->has($key, $locale, false)) {
                        $missing[] = "{$locale}: {$key} ({$file->getRelativePathname()})";
                    }
                }
            }
        }

        $this->assertSame([], array_values(array_unique($missing)));
    }

    public function test_dynamic_option_groups_are_translated(): void
    {
        foreach (QuoteRequest::OPTIONS as $group => $values) {
            foreach ($values as $value) {
                foreach (config('agency.locales') as $locale) {
                    $this->assertTrue(trans()->has("quote.options.{$group}.{$value}", $locale, false), "{$locale}: quote.options.{$group}.{$value}");
                }
            }
        }

        foreach ([...Service::KEYS, 'other'] as $key) {
            foreach (config('agency.locales') as $locale) {
                $this->assertTrue(trans()->has("site.service_types.{$key}", $locale, false));
            }
        }

        foreach (Order::STATUSES as $status) {
            foreach (config('agency.locales') as $locale) {
                $this->assertTrue(trans()->has("admin.statuses.{$status}", $locale, false));
            }
        }
    }
}
