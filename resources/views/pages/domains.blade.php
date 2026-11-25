@extends('layouts.app')

@section('title', __('seo.domains.title'))
@section('description', __('seo.domains.description'))

@section('content')
    <x-page-header :eyebrow="__('domains.eyebrow')" :title="__('domains.title')" :lead="__('domains.lead')">
        <div class="mt-10 max-w-3xl" id="search">
            <x-domain-search :initial="$initialQuery" dark />
        </div>
    </x-page-header>

    <section class="section">
        <div class="container max-w-4xl">
            <x-section-heading :title="__('domains.prices.title')" />
            <div class="mt-10">
                <x-domain-price-table :extensions="$extensions" />
            </div>
        </div>
    </section>

    <section class="section bg-slate-50 dark:bg-slate-900/50">
        <div class="container">
            <x-section-heading :title="__('domains.info.title')" />
            <div class="mt-12 grid gap-6 md:grid-cols-3">
                @foreach (__('domains.info.items') as $item)
                    <div class="card p-6">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-accent-100 text-accent-700 dark:bg-accent-500/15 dark:text-accent-400"><x-icon :name="$item['icon']" /></span>
                        <h3 class="mt-4 font-semibold text-slate-900 dark:text-white">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-cta service="domain" />
@endsection
