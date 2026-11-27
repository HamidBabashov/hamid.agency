@extends('layouts.app')

@section('title', __('account.reset.title'))
@section('robots', 'noindex, nofollow')

@section('content')
    <x-auth-card :title="__('account.reset.title')">
        <form method="POST" action="{{ lroute('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <x-field name="email" type="email" :label="__('site.form.email')" :value="$email" required autocomplete="username" />
            <x-field name="password" type="password" :label="__('account.fields.new_password')" required minlength="8" autocomplete="new-password" :hint="__('account.register.password_hint')" />
            <x-field name="password_confirmation" type="password" :label="__('account.fields.password_confirmation')" required minlength="8" autocomplete="new-password" />
            <button type="submit" class="btn-primary w-full py-3">{{ __('account.reset.submit') }}</button>
        </form>
    </x-auth-card>
@endsection
