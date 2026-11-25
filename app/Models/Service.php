<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasTranslations;

    public const KEYS = ['web_design', 'mobile_app', 'hosting', 'domain'];

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function slug(?string $locale = null): string
    {
        return $this->getAttribute('slug_'.($locale ?? app()->getLocale()));
    }

    public function url(?string $locale = null): string
    {
        return lroute('services.show', ['slug' => $this->slug($locale)], $locale);
    }
}
