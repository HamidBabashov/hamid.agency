@extends('layouts.app')

@section('title', __('account.forgot.title'))
@section('robots', 'noindex, follow')

@section('content')
    <x-auth-card :title="__('account.forgot.title')" :lead="__('account.forgot.lead')">
        <form method="POST" action="{{ lroute('password.email') }}" class="space-y-5">
            @csrf
            <x-field name="email" type="email" :label="__('site.form.email')" required autofocus autocomplete="email" :placeholder="__('site.form.placeholders.email')" />
            <button type="submit" class="btn-primary w-full py-3">{{ __('account.forgot.submit') }}</button>
        </form>
        <p class="mt-8 text-center text-sm"><a href="{{ lroute('login') }}" class="font-semibold text-brand-700 hover:underline dark:text-brand-300">← {{ __('account.forgot.back') }}</a></p>
    </x-auth-card>
@endsection
