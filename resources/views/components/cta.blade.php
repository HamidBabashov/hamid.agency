@props(['title' => null, 'text' => null, 'service' => null])
<section class="pb-20 sm:pb-24">
    <div class="container">
        <div class="relative isolate overflow-hidden rounded-[2rem] bg-ink px-6 py-14 text-center text-white sm:px-16 sm:py-16" data-reveal>
            <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true" style="background: radial-gradient(50% 80% at 50% 0%, rgba(52,102,246,.35), transparent 70%);"></div>
            <div class="relative mx-auto max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">{{ $title ?? __('home.cta.title') }}</h2>
                <p class="mt-4 text-lg text-slate-300">{{ $text ?? __('home.cta.text') }}</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ lroute('quote', array_filter(['service' => $service])) }}" class="btn-accent px-7 py-3.5 text-base">{{ __('site.actions.get_quote') }} <x-icon name="arrow-right" class="h-5 w-5" /></a>
                    <a href="{{ lroute('contact') }}" class="btn-glass px-7 py-3.5 text-base">{{ __('site.actions.contact_us') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
