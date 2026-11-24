@extends('layouts.app')

@section('title', __('seo.about.title'))
@section('description', __('seo.about.description'))

@section('content')
    <x-page-header :eyebrow="__('about.eyebrow')" :title="__('about.title')" :lead="__('about.lead')" />

    <section class="section">
        <div class="container grid gap-12 lg:grid-cols-2 lg:items-center">
            <div>
                <h2 class="section-title">{{ __('about.story.title') }}</h2>
                <div class="mt-6 space-y-4 text-lg leading-relaxed text-slate-600 dark:text-slate-400">
                    @foreach (__('about.story.paragraphs') as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach (__('about.values.items') as $item)
                    <div class="card p-6">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300"><x-icon :name="$item['icon']" /></span>
                        <h3 class="mt-4 font-semibold text-slate-900 dark:text-white">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if ($services->isNotEmpty())
        <section class="section bg-slate-50 dark:bg-slate-900/50">
            <div class="container">
                <x-section-heading :title="__('about.services_title')" />
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($services as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta />
@endsection
