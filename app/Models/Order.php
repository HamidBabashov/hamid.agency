<?php

namespace App\Models;

use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasStatus;

    /** details sahəsi => seçim qrupu (lang/quote.php "options") */
    private const OPTION_GROUPS = [
        'web_type' => 'web_type',
        'pages' => 'pages',
        'has_domain' => 'yes_no',
        'app_platform' => 'app_platform',
        'app_type' => 'app_type',
        'has_design' => 'yes_no',
        'budget' => 'budget',
        'deadline' => 'deadline',
        'billing_cycle' => 'billing_cycle',
        'domain_action' => 'domain_action',
    ];

    protected $guarded = ['id'];

    protected $casts = ['details' => 'array'];

    /**
     * Admin panel və email üçün oxunaqlı detallar.
     *
     * @return array<int, array{label: string, value: string}>
     */
    public function detailRows(): array
    {
        $rows = [];

        foreach ((array) $this->details as $key => $value) {
            if ($key === 'hosting_plan_id' || blank($value)) {
                continue;
            }

            $display = match (true) {
                isset(self::OPTION_GROUPS[$key]) => __('quote.options.'.self::OPTION_GROUPS[$key].'.'.$value),
                $key === 'domain_years' => __('quote.years', ['count' => $value]),
                default => (string) $value,
            };

            $rows[] = ['label' => __('quote.fields.'.$key), 'value' => $display];
        }

        return $rows;
    }
}
