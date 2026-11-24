<?php

namespace App\Http\Controllers\Admin\Concerns;

trait ValidatesTranslations
{
    /** ['title' => [...]] => ['title_az' => [...], 'title_en' => [...]] */
    protected function translatable(array $fields): array
    {
        $rules = [];
        foreach ($fields as $field => $fieldRules) {
            foreach (config('agency.locales') as $locale) {
                $rules["{$field}_{$locale}"] = $fieldRules;
            }
        }

        return $rules;
    }

    /** Validasiya mesajlarında "title_az" əvəzinə "Başlıq (AZ)" göstərilsin */
    protected function translatableAttributes(array $fields): array
    {
        $attributes = [];
        foreach ($fields as $field) {
            foreach (config('agency.locales') as $locale) {
                $attributes["{$field}_{$locale}"] = __("admin.fields.{$field}").' ('.strtoupper($locale).')';
            }
        }

        return $attributes;
    }
}
