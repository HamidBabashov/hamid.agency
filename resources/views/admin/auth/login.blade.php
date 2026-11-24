<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ __('admin.auth.title') }} | {{ __('site.brand') }}</title>
    <script>try{var t=localStorage.getItem('theme');if(t==='dark'||(!t&&matchMedia('(prefers-color-scheme: dark)').matches))document.documentElement.classList.add('dark')}catch(e){}</script>
    @vite(['resources/css/app.css'])
</head>
<body class="grid min-h-screen place-items-center bg-brand-950 px-4 font-sans antialiased">
    <main class="w-full max-w-sm">
        <p class="mb-8 text-center text-2xl font-extrabold text-white">Hamid<span class="text-accent-400">Agency</span></p>
        <form method="POST" action="{{ route('admin.login.attempt') }}" class="card space-y-5 p-8">
            @csrf
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('admin.auth.title') }}</h1>
            <x-field name="email" type="email" :label="__('admin.auth.email')" required autofocus autocomplete="username" />
            <x-field name="password" type="password" :label="__('admin.auth.password')" required autocomplete="current-password" />
            <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"> {{ __('admin.auth.remember') }}
            </label>
            <button type="submit" class="btn-primary w-full py-3">{{ __('admin.auth.submit') }}</button>
        </form>
    </main>
</body>
</html>
