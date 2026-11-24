@php
    $nav = [
        ['route' => 'hosting', 'label' => __('site.nav.hosting')],
        ['route' => 'domains', 'label' => __('site.nav.domains')],
        ['route' => 'portfolio', 'label' => __('site.nav.portfolio')],
        ['route' => 'about', 'label' => __('site.nav.about')],
        ['route' => 'contact', 'label' => __('site.nav.contact')],
    ];
    $current = current_route_base();
    $isActive = fn (string $route) => $current === $route || str_starts_with((string) $current, $route.'.');
@endphp
<header x-data="{ open: false }" @keydown.escape.window="open = false"
        class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur dark:border-slate-800 dark:bg-slate-950/90">
    <div class="container flex h-16 items-center justify-between gap-4">
        {{-- Loqo yeri: hazır olduqda <img src="..." alt="Hamid Agency"> ilə əvəz edin --}}
        <a href="{{ lroute('home') }}" class="flex items-center gap-2 text-lg font-extrabold tracking-tight text-brand-950 dark:text-white">
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand-950 text-sm text-accent-400 dark:bg-brand-600 dark:text-white">H</span>
            <span>Hamid<span class="text-accent-500">Agency</span></span>
        </a>

        <nav class="hidden items-center gap-1 lg:flex" aria-label="{{ __('site.nav.main') }}">
            <div x-data="{ sub: false }" class="relative" @mouseenter="sub = true" @mouseleave="sub = false">
                <button type="button" @click="sub = !sub" :aria-expanded="sub"
                        class="flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium transition hover:text-brand-600 dark:hover:text-white {{ $isActive('services') ? 'text-brand-700 dark:text-white' : 'text-slate-600 dark:text-slate-300' }}">
                    {{ __('site.nav.services') }}
                    <x-icon name="chevron-down" class="h-4 w-4" />
                </button>
                <div x-cloak x-show="sub" x-transition.opacity.duration.150ms
                     class="absolute left-0 top-full w-72 pt-2">
                    <div class="card p-2">
                        @foreach ($navServices as $service)
                            <a href="{{ $service->url() }}" class="flex items-start gap-3 rounded-xl p-3 transition hover:bg-slate-50 dark:hover:bg-slate-800">
                                <span class="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300">
                                    <x-icon :name="$service->icon" />
                                </span>
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900 dark:text-white">{{ $service->t('title') }}</span>
                                    <span class="mt-0.5 line-clamp-2 block text-xs text-slate-500 dark:text-slate-400">{{ $service->t('excerpt') }}</span>
                                </span>
                            </a>
                        @endforeach
                        <a href="{{ lroute('services.index') }}" class="mt-1 flex items-center justify-between rounded-xl px-3 py-2 text-sm font-medium text-brand-700 hover:bg-slate-50 dark:text-brand-300 dark:hover:bg-slate-800">
                            {{ __('site.nav.all_services') }} <x-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            </div>
            @foreach ($nav as $item)
                <a href="{{ lroute($item['route']) }}" @if ($isActive($item['route'])) aria-current="page" @endif
                   class="rounded-lg px-3 py-2 text-sm font-medium transition hover:text-brand-600 dark:hover:text-white {{ $isActive($item['route']) ? 'text-brand-700 dark:text-white' : 'text-slate-600 dark:text-slate-300' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-1 sm:gap-2">
            @include('partials.language-switcher')
            @include('partials.theme-toggle')
            <a href="{{ lroute('quote') }}" class="btn-accent hidden sm:inline-flex">{{ __('site.nav.quote') }}</a>
            <button type="button" @click="open = !open" class="rounded-lg p-2 text-slate-700 hover:bg-slate-100 lg:hidden dark:text-slate-200 dark:hover:bg-slate-800"
                    :aria-expanded="open" aria-controls="mobile-menu">
                <span class="sr-only" x-text="open ? @js(__('site.nav.close_menu')) : @js(__('site.nav.open_menu'))">{{ __('site.nav.open_menu') }}</span>
                <x-icon name="menu" class="h-6 w-6" x-show="!open" />
                <x-icon name="x" class="h-6 w-6" x-cloak x-show="open" />
            </button>
        </div>
    </div>

    {{-- Mobil menyu --}}
    <div id="mobile-menu" x-cloak x-show="open" x-transition.origin.top class="border-t border-slate-200 bg-white lg:hidden dark:border-slate-800 dark:bg-slate-950">
        <nav class="container space-y-1 py-4" aria-label="{{ __('site.nav.main') }}">
            <p class="px-3 pb-1 pt-2 text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('site.nav.services') }}</p>
            @foreach ($navServices as $service)
                <a href="{{ $service->url() }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
                    <x-icon :name="$service->icon" class="h-5 w-5 text-brand-600 dark:text-brand-400" /> {{ $service->t('title') }}
                </a>
            @endforeach
            <div class="my-2 border-t border-slate-200 dark:border-slate-800"></div>
            @foreach ($nav as $item)
                <a href="{{ lroute($item['route']) }}" class="block rounded-lg px-3 py-2 font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ lroute('quote') }}" class="btn-accent mt-3 w-full">{{ __('site.nav.quote') }}</a>
        </nav>
    </div>
</header>
