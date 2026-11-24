@php
    // Xəta səhifələrində dil URL-in ilk seqmentindən təyin olunur
    $segment = request()->segment(1);
    if (in_array($segment, config('agency.locales'), true)) {
        app()->setLocale($segment);
    }
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ __('site.errors.'.$code.'_title') }} | {{ __('site.brand') }}</title>
    <script>try{var t=localStorage.getItem('theme');if(t==='dark'||(!t&&matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.classList.add('dark')}catch(e){}</script>
    @vite(['resources/css/app.css'])
</head>
<body class="grid min-h-screen place-items-center bg-white px-4 font-sans text-slate-800 antialiased dark:bg-slate-950 dark:text-slate-200">
    <main class="text-center">
        <p class="text-7xl font-extrabold text-brand-950 dark:text-white">{{ $code }}</p>
        <h1 class="mt-4 text-2xl font-bold">{{ __('site.errors.'.$code.'_title') }}</h1>
        <p class="mt-3 text-slate-600 dark:text-slate-400">{{ __('site.errors.'.$code.'_text') }}</p>
        <a href="{{ url('/'.app()->getLocale()) }}" class="btn-primary mt-8">{{ __('site.actions.back_home') }}</a>
    </main>
</body>
</html>
