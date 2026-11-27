@extends('layouts.app')

@section('title', __('account.register.title'))
@section('robots', 'noindex, follow')

@section('content')
    <x-auth-card :title="__('account.register.title')" :lead="__('account.register.lead')">
        <form method="POST" action="{{ lroute('register.store') }}" class="relative grid gap-5 sm:grid-cols-2">
            @csrf
            <x-spam-protection />
            @if ($redirect)<input type="hidden" name="redirect" value="{{ $redirect }}">@endif
            <x-field name="name" class="sm:col-span-2" :label="__('site.form.name')" required maxlength="100" autocomplete="name" :placeholder="__('site.form.placeholders.name')" />
            <x-field name="email" type="email" :label="__('site.form.email')" required maxlength="150" autocomplete="email" :placeholder="__('site.form.placeholders.email')" />
            <x-field name="phone" type="tel" :label="__('site.form.phone')" maxlength="30" autocomplete="tel" :placeholder="__('site.form.placeholders.phone')" />
            <x-field name="company" class="sm:col-span-2" :label="__('site.form.company').' ('.__('site.form.optional').')'" maxlength="150" autocomplete="organization" :placeholder="__('site.form.placeholders.company')" />
            <x-field name="password" type="password" :label="__('account.fields.password')" required minlength="8" autocomplete="new-password" :hint="__('account.register.password_hint')" />
            <x-field name="password_confirmation" type="password" :label="__('account.fields.password_confirmation')" required minlength="8" autocomplete="new-password" />
            <div class="sm:col-span-2">
                <label class="flex items-start gap-2.5 text-sm text-slate-600 dark:text-slate-400">
                    <input type="checkbox" name="terms" value="1" required @checked(old('terms')) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    <span>
                        <a href="{{ lroute('terms') }}" target="_blank" class="underline hover:text-brand-600">{{ __('legal.terms.title') }}</a> ·
                        <a href="{{ lroute('privacy') }}" target="_blank" class="underline hover:text-brand-600">{{ __('legal.privacy.title') }}</a>
                        — {{ __('account.register.terms') }}
                    </span>
                </label>
                @error('terms')<p class="field-error">{{ $message }}</p>@enderror
                @error('spam')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn-accent py-3 sm:col-span-2">{{ __('account.register.submit') }}</button>
        </form>
        <p class="mt-8 text-center text-sm text-slate-600 dark:text-slate-400">
            {{ __('account.register.has_account') }}
            <a href="{{ lroute('login', array_filter(['redirect' => $redirect])) }}" class="font-semibold text-brand-700 hover:underline dark:text-brand-300">{{ __('account.register.login') }}</a>
        </p>
    </x-auth-card>
@endsection
