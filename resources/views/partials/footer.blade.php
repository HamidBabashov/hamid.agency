@php
    $contact = config('agency.contact');
    $social = array_filter(config('agency.social'));
@endphp
<footer class="relative overflow-hidden bg-ink text-slate-400">
    <div class="aurora-blob -left-40 -top-40 h-96 w-96 bg-brand-600/30" aria-hidden="true"></div>
    <div class="aurora-blob -bottom-40 right-0 h-80 w-80 bg-accent-500/10 motion-safe:animate-aurora-slow" aria-hidden="true"></div>

    <div class="container relative grid gap-12 pb-12 pt-20 sm:grid-cols-2 lg:grid-cols-12">
        <div class="lg:col-span-4">
            <a href="{{ lroute('home') }}" class="flex items-center gap-2.5 text-lg font-extrabold tracking-tight text-white">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-600 to-brand-900 text-sm text-accent-300 shadow-md shadow-brand-700/30">H</span>
                <span>Hamid<span class="text-accent-400">Agency</span></span>
            </a>
            <p class="mt-5 max-w-sm text-sm leading-relaxed">{{ __('site.footer.about') }}</p>
            @if ($social)
                <div class="mt-6 flex gap-2" aria-label="{{ __('site.footer.follow') }}">
                    @foreach ($social as $network => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"
                           class="grid h-10 w-10 place-items-center rounded-full border border-white/10 bg-white/5 text-slate-300 transition hover:border-white/30 hover:text-white">
                            <x-icon :name="$network" class="h-4 w-4" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="lg:col-span-3">
            <h2 class="text-sm font-semibold text-white">{{ __('site.footer.services') }}</h2>
            <ul class="mt-5 space-y-3 text-sm">
                @foreach ($navServices as $service)
                    <li><a href="{{ $service->url() }}" class="transition hover:text-white">{{ $service->t('title') }}</a></li>
                @endforeach
                <li><a href="{{ lroute('hosting') }}" class="transition hover:text-white">{{ __('services.hosting_plans') }}</a></li>
            </ul>
        </div>

        <div class="lg:col-span-2">
            <h2 class="text-sm font-semibold text-white">{{ __('site.footer.company') }}</h2>
            <ul class="mt-5 space-y-3 text-sm">
                @foreach (['about', 'portfolio', 'domains', 'contact', 'quote'] as $route)
                    <li><a href="{{ lroute($route) }}" class="transition hover:text-white">{{ __('site.nav.'.$route) }}</a></li>
                @endforeach
                <li><a href="{{ auth()->check() ? lroute('account') : lroute('login') }}" class="transition hover:text-white">{{ __('account.nav.account') }}</a></li>
            </ul>
        </div>

        <div class="lg:col-span-3">
            <h2 class="text-sm font-semibold text-white">{{ __('site.footer.contact') }}</h2>
            <ul class="mt-5 space-y-3 text-sm">
                @if ($contact['email'])
                    <li class="flex items-center gap-2.5"><x-icon name="mail" class="h-4 w-4 shrink-0 text-accent-400" /><a href="mailto:{{ $contact['email'] }}" class="transition hover:text-white">{{ $contact['email'] }}</a></li>
                @endif
                @if ($contact['phone'])
                    <li class="flex items-center gap-2.5"><x-icon name="phone" class="h-4 w-4 shrink-0 text-accent-400" /><a href="tel:{{ preg_replace('/[^\d+]/', '', $contact['phone']) }}" class="transition hover:text-white">{{ $contact['phone'] }}</a></li>
                @endif
                @if ($contact['address'])
                    <li class="flex items-start gap-2.5"><x-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-accent-400" /><span>{{ $contact['address'] }}</span></li>
                @endif
            </ul>
            <a href="{{ lroute('contact') }}" class="btn-glass mt-6">{{ __('site.actions.contact_us') }} <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>
    </div>

    <div class="container relative">
        <div class="flex flex-col items-center justify-between gap-4 border-t border-white/10 py-8 text-sm sm:flex-row">
            <p>&copy; {{ date('Y') }} {{ __('site.brand') }}. {{ __('site.footer.rights') }}</p>
            <nav class="flex gap-6" aria-label="{{ __('site.footer.legal') }}">
                <a href="{{ lroute('privacy') }}" class="transition hover:text-white">{{ __('legal.privacy.title') }}</a>
                <a href="{{ lroute('terms') }}" class="transition hover:text-white">{{ __('legal.terms.title') }}</a>
            </nav>
        </div>
    </div>
</footer>
