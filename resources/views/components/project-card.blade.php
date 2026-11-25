@props(['project'])
<article {{ $attributes->class('card group overflow-hidden') }}>
    <div class="aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800">
        @if ($project->image)
            <img src="{{ upload_url($project->image) }}" alt="{{ $project->t('title') }}" loading="lazy" decoding="async"
                 width="1200" height="750" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="grid h-full place-items-center text-slate-300 dark:text-slate-600"><x-icon name="image" class="h-12 w-12" /></div>
        @endif
    </div>
    <div class="p-5">
        @if ($project->category)
            <p class="text-xs font-semibold uppercase tracking-wider text-accent-600 dark:text-accent-400">{{ $project->category->t('name') }}</p>
        @endif
        <h3 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ $project->t('title') }}</h3>
        @if ($project->t('summary'))
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ $project->t('summary') }}</p>
        @endif
        <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-500 dark:text-slate-400">
            @if ($project->client)
                <span>{{ __('portfolio.client') }}: <strong class="font-medium text-slate-700 dark:text-slate-300">{{ $project->client }}</strong></span>
            @endif
            @if ($project->year)
                <span>{{ __('portfolio.year') }}: {{ $project->year }}</span>
            @endif
            @if ($project->url)
                <a href="{{ $project->url }}" target="_blank" rel="noopener nofollow" class="ml-auto inline-flex items-center gap-1 font-semibold text-brand-700 hover:underline dark:text-brand-300">
                    {{ __('site.actions.visit_site') }} <x-icon name="external-link" class="h-3.5 w-3.5" />
                </a>
            @endif
        </div>
    </div>
</article>
