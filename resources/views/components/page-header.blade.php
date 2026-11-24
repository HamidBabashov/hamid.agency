@props(['eyebrow' => null, 'title', 'lead' => null])
<section class="relative overflow-hidden bg-brand-950 text-white">
    <div class="pointer-events-none absolute inset-0 opacity-40" aria-hidden="true"
         style="background-image: radial-gradient(circle at 15% 20%, rgba(52,102,246,.45), transparent 40%), radial-gradient(circle at 85% 80%, rgba(245,158,11,.25), transparent 40%);"></div>
    <div class="container relative py-16 sm:py-20">
        @if ($eyebrow)
            <p class="text-sm font-semibold uppercase tracking-wider text-accent-400">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-3 max-w-3xl text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl">{{ $title }}</h1>
        @if ($lead)
            <p class="mt-5 max-w-2xl text-lg text-slate-300">{{ $lead }}</p>
        @endif
        {{ $slot }}
    </div>
</section>
