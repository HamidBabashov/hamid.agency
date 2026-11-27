@extends('layouts.app')

@section('title', __('account.profile.title'))
@section('robots', 'noindex, nofollow')

@section('content')
    <x-account-shell :title="__('account.profile.title')">
        <div class="grid gap-6 lg:grid-cols-2">
            <form method="POST" action="{{ lroute('account.profile.update') }}" class="card space-y-5 p-6 sm:p-8">
                @csrf @method('PUT')
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('account.profile.details') }}</h2>
                <x-field name="name" :label="__('site.form.name')" :value="$user->name" required maxlength="100" autocomplete="name" />
                <x-field name="email" type="email" :label="__('site.form.email')" :value="$user->email" required maxlength="150" autocomplete="email" />
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-field name="phone" type="tel" :label="__('site.form.phone')" :value="$user->phone" maxlength="30" autocomplete="tel" />
                    <x-field name="company" :label="__('site.form.company')" :value="$user->company" maxlength="150" autocomplete="organization" />
                </div>
                <x-field name="locale" type="select" :label="__('account.fields.language')" :value="$user->locale" required :options="__('site.language.names')" :hint="__('account.profile.language_hint')" />
                <button type="submit" class="btn-primary">{{ __('account.profile.save') }}</button>
            </form>

            <form method="POST" action="{{ lroute('account.password.update') }}" class="card h-fit space-y-5 p-6 sm:p-8">
                @csrf @method('PUT')
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('account.profile.password') }}</h2>
                <x-field name="current_password" type="password" :label="__('account.fields.current_password')" required autocomplete="current-password" />
                <x-field name="password" type="password" :label="__('account.fields.new_password')" required minlength="8" autocomplete="new-password" :hint="__('account.register.password_hint')" />
                <x-field name="password_confirmation" type="password" :label="__('account.fields.password_confirmation')" required minlength="8" autocomplete="new-password" />
                <button type="submit" class="btn-outline">{{ __('account.profile.update_password') }}</button>
            </form>
        </div>
    </x-account-shell>
@endsection
