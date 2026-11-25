@props(['service'])
<a href="{{ $service->url() }}" class="card group flex flex-col p-6 transition hover:-translate-y-1 hover:border-brand-300 hover:shadow-lg dark:hover:border-brand-700">
    <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand-50 text-brand-700 transition group-hover:bg-brand-950 group-hover:text-accent-400 dark:bg-brand-900/40 dark:text-brand-300">
        <x-icon :name="$service->icon" class="h-6 w-6" />
    </span>
    <h3 class="mt-5 text-lg font-semibold text-slate-900 dark:text-white">{{ $service->t('title') }}</h3>
    <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $service->t('excerpt') }}</p>
    <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-brand-700 dark:text-brand-300">
        {{ __('site.actions.learn_more') }} <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
    </span>
</a>
