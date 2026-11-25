@php
    $locale = app()->getLocale();
    $alternates = \App\Support\LocaleUrls::all();
    $codes = config('agency.locale_codes');
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' | '.__('site.brand') : __('site.brand').' — '.__('site.tagline');
    $description = trim($__env->yieldContent('description')) ?: __('seo.default_description');
    $ogImage = trim($__env->yieldContent('og_image')) ?: asset('images/og-default.png');
    $canonical = $alternates[$locale] ?? url()->current();
@endphp
<!DOCTYPE html>
<html lang="{{ $codes[$locale]['hreflang'] }}" data-error-generic="{{ __('site.error_generic') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- hreflang: hər dil üçün alternativ ünvan --}}
    @foreach ($alternates as $altLocale => $altUrl)
        <link rel="alternate" hreflang="{{ $codes[$altLocale]['hreflang'] }}" href="{{ $altUrl }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $alternates[config('agency.default_locale')] ?? url('/') }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ __('site.brand') }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="{{ $codes[$locale]['og'] }}">
    @foreach ($codes as $altLocale => $code)
        @if ($altLocale !== $locale)
            <meta property="og:locale:alternate" content="{{ $code['og'] }}">
        @endif
    @endforeach
    <meta name="twitter:card" content="summary_large_image">

    <meta name="theme-color" content="#0b1f3a">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    {{-- Qaranlıq rejim: CSS yüklənməzdən əvvəl tətbiq olunur ki, ekran "yanıb-sönməsin" --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('partials.schema-organization')
    @stack('head')
</head>
<body class="flex min-h-screen flex-col bg-white font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2 focus:text-brand-950 focus:shadow">{{ __('site.skip_to_content') }}</a>

    @include('partials.header')

    <main id="main" class="flex-1">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.whatsapp')

    @stack('scripts')
</body>
</html>
