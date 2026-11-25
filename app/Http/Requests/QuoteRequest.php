<?php

namespace App\Http\Requests;

use App\Models\DomainExtension;
use App\Models\HostingPlan;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Qiymət təklifi forması. Seçilən xidmətə görə fərqli sahələr tələb olunur.
 */
class QuoteRequest extends FormRequest
{
    public const OPTIONS = [
        'web_type' => ['corporate', 'ecommerce', 'landing', 'blog', 'redesign', 'other'],
        'pages' => ['1_5', '6_15', '16_plus', 'unsure'],
        'app_platform' => ['ios', 'android', 'both'],
        'app_type' => ['business', 'ecommerce', 'booking', 'delivery', 'social', 'other'],
        'yes_no' => ['yes', 'no'],
        'budget' => ['lt_1000', '1000_3000', '3000_7000', 'gt_7000', 'unsure'],
        'deadline' => ['urgent', '1_month', '1_3_months', 'flexible'],
        'billing_cycle' => ['monthly', 'yearly'],
        'domain_action' => ['register', 'transfer'],
    ];

    /** Hər xidmət üçün "details" sütununa yazılan sahələr */
    public const DETAIL_FIELDS = [
        'web_design' => ['web_type', 'pages', 'has_domain', 'budget', 'deadline'],
        'mobile_app' => ['app_platform', 'app_type', 'has_design', 'budget', 'deadline'],
        'hosting' => ['hosting_plan_id', 'billing_cycle', 'hosting_domain'],
        'domain' => ['domain_name', 'domain_action', 'domain_years'],
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $o = self::OPTIONS;
        $extensions = DomainExtension::active()->pluck('extension')->map(fn ($e) => preg_quote($e, '/'))->implode('|');

        return [
            'service_type' => ['required', Rule::in(Service::KEYS)],
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^[0-9 +()\-]{7,30}$/'],
            'company' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:3000'],

            // Veb dizayn
            'web_type' => ['exclude_unless:service_type,web_design', 'required', Rule::in($o['web_type'])],
            'pages' => ['exclude_unless:service_type,web_design', 'nullable', Rule::in($o['pages'])],
            'has_domain' => ['exclude_unless:service_type,web_design', 'nullable', Rule::in($o['yes_no'])],

            // Mobil tətbiq
            'app_platform' => ['exclude_unless:service_type,mobile_app', 'required', Rule::in($o['app_platform'])],
            'app_type' => ['exclude_unless:service_type,mobile_app', 'nullable', Rule::in($o['app_type'])],
            'has_design' => ['exclude_unless:service_type,mobile_app', 'nullable', Rule::in($o['yes_no'])],

            // Veb dizayn və mobil üçün ortaq
            'budget' => ['exclude_unless:service_type,web_design,mobile_app', 'nullable', Rule::in($o['budget'])],
            'deadline' => ['exclude_unless:service_type,web_design,mobile_app', 'nullable', Rule::in($o['deadline'])],

            // Hosting
            'hosting_plan_id' => ['exclude_unless:service_type,hosting', 'required', Rule::exists('hosting_plans', 'id')->where('is_active', true)],
            'billing_cycle' => ['exclude_unless:service_type,hosting', 'required', Rule::in($o['billing_cycle'])],
            'hosting_domain' => ['exclude_unless:service_type,hosting', 'nullable', 'string', 'max:100', 'regex:/^[a-z0-9.-]+$/i'],

            // Domen
            'domain_name' => ['exclude_unless:service_type,domain', 'required', 'string', 'max:100',
                'regex:/^(?!-)[a-z0-9-]{1,63}(?<!-)\.('.($extensions ?: 'az').')$/i'],
            'domain_action' => ['exclude_unless:service_type,domain', 'required', Rule::in($o['domain_action'])],
            'domain_years' => ['exclude_unless:service_type,domain', 'required', 'integer', 'between:1,10'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('domain_name')) {
            $this->merge(['domain_name' => mb_strtolower(trim((string) $this->input('domain_name')))]);
        }
    }

    public function attributes(): array
    {
        return __('site.attributes') + __('quote.attributes');
    }

    public function messages(): array
    {
        return ['domain_name.regex' => __('quote.errors.domain_name')];
    }

    /** Seçilmiş xidmətə aid sahələr (bazadakı "details" JSON-u) */
    public function details(): array
    {
        $type = $this->validated('service_type');
        $details = collect(self::DETAIL_FIELDS[$type] ?? [])
            ->mapWithKeys(fn ($field) => [$field => $this->validated($field)])
            ->filter(fn ($value) => filled($value))
            ->all();

        // Sifariş anındakı paket adı və qiymətini saxla (sonradan dəyişsə belə)
        if ($type === 'hosting' && isset($details['hosting_plan_id'])) {
            $plan = HostingPlan::find($details['hosting_plan_id']);
            $details['hosting_plan_name'] = $plan?->name_az;
            $details['price'] = $plan ? money($details['billing_cycle'] === 'yearly' ? $plan->price_yearly : $plan->price_monthly) : null;
        }

        if ($type === 'domain' && isset($details['domain_name'])) {
            $ext = substr($details['domain_name'], strpos($details['domain_name'], '.') + 1);
            $extension = DomainExtension::where('extension', $ext)->first();
            if ($extension) {
                $unit = $details['domain_action'] === 'transfer' ? ($extension->transfer_price ?? $extension->register_price) : $extension->register_price;
                $details['price'] = money($unit * (int) $details['domain_years']);
            }
        }

        return $details;
    }
}
