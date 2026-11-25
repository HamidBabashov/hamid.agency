@extends('layouts.app')

@section('title', $service->t('meta_title') ?: $service->t('title'))
@section('description', $service->t('meta_description') ?: $service->t('excerpt'))
@if ($service->image)
    @section('og_image', upload_url($service->image))
@endif

@push('head')
    @php
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service->t('title'),
            'description' => $service->t('excerpt'),
            'serviceType' => $service->t('title'),
            'url' => $service->url(),
            'areaServed' => ['@type' => 'Country', 'name' => 'Azerbaijan'],
            'provider' => ['@type' => 'Organization', 'name' => __('site.brand'), 'url' => url('/')],
        ];
        if ($plans->isNotEmpty()) {
            $schema['offers'] = $plans->map(fn ($p) => [
                '@type' => 'Offer', 'name' => $p->t('name'), 'price' => (string) $p->price_monthly, 'priceCurrency' => config('agency.currency'),
            ])->values()->all();
        }
        if ($extensions->isNotEmpty()) {
            $schema['offers'] = $extensions->map(fn ($e) => [
                '@type' => 'Offer', 'name' => $e->label(), 'price' => (string) $e->register_price, 'priceCurrency' => config('agency.currency'),
            ])->values()->all();
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
    <x-page-header :eyebrow="__('site.nav.services')" :title="$service->t('title')" :lead="$service->t('excerpt')">
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <a href="{{ lroute('quote', ['service' => $service->key]) }}" class="btn-accent px-6 py-3">{{ __('site.actions.get_quote') }}</a>
            <a href="{{ lroute('contact') }}" class="btn border border-white/25 px-6 py-3 text-white hover:bg-white/10">{{ __('site.actions.contact_us') }}</a>
        </div>
    </x-page-header>

    <section class="section">
        <div class="container grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                @if ($service->image)
                    <img src="{{ upload_url($service->image) }}" alt="{{ $service->t('title') }}" loading="lazy" decoding="async"
                         class="mb-8 aspect-video w-full rounded-2xl object-cover">
                @endif
                <div class="space-y-5 text-lg leading-relaxed text-slate-700 dark:text-slate-300">
                    @foreach (preg_split('/\R{2,}/', trim((string) $service->t('body'))) as $paragraph)
                        @if (trim($paragraph) !== '')
                            <p>{!! nl2br(e(trim($paragraph))) !!}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            @if ($features = $service->lines('features'))
                <aside class="card h-fit p-6 lg:sticky lg:top-24">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('services.features') }}</h2>
                    <ul class="mt-5 space-y-3">
                        @foreach ($features as $feature)
                            <li class="flex gap-2.5 text-sm"><x-icon name="check-circle" class="h-5 w-5 shrink-0 text-green-600 dark:text-green-400" />{{ $feature }}</li>
                        @endforeach
                    </ul>
                </aside>
            @endif
        </div>
    </section>

    @if ($service->key === 'hosting')
        <section class="section bg-slate-50 pt-16 dark:bg-slate-900/50">
            <div class="container">
                <x-section-heading :title="__('services.hosting_plans')" />
                <div class="mt-10"><x-pricing-table :plans="$plans" /></div>
                <p class="mt-8 text-center"><a href="{{ lroute('hosting') }}" class="font-semibold text-brand-700 hover:underline dark:text-brand-300">{{ __('services.all_plans') }} →</a></p>
            </div>
        </section>
    @endif

    @if ($service->key === 'domain')
        <section class="section bg-slate-50 dark:bg-slate-900/50">
            <div class="container max-w-3xl">
                <x-section-heading :title="__('services.domain_search')" />
                <div class="mt-8"><x-domain-search /></div>
                <div class="mt-12"><x-domain-price-table :extensions="$extensions" /></div>
            </div>
        </section>
    @endif

    @if ($otherServices->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-heading :title="__('services.other')" />
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($otherServices as $other)
                        <x-service-card :service="$other" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta :title="__('services.cta_title', ['service' => $service->t('title')])" :text="__('services.cta_text')" :service="$service->key" />
@endsection
