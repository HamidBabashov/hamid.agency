@extends('layouts.app')

@section('title', __('seo.home.title'))
@section('description', __('seo.home.description'))

@section('content')
    {{-- HERO --}}
    <section class="relative overflow-hidden bg-brand-950 text-white">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true"
             style="background-image: radial-gradient(circle at 10% 10%, rgba(52,102,246,.45), transparent 35%), radial-gradient(circle at 90% 70%, rgba(245,158,11,.22), transparent 35%);"></div>
        <div class="pointer-events-none absolute inset-0 opacity-[.07]" aria-hidden="true"
             style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 48px 48px;"></div>

        <div class="container relative grid items-center gap-12 py-20 lg:grid-cols-2 lg:py-28">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-sm font-medium text-accent-300">
                    <span class="h-2 w-2 rounded-full bg-accent-400"></span>{{ __('home.hero.eyebrow') }}
                </p>
                <h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                    {{ __('home.hero.title') }}
                    <span class="bg-gradient-to-r from-accent-300 to-accent-500 bg-clip-text text-transparent">{{ __('home.hero.title_highlight') }}</span>
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-slate-300">{{ __('home.hero.lead') }}</p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ lroute('quote') }}" class="btn-accent px-6 py-3.5 text-base">{{ __('home.hero.cta_primary') }} <x-icon name="arrow-right" class="h-5 w-5" /></a>
                    <a href="{{ lroute('services.index') }}" class="btn border border-white/25 px-6 py-3.5 text-base text-white hover:bg-white/10">{{ __('home.hero.cta_secondary') }}</a>
                </div>
                <ul class="mt-9 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-300">
                    @foreach (__('home.hero.points') as $point)
                        <li class="flex items-center gap-2"><x-icon name="check-circle" class="h-4 w-4 text-accent-400" />{{ $point }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Dekorativ "brauzer" maketi (şəkilsiz, sürətli) --}}
            <div class="relative hidden lg:block" aria-hidden="true">
                <div class="absolute -inset-6 rounded-[2rem] bg-gradient-to-tr from-brand-600/30 to-accent-500/20 blur-2xl"></div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-slate-900/80 shadow-2xl">
                    <div class="flex items-center gap-2 border-b border-white/10 px-4 py-3">
                        <span class="h-3 w-3 rounded-full bg-red-400/80"></span><span class="h-3 w-3 rounded-full bg-yellow-400/80"></span><span class="h-3 w-3 rounded-full bg-green-400/80"></span>
                        <span class="ml-3 flex-1 rounded-md bg-white/5 px-3 py-1 text-xs text-slate-400">https://hamid.agency</span>
                    </div>
                    <div class="space-y-4 p-6">
                        <div class="h-4 w-2/3 rounded bg-white/15"></div>
                        <div class="h-3 w-full rounded bg-white/10"></div>
                        <div class="h-3 w-5/6 rounded bg-white/10"></div>
                        <div class="grid grid-cols-3 gap-3 pt-2">
                            <div class="h-20 rounded-lg bg-brand-500/30"></div>
                            <div class="h-20 rounded-lg bg-accent-500/30"></div>
                            <div class="h-20 rounded-lg bg-white/10"></div>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                            <p class="text-sm font-semibold text-white">{{ __('home.hero.card_title') }}</p>
                            <ul class="mt-3 space-y-2 text-sm">
                                @foreach (__('home.hero.card_items') as $item)
                                    <li class="flex items-center justify-between text-slate-300">
                                        <span>{{ $item['label'] }}</span>
                                        <span class="rounded-full bg-green-400/15 px-2 py-0.5 text-xs font-medium text-green-300">{{ $item['status'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- XİDMƏTLƏR --}}
    @if ($services->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-heading :eyebrow="__('home.services.eyebrow')" :title="__('home.services.title')" :lead="__('home.services.lead')" />
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($services as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- DOMEN AXTARIŞI --}}
    <section class="bg-slate-50 py-16 dark:bg-slate-900/50" id="domain-search">
        <div class="container">
            <div class="mx-auto max-w-3xl text-center">
                <x-section-heading :eyebrow="__('home.domain.eyebrow')" :title="__('home.domain.title')" :lead="__('home.domain.lead')" />
                <div class="mt-8 text-left">
                    <x-domain-search />
                </div>
                @if ($featuredExtensions->isNotEmpty())
                    <ul class="mt-6 flex flex-wrap justify-center gap-3">
                        @foreach ($featuredExtensions as $ext)
                            <li class="rounded-full border border-slate-200 bg-white px-4 py-1.5 text-sm dark:border-slate-700 dark:bg-slate-800">
                                <strong class="text-brand-950 dark:text-white">{{ $ext->label() }}</strong>
                                <span class="text-slate-500 dark:text-slate-400">{{ money($ext->register_price) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ lroute('domains') }}" class="mt-5 inline-block text-sm font-semibold text-brand-700 hover:underline dark:text-brand-300">{{ __('services.domain_prices') }} →</a>
                @endif
            </div>
        </div>
    </section>

    {{-- NİYƏ BİZ --}}
    <section class="section">
        <div class="container">
            <x-section-heading :eyebrow="__('home.why.eyebrow')" :title="__('home.why.title')" />
            <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (__('home.why.items') as $item)
                    <div class="text-center">
                        <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-accent-100 text-accent-700 dark:bg-accent-500/15 dark:text-accent-400">
                            <x-icon :name="$item['icon']" class="h-7 w-7" />
                        </span>
                        <h3 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- İŞ PROSESİ --}}
    <section class="section bg-slate-50 dark:bg-slate-900/50">
        <div class="container">
            <x-section-heading :eyebrow="__('home.process.eyebrow')" :title="__('home.process.title')" :lead="__('home.process.lead')" />
            <ol class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (__('home.process.steps') as $i => $step)
                    <li class="card relative p-6">
                        <span class="text-5xl font-extrabold text-brand-100 dark:text-brand-900/60">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- PORTFOLIO (yalnız admin paneldən layihə əlavə edildikdə) --}}
    @if ($projects->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
                    <x-section-heading :eyebrow="__('home.portfolio.eyebrow')" :title="__('home.portfolio.title')" :lead="__('home.portfolio.lead')" :center="false" />
                    <a href="{{ lroute('portfolio') }}" class="btn-outline shrink-0">{{ __('site.actions.view_all') }}</a>
                </div>
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <x-project-card :project="$project" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- RƏYLƏR (yalnız admin paneldən real rəy əlavə edildikdə) --}}
    @if ($testimonials->isNotEmpty())
        <section class="section {{ $projects->isNotEmpty() ? 'bg-slate-50 dark:bg-slate-900/50' : '' }}">
            <div class="container">
                <x-section-heading :eyebrow="__('home.testimonials.eyebrow')" :title="__('home.testimonials.title')" />
                <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <x-testimonial-card :testimonial="$testimonial" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta />
@endsection
