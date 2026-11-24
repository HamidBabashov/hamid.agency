@props(['title' => null, 'text' => null, 'service' => null])
<section class="section">
    <div class="container">
        <div class="relative overflow-hidden rounded-3xl bg-brand-950 px-6 py-14 text-center text-white sm:px-12">
            <div class="pointer-events-none absolute inset-0 opacity-50" aria-hidden="true"
                 style="background-image: radial-gradient(circle at 20% 0%, rgba(52,102,246,.5), transparent 45%), radial-gradient(circle at 80% 100%, rgba(245,158,11,.3), transparent 45%);"></div>
            <div class="relative mx-auto max-w-2xl">
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">{{ $title ?? __('home.cta.title') }}</h2>
                <p class="mt-4 text-lg text-slate-300">{{ $text ?? __('home.cta.text') }}</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ lroute('quote', array_filter(['service' => $service])) }}" class="btn-accent px-6 py-3 text-base">{{ __('site.actions.get_quote') }}</a>
                    <a href="{{ lroute('contact') }}" class="btn border border-white/30 px-6 py-3 text-base text-white hover:bg-white/10">{{ __('site.actions.contact_us') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
