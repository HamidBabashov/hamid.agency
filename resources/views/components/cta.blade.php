@props(['title' => null, 'text' => null, 'service' => null])
<section class="section">
    <div class="container">
        <div class="relative isolate overflow-hidden rounded-[2.5rem] bg-ink px-6 py-16 text-center text-white shadow-glow sm:px-16 sm:py-20" data-reveal>
            <x-aurora />
            <div class="relative mx-auto max-w-2xl">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-accent-300 to-accent-500 text-brand-950 shadow-lg shadow-accent-500/30"><x-icon name="sparkles" class="h-7 w-7" /></span>
                <h2 class="mt-6 text-3xl font-bold tracking-tight sm:text-5xl">{{ $title ?? __('home.cta.title') }}</h2>
                <p class="mt-5 text-lg text-slate-300">{{ $text ?? __('home.cta.text') }}</p>
                <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ lroute('quote', array_filter(['service' => $service])) }}" class="btn-accent px-7 py-3.5 text-base">{{ __('site.actions.get_quote') }} <x-icon name="arrow-right" class="h-5 w-5" /></a>
                    <a href="{{ lroute('contact') }}" class="btn-glass px-7 py-3.5 text-base">{{ __('site.actions.contact_us') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
