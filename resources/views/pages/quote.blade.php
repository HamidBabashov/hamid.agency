@extends('layouts.app')

@section('title', __('seo.quote.title'))
@section('description', __('seo.quote.description'))

@section('content')
    @php
        $opt = fn (string $group) => __("quote.options.{$group}");
        $service = old('service_type', $prefill['service_type']);
        $icons = ['web_design' => 'monitor', 'mobile_app' => 'smartphone', 'hosting' => 'server', 'domain' => 'globe'];
        $planOptions = $plans->mapWithKeys(fn ($p) => [$p->id => $p->t('name').' — '.money($p->price_monthly).__('site.per_month')])->all();
        $yearOptions = collect(range(1, 5))->mapWithKeys(fn ($n) => [$n => __('quote.years', ['count' => $n])])->all();
    @endphp

    <x-page-header :eyebrow="__('quote.eyebrow')" :title="__('quote.title')" :lead="__('quote.lead')" />

    <section class="section">
        <div class="container max-w-4xl">
            @if (session('success'))
                <x-alert class="mb-8 text-base">{{ session('success') }}</x-alert>
            @endif
            <x-form-errors />

            <form method="POST" action="{{ lroute('quote.store') }}" class="relative space-y-8" x-data="{ service: @js($service), sending: false }" @submit="sending = true">
                @csrf
                <x-spam-protection />

                {{-- 1. Xidmət --}}
                <fieldset class="card p-6 sm:p-8">
                    <legend class="sr-only">{{ __('quote.steps.service') }}</legend>
                    <h2 class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-white">
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-950 text-sm text-white dark:bg-brand-600">1</span>{{ __('quote.steps.service') }}
                    </h2>
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        @foreach (\App\Models\Service::KEYS as $key)
                            <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border-2 p-4 transition"
                                   :class="service === @js($key) ? 'border-brand-600 bg-brand-50/60 dark:border-brand-500 dark:bg-brand-900/20' : 'border-slate-200 hover:border-slate-300 dark:border-slate-700'">
                                <input type="radio" name="service_type" value="{{ $key }}" x-model="service" class="sr-only" @checked($service === $key) required>
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-white text-brand-700 shadow-sm dark:bg-slate-800 dark:text-brand-300"><x-icon :name="$icons[$key]" /></span>
                                <span>
                                    <span class="block font-semibold text-slate-900 dark:text-white">{{ __('site.service_types.'.$key) }}</span>
                                    <span class="mt-0.5 block text-sm text-slate-500 dark:text-slate-400">{{ __('quote.service_hints.'.$key) }}</span>
                                </span>
                                <x-icon name="check-circle" class="absolute right-3 top-3 h-5 w-5 text-brand-600 dark:text-brand-400" x-show="service === {{ \Illuminate\Support\Js::from($key) }}" x-cloak />
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                {{-- 2. Detallar — seçilən xidmətə görə dəyişir. Gizli bölmələr "disabled" olur və göndərilmir. --}}
                <div class="card p-6 sm:p-8">
                    <h2 class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-white">
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-950 text-sm text-white dark:bg-brand-600">2</span>{{ __('quote.steps.details') }}
                    </h2>

                    <fieldset x-show="service === 'web_design'" :disabled="service !== 'web_design'" @disabled($service !== 'web_design') class="mt-6 grid gap-5 sm:grid-cols-2">
                        <x-field name="web_type" type="select" :label="__('quote.fields.web_type')" :options="$opt('web_type')" required />
                        <x-field name="pages" type="select" :label="__('quote.fields.pages')" :options="$opt('pages')" />
                        <x-field name="has_domain" type="select" :label="__('quote.fields.has_domain')" :options="$opt('yes_no')" />
                        <x-field name="budget" type="select" :label="__('quote.fields.budget')" :options="$opt('budget')" />
                        <x-field name="deadline" type="select" :label="__('quote.fields.deadline')" :options="$opt('deadline')" />
                    </fieldset>

                    <fieldset x-cloak x-show="service === 'mobile_app'" :disabled="service !== 'mobile_app'" @disabled($service !== 'mobile_app') class="mt-6 grid gap-5 sm:grid-cols-2">
                        <x-field name="app_platform" type="select" :label="__('quote.fields.app_platform')" :options="$opt('app_platform')" required />
                        <x-field name="app_type" type="select" :label="__('quote.fields.app_type')" :options="$opt('app_type')" />
                        <x-field name="has_design" type="select" :label="__('quote.fields.has_design')" :options="$opt('yes_no')" />
                        <x-field name="budget" id="f-budget-mobile" type="select" :label="__('quote.fields.budget')" :options="$opt('budget')" />
                        <x-field name="deadline" id="f-deadline-mobile" type="select" :label="__('quote.fields.deadline')" :options="$opt('deadline')" />
                    </fieldset>

                    <fieldset x-cloak x-show="service === 'hosting'" :disabled="service !== 'hosting'" @disabled($service !== 'hosting') class="mt-6 grid gap-5 sm:grid-cols-2">
                        <x-field name="hosting_plan_id" type="select" class="sm:col-span-2" :label="__('quote.fields.hosting_plan_id')" :options="$planOptions" :value="$prefill['plan']" required />
                        <x-field name="billing_cycle" type="select" :label="__('quote.fields.billing_cycle')" :options="$opt('billing_cycle')" :value="$prefill['billing']" required />
                        <x-field name="hosting_domain" :label="__('quote.fields.hosting_domain')" maxlength="100" :placeholder="__('domains.search.placeholder')" />
                    </fieldset>

                    <fieldset x-cloak x-show="service === 'domain'" :disabled="service !== 'domain'" @disabled($service !== 'domain') class="mt-6 grid gap-5 sm:grid-cols-3">
                        <x-field name="domain_name" class="sm:col-span-3" :label="__('quote.fields.domain_name')" :value="$prefill['domain']" required maxlength="100"
                                 :placeholder="__('domains.search.placeholder')" :hint="$extensions->map->label()->implode(', ')" autocapitalize="none" spellcheck="false" />
                        <x-field name="domain_action" type="select" class="sm:col-span-2" :label="__('quote.fields.domain_action')" :options="$opt('domain_action')" value="register" required />
                        <x-field name="domain_years" type="select" :label="__('quote.fields.domain_years')" :options="$yearOptions" value="1" required />
                    </fieldset>

                    <x-field name="message" type="textarea" rows="4" class="mt-5" :label="__('quote.fields.message')" maxlength="3000" :placeholder="__('quote.fields.message_placeholder')" />
                </div>

                {{-- 3. Əlaqə --}}
                <fieldset class="card p-6 sm:p-8">
                    <legend class="sr-only">{{ __('quote.steps.contact') }}</legend>
                    <h2 class="flex items-center gap-3 text-lg font-bold text-slate-900 dark:text-white">
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-950 text-sm text-white dark:bg-brand-600">3</span>{{ __('quote.steps.contact') }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('site.form.required_hint') }}</p>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <x-field name="name" :label="__('site.form.name')" required maxlength="100" autocomplete="name" :placeholder="__('site.form.placeholders.name')" />
                        <x-field name="company" :label="__('site.form.company')" maxlength="150" autocomplete="organization" :placeholder="__('site.form.placeholders.company')" />
                        <x-field name="email" type="email" :label="__('site.form.email')" required maxlength="150" autocomplete="email" :placeholder="__('site.form.placeholders.email')" />
                        <x-field name="phone" type="tel" :label="__('site.form.phone')" required maxlength="30" autocomplete="tel" :placeholder="__('site.form.placeholders.phone')" />
                    </div>
                </fieldset>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="max-w-md text-sm text-slate-500 dark:text-slate-400">
                        {{ __('quote.no_payment') }}
                        {!! __('site.form.privacy_note', ['link' => '<a href="'.e(lroute('privacy')).'" class="underline hover:text-brand-600">'.e(__('site.form.privacy_link')).'</a>']) !!}
                    </p>
                    <button type="submit" class="btn-accent px-8 py-3.5 text-base" :disabled="sending">
                        <span x-text="sending ? @js(__('site.form.sending')) : @js(__('quote.submit'))">{{ __('quote.submit') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
