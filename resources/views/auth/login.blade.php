@extends('layouts.app')

@section('title', __('account.login.title'))
@section('robots', 'noindex, follow')

@section('content')
    <x-auth-card :title="__('account.login.title')" :lead="__('account.login.lead')">
        <form method="POST" action="{{ lroute('login.store') }}" class="space-y-5">
            @csrf
            @if ($redirect)<input type="hidden" name="redirect" value="{{ $redirect }}">@endif
            <x-field name="email" type="email" :label="__('site.form.email')" required autofocus autocomplete="username" :placeholder="__('site.form.placeholders.email')" />
            <div>
                <x-field name="password" type="password" :label="__('account.fields.password')" required autocomplete="current-password" />
                <a href="{{ lroute('password.request') }}" class="mt-2 inline-block text-sm font-medium text-brand-700 hover:underline dark:text-brand-300">{{ __('account.login.forgot') }}</a>
            </div>
            <label class="flex items-center gap-2.5 text-sm text-slate-600 dark:text-slate-400">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500"> {{ __('account.login.remember') }}
            </label>
            <button type="submit" class="btn-primary w-full py-3">{{ __('account.login.submit') }}</button>
        </form>
        <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
            {{ __('account.login.no_account') }}
            <a href="{{ lroute('register', array_filter(['redirect' => $redirect])) }}" class="font-semibold text-brand-700 hover:underline dark:text-brand-300">{{ __('account.login.create_account') }}</a>
        </p>
    </x-auth-card>
@endsection
