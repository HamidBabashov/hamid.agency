@php
    $groups = [
        '' => [
            ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'icon' => 'home', 'label' => __('admin.nav.dashboard')],
        ],
        __('admin.nav.inbox') => [
            ['route' => 'admin.orders.index', 'match' => 'admin.orders.*', 'icon' => 'cart', 'label' => __('admin.nav.orders'), 'badge' => $adminCounts['orders'] ?? 0],
            ['route' => 'admin.messages.index', 'match' => 'admin.messages.*', 'icon' => 'inbox', 'label' => __('admin.nav.messages'), 'badge' => $adminCounts['messages'] ?? 0],
            ['route' => 'admin.customers.index', 'match' => 'admin.customers.*', 'icon' => 'users', 'label' => __('admin.nav.customers')],
        ],
        __('admin.nav.sales') => [
            ['route' => 'admin.hosting-plans.index', 'match' => 'admin.hosting-plans.*', 'icon' => 'server', 'label' => __('admin.nav.hosting_plans')],
            ['route' => 'admin.domain-extensions.index', 'match' => 'admin.domain-extensions.*', 'icon' => 'globe', 'label' => __('admin.nav.domain_extensions')],
        ],
        __('admin.nav.content') => [
            ['route' => 'admin.services.index', 'match' => 'admin.services.*', 'icon' => 'layers', 'label' => __('admin.nav.services')],
            ['route' => 'admin.projects.index', 'match' => 'admin.projects.*', 'icon' => 'image', 'label' => __('admin.nav.projects')],
            ['route' => 'admin.project-categories.index', 'match' => 'admin.project-categories.*', 'icon' => 'tag', 'label' => __('admin.nav.project_categories')],
            ['route' => 'admin.testimonials.index', 'match' => 'admin.testimonials.*', 'icon' => 'quote', 'label' => __('admin.nav.testimonials')],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ __('admin.panel') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <script>try{var t=localStorage.getItem('theme');if(t==='dark'||(!t&&matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.classList.add('dark')}catch(e){}</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200" x-data="{ nav: false }">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-brand-950 text-slate-300 transition lg:translate-x-0" :class="nav && 'translate-x-0'">
        <div class="flex h-16 items-center gap-2 px-6 text-lg font-extrabold text-white">
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-white/10 text-sm text-accent-400">H</span>
            Hamid<span class="text-accent-400">Agency</span>
        </div>
        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-4">
            @foreach ($groups as $title => $items)
                <div>
                    @if ($title)
                        <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $title }}</p>
                    @endif
                    @foreach ($items as $item)
                        @php $active = request()->routeIs($item['match']); @endphp
                        <a href="{{ route($item['route']) }}" @class(['flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition', 'bg-white/10 text-white' => $active, 'hover:bg-white/5 hover:text-white' => ! $active])>
                            <x-icon :name="$item['icon']" class="h-5 w-5" />
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if (! empty($item['badge']))
                                <span class="rounded-full bg-accent-500 px-2 py-0.5 text-xs font-bold text-brand-950">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>
        <div class="space-y-1 border-t border-white/10 p-3">
            <a href="{{ lroute('home', [], config('agency.default_locale')) }}" target="_blank" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm hover:bg-white/5 hover:text-white">
                <x-icon name="external-link" class="h-5 w-5" /> {{ __('admin.nav.view_site') }}
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm hover:bg-white/5 hover:text-white"><x-icon name="logout" class="h-5 w-5" /> {{ __('admin.nav.logout') }}</button>
            </form>
        </div>
    </aside>
    <div x-cloak x-show="nav" @click="nav = false" class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6 dark:border-slate-800 dark:bg-slate-900/90">
            <div class="flex items-center gap-3">
                <button type="button" @click="nav = true" class="rounded-lg p-2 hover:bg-slate-100 lg:hidden dark:hover:bg-slate-800" aria-label="{{ __('admin.nav.menu') }}"><x-icon name="menu" class="h-6 w-6" /></button>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-white">@yield('title')</h1>
            </div>
            <div class="flex items-center gap-2">
                @include('partials.theme-toggle')
                <span class="hidden text-sm text-slate-500 sm:inline">{{ auth()->user()->email }}</span>
            </div>
        </header>

        <main class="p-4 sm:p-6 lg:p-8">
            @if (session('success'))
                <x-alert class="mb-6">{{ session('success') }}</x-alert>
            @endif
            <x-form-errors />
            @yield('content')
        </main>
    </div>
</body>
</html>
