<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class HostingPlan extends Model
{
    use HasTranslations;

    protected $guarded = ['id'];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /** İllik ödənişdə aylıq ekvivalent qiymət. */
    public function yearlyPerMonth(): float
    {
        return round((float) $this->price_yearly / 12, 2);
    }

    /** İllik ödənişdə qənaət faizi. */
    public function yearlySavingPercent(): int
    {
        $full = (float) $this->price_monthly * 12;

        return $full > 0 ? (int) max(0, round(100 - ((float) $this->price_yearly / $full * 100))) : 0;
    }
}
