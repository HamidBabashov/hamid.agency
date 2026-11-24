<button type="button" x-data @click="$store.theme.toggle()" title="{{ __('site.theme.toggle') }}"
        class="rounded-lg p-2 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800">
    <span class="sr-only">{{ __('site.theme.toggle') }}</span>
    <x-icon name="moon" class="h-5 w-5 dark:hidden" />
    <x-icon name="sun" class="hidden h-5 w-5 dark:block" />
</button>
