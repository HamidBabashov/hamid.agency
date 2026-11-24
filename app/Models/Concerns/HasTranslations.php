<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\App;

/**
 * Tərcümə olunan sahələr "{field}_{locale}" sütunlarında saxlanılır (məs. title_az, title_en).
 * $model->t('title') cari dilə uyğun dəyəri qaytarır, boşdursa əsas dilə (az) düşür.
 */
trait HasTranslations
{
    public function t(string $field, ?string $locale = null): ?string
    {
        $locale ??= App::getLocale();
        $value = $this->getAttribute("{$field}_{$locale}");

        if (blank($value)) {
            $value = $this->getAttribute("{$field}_".config('agency.default_locale'));
        }

        return $value;
    }

    /** Hər sətri bir element kimi qaytarır (xüsusiyyət siyahıları üçün). */
    public function lines(string $field, ?string $locale = null): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->t($field, $locale)))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
