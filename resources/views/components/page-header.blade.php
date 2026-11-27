@props(['eyebrow' => null, 'title', 'lead' => null, 'compact' => false])
<section class="relative overflow-hidden bg-ink text-white">
    <x-aurora />
    <div @class(['container relative', 'pb-16 pt-32 sm:pb-20 sm:pt-40' => ! $compact, 'pb-12 pt-28 sm:pt-32' => $compact])>
        <div class="max-w-3xl" data-reveal>
            @if ($eyebrow)
                <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-accent-300 backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent-400"></span>{{ $eyebrow }}
                </p>
            @endif
            <h1 @class(['font-extrabold tracking-tight', 'mt-5 text-4xl sm:text-5xl lg:text-6xl' => ! $compact, 'mt-3 text-3xl sm:text-4xl' => $compact])>{{ $title }}</h1>
            @if ($lead)
                <p class="mt-5 max-w-2xl text-lg leading-relaxed text-slate-300">{{ $lead }}</p>
            @endif
        </div>
        {{ $slot }}
    </div>
</section>
