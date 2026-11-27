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
    $linkClass = fn (bool $active) => 'rounded-full px-3.5 py-2 text-sm font-medium transition '.($active
        ? 'bg-slate-900/[.06] text-slate-900 dark:bg-white/10 dark:text-white'
        : 'text-slate-600 hover:bg-slate-900/[.04] hover:text-slate-900 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white');
@endphp
<header x-data="siteHeader" @keydown.escape.window="open = false" class="fixed inset-x-0 top-0 z-50 px-3 pt-3 sm:px-4">
    <div class="mx-auto max-w-7xl rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-xl transition duration-300 dark:border-white/10 dark:bg-slate-900/90"
         :class="scrolled || open ? 'shadow-lg shadow-slate-900/10' : ''">
        <div class="flex h-14 items-center justify-between gap-3 pl-4 pr-2 sm:h-16 sm:pl-5">
            {{-- Loqo yeri: hazır olduqda <img src="..." alt="Hamid Agency"> ilə əvəz edin --}}
            <a href="{{ lroute('home') }}" class="flex shrink-0 items-center gap-2.5 text-[17px] font-extrabold tracking-tight text-slate-900 dark:text-white">
                <span class="grid h-8 w-8 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-brand-900 text-sm text-accent-300 shadow-md shadow-brand-700/30">H</span>
                <span>Hamid<span class="text-accent-500">Agency</span></span>
            </a>

            <nav class="hidden items-center gap-0.5 lg:flex" aria-label="{{ __('site.nav.main') }}">
                <div x-data="{ sub: false }" class="relative" @mouseenter="sub = true" @mouseleave="sub = false">
                    <button type="button" @click="sub = !sub" :aria-expanded="sub" class="flex items-center gap-1 {{ $linkClass($isActive('services')) }}">
                        {{ __('site.nav.services') }}
                        <x-icon name="chevron-down" class="h-4 w-4 transition" x-bind:class="sub && 'rotate-180'" />
                    </button>
                    <div x-cloak x-show="sub" x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:leave="transition duration-150" x-transition:leave-end="opacity-0"
                         class="absolute left-1/2 top-full w-[560px] -translate-x-1/2 pt-3">
                        <div class="grid grid-cols-2 gap-1 rounded-3xl border border-slate-200/80 bg-white/95 p-2 shadow-2xl shadow-slate-900/10 backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/95">
                            @foreach ($navServices as $service)
                                <a href="{{ $service->url() }}" class="group flex items-start gap-3 rounded-2xl p-3 transition hover:bg-slate-50 dark:hover:bg-white/5">
                                    <span class="icon-tile-soft h-10 w-10 transition group-hover:scale-105"><x-icon :name="$service->icon" /></span>
                                    <span>
                                        <span class="block text-sm font-semibold text-slate-900 dark:text-white">{{ $service->t('title') }}</span>
                                        <span class="mt-0.5 line-clamp-2 block text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $service->t('excerpt') }}</span>
                                    </span>
                                </a>
                            @endforeach
                            <a href="{{ lroute('services.index') }}" class="col-span-2 mt-1 flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100 dark:bg-white/5 dark:text-white dark:hover:bg-white/10">
                                {{ __('site.nav.all_services') }} <x-icon name="arrow-right" class="h-4 w-4" />
                            </a>
                        </div>
                    </div>
                </div>
                @foreach ($nav as $item)
                    <a href="{{ lroute($item['route']) }}" @if ($isActive($item['route'])) aria-current="page" @endif class="{{ $linkClass($isActive($item['route'])) }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-1">
                @include('partials.language-switcher')
                @include('partials.theme-toggle')
                @include('partials.account-menu')
                <a href="{{ lroute('quote') }}" class="btn-accent ml-1 hidden px-4 xl:inline-flex">{{ __('site.nav.quote') }}</a>
                <button type="button" @click="open = !open" class="rounded-full p-2 text-slate-700 hover:bg-slate-900/5 lg:hidden dark:text-slate-200 dark:hover:bg-white/10"
                        :aria-expanded="open" aria-controls="mobile-menu">
                    <span class="sr-only" x-text="open ? @js(__('site.nav.close_menu')) : @js(__('site.nav.open_menu'))">{{ __('site.nav.open_menu') }}</span>
                    <x-icon name="menu" class="h-6 w-6" x-show="!open" />
                    <x-icon name="x" class="h-6 w-6" x-cloak x-show="open" />
                </button>
            </div>
        </div>

        {{-- Mobil menyu --}}
        <div id="mobile-menu" x-cloak x-show="open" x-transition class="border-t border-slate-200/80 lg:hidden dark:border-white/10">
            <nav class="max-h-[75vh] space-y-1 overflow-y-auto p-3" aria-label="{{ __('site.nav.main') }}">
                <p class="px-3 pb-1 pt-2 text-xs font-semibold uppercase tracking-wider text-slate-400">{{ __('site.nav.services') }}</p>
                @foreach ($navServices as $service)
                    <a href="{{ $service->url() }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/5">
                        <span class="icon-tile-soft h-8 w-8 rounded-xl"><x-icon :name="$service->icon" class="h-4 w-4" /></span> {{ $service->t('title') }}
                    </a>
                @endforeach
                <div class="my-2 border-t border-slate-200 dark:border-white/10"></div>
                @foreach ($nav as $item)
                    <a href="{{ lroute($item['route']) }}" class="block rounded-xl px-3 py-2.5 font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/5">{{ $item['label'] }}</a>
                @endforeach
                <div class="grid grid-cols-2 gap-2 pt-3">
                    @auth
                        <a href="{{ lroute('account') }}" class="btn-outline">{{ __('account.nav.account') }}</a>
                    @else
                        <a href="{{ lroute('login') }}" class="btn-outline">{{ __('account.nav.login') }}</a>
                    @endauth
                    <a href="{{ lroute('quote') }}" class="btn-accent">{{ __('site.nav.quote') }}</a>
                </div>
            </nav>
        </div>
    </div>
</header>
