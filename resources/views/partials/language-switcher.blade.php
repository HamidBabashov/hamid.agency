@php
    $alternates = \App\Support\LocaleUrls::all();
    $locale = app()->getLocale();
@endphp
<div x-data="{ open: false }" class="relative" @click.outside="open = false">
    <button type="button" @click="open = !open" :aria-expanded="open" aria-haspopup="true"
            class="flex items-center gap-1 rounded-full px-2.5 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-900/5 dark:text-slate-200 dark:hover:bg-white/10">
        <x-icon name="globe" class="h-4 w-4" />
        <span>{{ __('site.language.short.'.$locale) }}</span>
        <span class="sr-only">{{ __('site.language.switch') }}</span>
    </button>
    <div x-cloak x-show="open" x-transition.opacity class="absolute right-0 mt-3 w-44 rounded-2xl border border-slate-200/80 bg-white p-1.5 shadow-2xl shadow-slate-900/10 dark:border-white/10 dark:bg-slate-900" role="menu">
        @foreach ($alternates as $altLocale => $url)
            <a href="{{ $url }}" hreflang="{{ $altLocale }}" lang="{{ $altLocale }}" role="menuitem"
               class="flex items-center justify-between rounded-xl px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-white/5 {{ $altLocale === $locale ? 'font-semibold text-brand-700 dark:text-white' : 'text-slate-600 dark:text-slate-300' }}">
                {{ __('site.language.names.'.$altLocale) }}
                @if ($altLocale === $locale)
                    <x-icon name="check" class="h-4 w-4" />
                @endif
            </a>
        @endforeach
    </div>
</div>
