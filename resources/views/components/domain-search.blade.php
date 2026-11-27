@props(['initial' => '', 'dark' => false])
<div x-data="domainSearch(@js(lroute('domains.check')), @js($initial))" x-init="if (query) search()">
    <form @submit.prevent="search" action="{{ lroute('domains') }}" method="GET" role="search" class="flex flex-col gap-3 sm:flex-row">
        <label for="domain-q-{{ $id = uniqid() }}" class="sr-only">{{ __('domains.search.label') }}</label>
        <div class="relative flex-1">
            <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
            <input id="domain-q-{{ $id }}" name="domain" type="text" x-model="query" required maxlength="100"
                   autocomplete="off" autocapitalize="none" spellcheck="false" inputmode="url"
                   placeholder="{{ __('domains.search.placeholder') }}"
                   class="input h-14 rounded-2xl pl-12 text-base {{ $dark ? 'border-transparent' : '' }}">
        </div>
        <button type="submit" class="btn-accent h-14 px-8 text-base" :disabled="loading">
            <svg x-cloak x-show="loading" class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" class="opacity-75"/></svg>
            <span x-text="loading ? @js(__('domains.search.searching')) : @js(__('domains.search.button'))">{{ __('domains.search.button') }}</span>
        </button>
    </form>
    <p class="mt-2 text-sm {{ $dark ? 'text-slate-400' : 'text-slate-500 dark:text-slate-400' }}">{{ __('domains.search.hint') }}</p>

    <div aria-live="polite">
        <p x-cloak x-show="error" x-text="error" class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-950/50 dark:text-red-300"></p>

        <div x-cloak x-show="results.length" class="mt-6">
            <h3 class="sr-only">{{ __('domains.search.results') }}</h3>
            <ul class="divide-y divide-slate-200 overflow-hidden rounded-3xl border border-slate-200 bg-white text-left shadow-soft dark:divide-white/10 dark:border-white/10 dark:bg-slate-900">
                <template x-for="r in results" :key="r.domain">
                    <li class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full"
                                  :class="{ 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300': r.status === 'available', 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300': r.status === 'taken', 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300': r.status === 'unknown' }">
                                <span x-show="r.status === 'available'"><x-icon name="check" class="h-5 w-5" /></span>
                                <span x-show="r.status === 'taken'"><x-icon name="x" class="h-5 w-5" /></span>
                                <span x-show="r.status === 'unknown'"><x-icon name="help-circle" class="h-5 w-5" /></span>
                            </span>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white" x-text="r.domain"></p>
                                <p class="text-sm" :class="{ 'text-green-700 dark:text-green-400': r.status === 'available', 'text-red-700 dark:text-red-400': r.status === 'taken', 'text-amber-700 dark:text-amber-400': r.status === 'unknown' }">
                                    <span x-text="r.status_label"></span>
                                    <span x-show="r.status === 'unknown'" class="block text-xs text-slate-500 dark:text-slate-400">{{ __('domains.unknown_hint') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-4 sm:justify-end" x-show="r.status !== 'taken'">
                            <span class="text-sm text-slate-600 dark:text-slate-300" x-show="r.register_price">
                                <strong class="text-base text-slate-900 dark:text-white" x-text="r.register_price"></strong> / {{ __('domains.per_year') }}
                            </span>
                            <a :href="r.order_url" class="btn-primary"
                               x-text="r.status === 'available' ? @js(__('domains.order')) : @js(__('domains.request'))"></a>
                        </div>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
