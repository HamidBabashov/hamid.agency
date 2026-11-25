@extends('layouts.app')

@section('title', __('seo.contact.title'))
@section('description', __('seo.contact.description'))

@section('content')
    @php $contact = config('agency.contact'); @endphp
    <x-page-header :eyebrow="__('contact.eyebrow')" :title="__('contact.title')" :lead="__('contact.lead')" />

    <section class="section">
        <div class="container grid gap-10 lg:grid-cols-5">
            <aside class="space-y-4 lg:col-span-2">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('contact.info.title') }}</h2>

                @if ($contact['email'])
                    <a href="mailto:{{ $contact['email'] }}" class="card flex items-center gap-4 p-5 transition hover:border-brand-300">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300"><x-icon name="mail" /></span>
                        <span><span class="block text-sm text-slate-500 dark:text-slate-400">{{ __('contact.info.email') }}</span><span class="font-semibold text-slate-900 dark:text-white">{{ $contact['email'] }}</span></span>
                    </a>
                @endif
                @if ($contact['phone'])
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $contact['phone']) }}" class="card flex items-center gap-4 p-5 transition hover:border-brand-300">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300"><x-icon name="phone" /></span>
                        <span><span class="block text-sm text-slate-500 dark:text-slate-400">{{ __('contact.info.phone') }}</span><span class="font-semibold text-slate-900 dark:text-white">{{ $contact['phone'] }}</span></span>
                    </a>
                @endif
                @if ($contact['whatsapp'])
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $contact['whatsapp']) }}?text={{ rawurlencode(__('site.whatsapp.message')) }}" target="_blank" rel="noopener"
                       class="card flex items-center gap-4 p-5 transition hover:border-green-400">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300"><x-icon name="whatsapp" /></span>
                        <span><span class="block text-sm text-slate-500 dark:text-slate-400">{{ __('contact.info.whatsapp') }}</span><span class="font-semibold text-slate-900 dark:text-white">{{ __('contact.info.whatsapp_text') }}</span></span>
                    </a>
                @endif
                @if ($contact['address'])
                    <div class="card flex items-center gap-4 p-5">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300"><x-icon name="map-pin" /></span>
                        <span><span class="block text-sm text-slate-500 dark:text-slate-400">{{ __('contact.info.address') }}</span><span class="font-semibold text-slate-900 dark:text-white">{{ $contact['address'] }}</span></span>
                    </div>
                @endif
                <div class="card flex items-center gap-4 p-5">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300"><x-icon name="clock" /></span>
                    <span><span class="block text-sm text-slate-500 dark:text-slate-400">{{ __('contact.info.hours') }}</span><span class="font-semibold text-slate-900 dark:text-white">{{ __('contact.info.hours_value') }}</span></span>
                </div>
            </aside>

            <div class="card p-6 sm:p-8 lg:col-span-3">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ __('contact.form.title') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('site.form.required_hint') }}</p>

                <div class="mt-6">
                    @if (session('success'))
                        <x-alert class="mb-6">{{ session('success') }}</x-alert>
                    @endif
                    <x-form-errors />
                </div>

                <form method="POST" action="{{ lroute('contact.store') }}" class="relative grid gap-5 sm:grid-cols-2" x-data="{ sending: false }" @submit="sending = true">
                    @csrf
                    <x-spam-protection />
                    <x-field name="name" :label="__('site.form.name')" required maxlength="100" autocomplete="name" :placeholder="__('site.form.placeholders.name')" />
                    <x-field name="email" type="email" :label="__('site.form.email')" required maxlength="150" autocomplete="email" :placeholder="__('site.form.placeholders.email')" />
                    <x-field name="phone" type="tel" :label="__('site.form.phone')" maxlength="30" autocomplete="tel" :placeholder="__('site.form.placeholders.phone')" />
                    <x-field name="service_type" type="select" :label="__('site.form.service')" :options="__('site.service_types')" :value="request('service')" />
                    <x-field name="message" type="textarea" class="sm:col-span-2" :label="__('site.form.message')" required minlength="10" maxlength="3000" :placeholder="__('site.form.placeholders.message')" />
                    <div class="sm:col-span-2">
                        <p class="mb-4 text-xs text-slate-500 dark:text-slate-400">
                            {!! __('site.form.privacy_note', ['link' => '<a href="'.e(lroute('privacy')).'" class="underline hover:text-brand-600">'.e(__('site.form.privacy_link')).'</a>']) !!}
                        </p>
                        <button type="submit" class="btn-primary w-full py-3 sm:w-auto" :disabled="sending">
                            <span x-text="sending ? @js(__('site.form.sending')) : @js(__('contact.form.submit'))">{{ __('contact.form.submit') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
