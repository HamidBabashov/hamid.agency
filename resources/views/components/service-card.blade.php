@props(['service', 'large' => false])
<a href="{{ $service->url() }}" {{ $attributes->class(['card card-hover group relative flex flex-col overflow-hidden p-7', 'sm:p-9' => $large]) }} data-reveal>
    <div class="pointer-events-none absolute -right-16 -top-16 h-48 w-48 rounded-full bg-gradient-to-br from-brand-400/20 to-accent-300/10 opacity-0 blur-2xl transition duration-500 group-hover:opacity-100" aria-hidden="true"></div>
    <span class="icon-tile relative transition duration-300 group-hover:scale-110 group-hover:rotate-3"><x-icon :name="$service->icon" class="h-6 w-6" /></span>
    <h3 @class(['relative mt-6 font-bold text-slate-900 dark:text-white', 'text-2xl' => $large, 'text-xl' => ! $large])>{{ $service->t('title') }}</h3>
    <p class="relative mt-3 flex-1 leading-relaxed text-slate-600 dark:text-slate-400">{{ $service->t('excerpt') }}</p>
    @if ($large)
        <ul class="relative mt-6 flex flex-wrap gap-2">
            @foreach (array_slice($service->lines('features'), 0, 4) as $feature)
                <li class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-medium text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">{{ $feature }}</li>
            @endforeach
        </ul>
    @endif
    <span class="relative mt-7 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700 dark:text-brand-300">
        {{ __('site.actions.learn_more') }} <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
    </span>
</a>
