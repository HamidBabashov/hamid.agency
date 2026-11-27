@props(['testimonial'])
<figure class="card card-hover flex h-full flex-col p-7" data-reveal>
    <x-icon name="quote" class="h-8 w-8 text-brand-200 dark:text-brand-500/40" />
    <div class="mt-4 flex gap-0.5 text-amber-400" aria-label="{{ __('home.testimonials.rating', ['rating' => $testimonial->rating]) }}">
        @for ($i = 1; $i <= 5; $i++)
            <x-icon name="star" class="h-4 w-4 {{ $i <= $testimonial->rating ? 'fill-current' : 'text-slate-300 dark:text-slate-600' }}" />
        @endfor
    </div>
    <blockquote class="mt-4 flex-1 text-slate-700 dark:text-slate-300">
        <p>“{{ $testimonial->t('content') }}”</p>
    </blockquote>
    <figcaption class="mt-6 flex items-center gap-3">
        @if ($testimonial->photo)
            <img src="{{ upload_url($testimonial->photo) }}" alt="{{ $testimonial->author_name }}" loading="lazy" width="44" height="44" class="h-11 w-11 rounded-full object-cover">
        @else
            <span class="grid h-11 w-11 place-items-center rounded-full bg-brand-100 font-semibold text-brand-800 dark:bg-brand-900/50 dark:text-brand-200">{{ mb_substr($testimonial->author_name, 0, 1) }}</span>
        @endif
        <span>
            <span class="block font-semibold text-slate-900 dark:text-white">{{ $testimonial->author_name }}</span>
            @if ($testimonial->t('position') || $testimonial->company)
                <span class="block text-sm text-slate-500 dark:text-slate-400">{{ collect([$testimonial->t('position'), $testimonial->company])->filter()->implode(', ') }}</span>
            @endif
        </span>
    </figcaption>
</figure>
