@extends('layouts.app')

@section('title', __('seo.hosting.title'))
@section('description', __('seo.hosting.description'))

@push('head')
    @if ($plans->isNotEmpty())
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => __('seo.hosting.title'),
            'serviceType' => 'Web hosting',
            'provider' => ['@type' => 'Organization', 'name' => __('site.brand'), 'url' => url('/')],
            'offers' => $plans->map(fn ($p) => ['@type' => 'Offer', 'name' => $p->t('name'), 'price' => (string) $p->price_monthly, 'priceCurrency' => config('agency.currency')])->values()->all(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    @endif
@endpush

@section('content')
    <x-page-header :eyebrow="__('hosting.eyebrow')" :title="__('hosting.title')" :lead="__('hosting.lead')" />

    <section class="section">
        <div class="container">
            <x-pricing-table :plans="$plans" />
        </div>
    </section>

    <section class="section bg-slate-50 dark:bg-slate-900/50">
        <div class="container">
            <x-section-heading :title="__('hosting.included.title')" />
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (__('hosting.included.items') as $item)
                    <div class="card p-6">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300"><x-icon :name="$item['icon']" /></span>
                        <h3 class="mt-4 font-semibold text-slate-900 dark:text-white">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.faq', ['title' => __('hosting.faq.title'), 'items' => __('hosting.faq.items')])

    <x-cta service="hosting" />
@endsection
