@extends('layouts.app')

@section('title', __('seo.home.title'))
@section('description', __('seo.home.description'))

@section('content')
    {{-- ═══════════ HERO ═══════════ --}}
    <section class="relative isolate overflow-hidden bg-ink text-white">
        <x-aurora />
        <div class="container relative pb-24 pt-36 sm:pt-44 lg:pb-32">
            <div class="mx-auto max-w-4xl text-center" data-reveal>
                <a href="{{ lroute('services.index') }}" class="group inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 py-1 pl-1 pr-3 text-sm text-slate-300 backdrop-blur transition hover:border-white/30">
                    <span class="rounded-full bg-gradient-to-r from-accent-300 to-accent-500 px-2.5 py-0.5 text-xs font-bold text-brand-950">{{ __('home.hero.eyebrow') }}</span>
                    {{ __('site.tagline') }}
                    <x-icon name="arrow-right" class="h-3.5 w-3.5 transition group-hover:translate-x-0.5" />
                </a>
                <h1 class="mt-8 text-[2.6rem] font-extrabold leading-[1.05] tracking-tight sm:text-6xl lg:text-7xl">
                    {{ __('home.hero.title') }}
                    <span class="text-gradient block sm:inline">{{ __('home.hero.title_highlight') }}</span>
                </h1>
                <p class="mx-auto mt-7 max-w-2xl text-lg leading-relaxed text-slate-300 sm:text-xl">{{ __('home.hero.lead') }}</p>
                <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ lroute('quote') }}" class="btn-accent px-7 py-3.5 text-base">{{ __('home.hero.cta_primary') }} <x-icon name="arrow-right" class="h-5 w-5" /></a>
                    <a href="{{ lroute('services.index') }}" class="btn-glass px-7 py-3.5 text-base">{{ __('home.hero.cta_secondary') }}</a>
                </div>
                <ul class="mt-10 flex flex-wrap justify-center gap-x-7 gap-y-3 text-sm text-slate-400">
                    @foreach (__('home.hero.points') as $point)
                        <li class="flex items-center gap-2"><x-icon name="check-circle" class="h-4 w-4 text-accent-400" />{{ $point }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Dekorativ məhsul maketi (şəkilsiz, sürətli yüklənir) --}}
            <div class="relative mx-auto mt-20 max-w-5xl" aria-hidden="true" data-reveal style="--reveal-delay: 150ms">
                <div class="absolute -inset-x-10 -inset-y-6 rounded-[3rem] bg-gradient-to-r from-brand-600/30 via-sky-500/20 to-accent-500/20 blur-3xl"></div>
                <div class="glass relative overflow-hidden rounded-[1.75rem] p-2 shadow-2xl shadow-black/40">
                    <div class="overflow-hidden rounded-[1.3rem] border border-white/10 bg-slate-950/80">
                        <div class="flex items-center gap-2 border-b border-white/10 px-4 py-3">
                            <span class="h-3 w-3 rounded-full bg-white/20"></span><span class="h-3 w-3 rounded-full bg-white/20"></span><span class="h-3 w-3 rounded-full bg-white/20"></span>
                            <span class="mx-auto flex items-center gap-1.5 rounded-full bg-white/5 px-4 py-1 text-xs text-slate-400"><x-icon name="lock" class="h-3 w-3 text-green-400" />hamid.agency</span>
                        </div>
                        <div class="grid gap-5 p-6 sm:grid-cols-5 sm:p-8">
                            <div class="space-y-4 sm:col-span-3">
                                <div class="h-3 w-24 rounded-full bg-accent-400/70"></div>
                                <div class="h-7 w-4/5 rounded-lg bg-white/20"></div>
                                <div class="h-7 w-3/5 rounded-lg bg-white/10"></div>
                                <div class="space-y-2 pt-2"><div class="h-2.5 w-full rounded-full bg-white/10"></div><div class="h-2.5 w-11/12 rounded-full bg-white/10"></div><div class="h-2.5 w-2/3 rounded-full bg-white/10"></div></div>
                                <div class="flex gap-3 pt-3"><div class="h-10 w-32 rounded-full bg-gradient-to-r from-accent-300 to-accent-500"></div><div class="h-10 w-28 rounded-full border border-white/15"></div></div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 sm:col-span-2">
                                <div class="aspect-square rounded-2xl bg-gradient-to-br from-brand-500/60 to-brand-800/60"></div>
                                <div class="aspect-square rounded-2xl bg-gradient-to-br from-sky-400/40 to-cyan-600/30"></div>
                                <div class="col-span-2 rounded-2xl border border-white/10 bg-white/5 p-4">
                                    <p class="text-sm font-semibold text-white">{{ __('home.hero.card_title') }}</p>
                                    <ul class="mt-3 space-y-2 text-xs">
                                        @foreach (__('home.hero.card_items') as $item)
                                            <li class="flex items-center justify-between text-slate-300"><span>{{ $item['label'] }}</span><span class="rounded-full bg-green-400/15 px-2 py-0.5 font-medium text-green-300">{{ $item['status'] }}</span></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Üzən çiplər --}}
                <div class="glass absolute -left-4 top-24 hidden items-center gap-3 rounded-2xl px-4 py-3 shadow-xl motion-safe:animate-float md:flex">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-green-400/20 text-green-300"><x-icon name="globe" class="h-5 w-5" /></span>
                    <span class="text-left"><span class="block text-sm font-semibold text-white">{{ __('home.chips.domain') }}</span><span class="text-xs text-green-300">{{ __('domains.status.available') }}</span></span>
                </div>
                <div class="glass absolute -right-4 bottom-16 hidden items-center gap-3 rounded-2xl px-4 py-3 shadow-xl motion-safe:animate-float-delayed md:flex">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-accent-400/20 text-accent-300"><x-icon name="shield" class="h-5 w-5" /></span>
                    <span class="text-sm font-semibold text-white">{{ __('home.chips.ssl') }}</span>
                </div>
                <div class="glass absolute -top-5 right-16 hidden items-center gap-2 rounded-full px-4 py-2 shadow-xl motion-safe:animate-float lg:flex">
                    <x-icon name="smartphone" class="h-4 w-4 text-sky-300" /><span class="text-xs font-semibold text-white">{{ __('home.chips.mobile') }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ TEXNOLOGİYALAR ═══════════ --}}
    <section class="border-b border-slate-200/70 py-10 dark:border-white/10" aria-label="{{ __('home.stack.title') }}">
        <p class="text-center text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('home.stack.title') }}</p>
        <div class="relative mt-6 overflow-hidden [mask-image:linear-gradient(90deg,transparent,black_12%,black_88%,transparent)]">
            <ul class="flex w-max gap-12 motion-safe:animate-marquee">
                @foreach ([...__('home.stack.items'), ...__('home.stack.items')] as $i => $tech)
                    <li class="whitespace-nowrap text-xl font-bold text-slate-300 dark:text-slate-600" @if ($i >= count(__('home.stack.items'))) aria-hidden="true" @endif>{{ $tech }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- ═══════════ XİDMƏTLƏR (bento) ═══════════ --}}
    @if ($services->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-heading :eyebrow="__('home.services.eyebrow')" :title="__('home.services.title')" :lead="__('home.services.lead')" />
                <div class="mt-16 grid gap-5 lg:grid-cols-6">
                    @foreach ($services as $i => $service)
                        @php $large = in_array($i % 4, [0, 3], true); @endphp
                        <x-service-card :service="$service" :large="$large" @class(['lg:col-span-4' => $large, 'lg:col-span-2' => ! $large]) style="--reveal-delay: {{ ($i % 2) * 100 }}ms" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════ DOMEN AXTARIŞI ═══════════ --}}
    <section class="relative overflow-hidden py-20 sm:py-24" id="domain-search">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-50 to-white dark:from-white/[.02] dark:to-transparent" aria-hidden="true"></div>
        <div class="container relative">
            <div class="mx-auto max-w-3xl rounded-[2rem] bg-gradient-to-br from-brand-500/40 via-slate-200 to-accent-400/40 p-px shadow-soft dark:via-white/10" data-reveal>
                <div class="rounded-[calc(2rem-1px)] bg-white p-6 text-center sm:p-10 dark:bg-ink">
                    <x-section-heading :eyebrow="__('home.domain.eyebrow')" :title="__('home.domain.title')" :lead="__('home.domain.lead')" />
                    <div class="mt-8 text-left"><x-domain-search /></div>
                    @if ($featuredExtensions->isNotEmpty())
                        <ul class="mt-6 flex flex-wrap justify-center gap-2">
                            @foreach ($featuredExtensions as $ext)
                                <li class="rounded-full border border-slate-200 bg-slate-50 px-3.5 py-1.5 text-sm dark:border-white/10 dark:bg-white/5">
                                    <strong class="text-slate-900 dark:text-white">{{ $ext->label() }}</strong>
                                    <span class="text-slate-500 dark:text-slate-400">{{ money($ext->register_price) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ lroute('domains') }}" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-brand-700 hover:underline dark:text-brand-300">{{ __('services.domain_prices') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ NİYƏ BİZ ═══════════ --}}
    <section class="section">
        <div class="container">
            <x-section-heading :eyebrow="__('home.why.eyebrow')" :title="__('home.why.title')" />
            <div class="mt-16 grid overflow-hidden rounded-3xl border border-slate-200/80 bg-slate-200/80 sm:grid-cols-2 lg:grid-cols-4 dark:border-white/10 dark:bg-white/10" style="gap: 1px">
                @foreach (__('home.why.items') as $i => $item)
                    <div class="group bg-white p-8 transition hover:bg-slate-50 dark:bg-ink dark:hover:bg-white/[.03]" data-reveal style="--reveal-delay: {{ $i * 80 }}ms">
                        <span class="icon-tile-soft transition group-hover:scale-110"><x-icon :name="$item['icon']" class="h-5 w-5" /></span>
                        <h3 class="mt-6 text-lg font-semibold text-slate-900 dark:text-white">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════ İŞ PROSESİ ═══════════ --}}
    <section class="section relative overflow-hidden bg-slate-50 dark:bg-white/[.02]">
        <div class="container">
            <x-section-heading :eyebrow="__('home.process.eyebrow')" :title="__('home.process.title')" :lead="__('home.process.lead')" />
            <div class="relative mt-16">
            <div class="absolute left-0 right-0 top-7 hidden h-px bg-gradient-to-r from-transparent via-brand-300 to-transparent lg:block dark:via-brand-500/40" aria-hidden="true"></div>
            <ol class="relative grid gap-10 sm:grid-cols-2 lg:grid-cols-4 lg:gap-6">
                @foreach (__('home.process.steps') as $i => $step)
                    <li class="relative text-center" data-reveal style="--reveal-delay: {{ $i * 100 }}ms">
                        <span class="relative mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-brand-600 to-brand-900 text-lg font-bold text-white shadow-lg shadow-brand-700/30 ring-8 ring-slate-50 dark:ring-ink">{{ $i + 1 }}</span>
                        <h3 class="mt-6 text-lg font-semibold text-slate-900 dark:text-white">{{ $step['title'] }}</h3>
                        <p class="mx-auto mt-2 max-w-xs text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
            </div>
        </div>
    </section>

    {{-- ═══════════ PORTFOLIO ═══════════ --}}
    @if ($projects->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end">
                    <x-section-heading :eyebrow="__('home.portfolio.eyebrow')" :title="__('home.portfolio.title')" :lead="__('home.portfolio.lead')" :center="false" />
                    <a href="{{ lroute('portfolio') }}" class="btn-outline shrink-0">{{ __('site.actions.view_all') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>
                </div>
                <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <x-project-card :project="$project" data-reveal />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════ RƏYLƏR ═══════════ --}}
    @if ($testimonials->isNotEmpty())
        <section class="section {{ $projects->isNotEmpty() ? 'bg-slate-50 dark:bg-white/[.02]' : '' }}">
            <div class="container">
                <x-section-heading :eyebrow="__('home.testimonials.eyebrow')" :title="__('home.testimonials.title')" />
                <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <x-testimonial-card :testimonial="$testimonial" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta />
@endsection
