@props(['service', 'large' => false])
<a href="{{ $service->url() }}" {{ $attributes->class(['card card-hover group relative flex gap-4 overflow-hidden p-5 sm:flex-col sm:gap-0 sm:p-6', 'sm:p-9' => $large]) }} data-reveal>
    <span class="icon-tile relative h-11 w-11 transition duration-300 group-hover:scale-105 sm:h-12 sm:w-12"><x-icon :name="$service->icon" class="h-5 w-5 sm:h-6 sm:w-6" /></span>
    <span class="relative flex min-w-0 flex-1 flex-col">
        <span @class(['font-bold text-slate-900 sm:mt-5 dark:text-white', 'text-2xl' => $large, 'text-base sm:text-lg' => ! $large])>{{ $service->t('title') }}</span>
        <span class="mt-1 flex-1 text-sm leading-relaxed text-slate-600 sm:mt-2 dark:text-slate-400">{{ $service->t('excerpt') }}</span>
        @if ($large)
            <span class="mt-6 flex flex-wrap gap-2">
                @foreach (array_slice($service->lines('features'), 0, 4) as $feature)
                    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-medium text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">{{ $feature }}</span>
                @endforeach
            </span>
        @endif
        <span class="mt-5 hidden items-center gap-1.5 text-sm font-semibold text-brand-700 sm:inline-flex dark:text-brand-300">
            {{ __('site.actions.learn_more') }} <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
        </span>
    </span>
    <x-icon name="arrow-right" class="h-5 w-5 shrink-0 self-center text-slate-400 sm:hidden" />
</a>
