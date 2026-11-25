@extends('layouts.app')

@section('title', __('seo.services.title'))
@section('description', __('seo.services.description'))

@section('content')
    <x-page-header :eyebrow="__('services.index.eyebrow')" :title="__('services.index.title')" :lead="__('services.index.lead')" />

    <section class="section">
        <div class="container space-y-8">
            @foreach ($services as $service)
                <article class="card grid gap-8 p-6 sm:p-8 lg:grid-cols-5 lg:items-center">
                    <div class="lg:col-span-3">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300">
                            <x-icon :name="$service->icon" class="h-6 w-6" />
                        </span>
                        <h2 class="mt-5 text-2xl font-bold text-slate-900 dark:text-white">{{ $service->t('title') }}</h2>
                        <p class="mt-3 text-slate-600 dark:text-slate-400">{{ $service->t('excerpt') }}</p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ $service->url() }}" class="btn-primary">{{ __('site.actions.learn_more') }}</a>
                            <a href="{{ lroute('quote', ['service' => $service->key]) }}" class="btn-outline">{{ __('site.actions.get_quote') }}</a>
                        </div>
                    </div>
                    <ul class="grid gap-3 text-sm sm:grid-cols-2 lg:col-span-2 lg:grid-cols-1">
                        @foreach (array_slice($service->lines('features'), 0, 4) as $feature)
                            <li class="flex gap-2.5"><x-icon name="check" class="h-5 w-5 shrink-0 text-green-600 dark:text-green-400" />{{ $feature }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </section>

    <x-cta />
@endsection
