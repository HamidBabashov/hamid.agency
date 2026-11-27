@extends('layouts.app')

@section('title', __('seo.home.title'))
@section('description', __('seo.home.description'))

@section('content')
    {{-- HERO: başlıq + domen axtarışı (birinci ekranda əsas iki hərəkət) --}}
    <section class="relative isolate overflow-hidden bg-ink text-white">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true"
             style="background: radial-gradient(60% 50% at 15% 0%, rgba(52,102,246,.35), transparent 70%), radial-gradient(40% 40% at 90% 100%, rgba(56,189,248,.2), transparent 70%);"></div>

        <div class="container grid items-center gap-12 pb-20 pt-32 sm:pt-40 lg:grid-cols-2 lg:gap-16 lg:pb-28">
            <div>
                <p class="text-sm font-semibold tracking-wide text-accent-300">{{ __('home.hero.eyebrow') }}</p>
                <h1 class="mt-4 text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-6xl">
                    {{ __('home.hero.title') }}
                    <span class="text-gradient block">{{ __('home.hero.title_highlight') }}</span>
                </h1>
                <p class="mt-6 max-w-lg text-lg leading-relaxed text-slate-300">{{ __('home.hero.lead') }}</p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ lroute('quote') }}" class="btn-accent px-7 py-3.5 text-base">{{ __('home.hero.cta_primary') }} <x-icon name="arrow-right" class="h-5 w-5" /></a>
                    <a href="{{ lroute('services.index') }}" class="btn-glass px-7 py-3.5 text-base">{{ __('home.hero.cta_secondary') }}</a>
                </div>
            </div>

            <div class="glass rounded-3xl p-6 sm:p-8" id="domain-search">
                <h2 class="flex items-center gap-2 text-lg font-semibold"><x-icon name="globe" class="h-5 w-5 text-accent-300" />{{ __('home.domain.title') }}</h2>
                <div class="mt-5"><x-domain-search dark /></div>
                @if ($featuredExtensions->isNotEmpty())
                    <ul class="mt-5 flex flex-wrap gap-2 text-sm">
                        @foreach ($featuredExtensions as $ext)
                            <li class="rounded-full border border-white/10 bg-white/5 px-3 py-1">
                                <strong class="text-white">{{ $ext->label() }}</strong>
                                <span class="text-slate-400">{{ money($ext->register_price) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>

    {{-- XİDMƏTLƏR --}}
    @if ($services->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-heading :title="__('home.services.title')" :lead="__('home.services.lead')" />
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($services as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- PORTFOLIO (yalnız admin paneldən layihə əlavə edildikdə) --}}
    @if ($projects->isNotEmpty())
        <section class="section bg-slate-50 dark:bg-white/[.02]">
            <div class="container">
                <div class="flex items-end justify-between gap-6">
                    <x-section-heading :title="__('home.portfolio.title')" :center="false" />
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

    {{-- RƏYLƏR (yalnız real rəy əlavə edildikdə) --}}
    @if ($testimonials->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-heading :title="__('home.testimonials.title')" />
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
