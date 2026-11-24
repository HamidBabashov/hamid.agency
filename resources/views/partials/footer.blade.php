@php
    $contact = config('agency.contact');
    $social = array_filter(config('agency.social'));
@endphp
<footer class="border-t border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900/50">
    <div class="container grid gap-10 py-14 sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <a href="{{ lroute('home') }}" class="flex items-center gap-2 text-lg font-extrabold tracking-tight text-brand-950 dark:text-white">
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand-950 text-sm text-accent-400 dark:bg-brand-600 dark:text-white">H</span>
                <span>Hamid<span class="text-accent-500">Agency</span></span>
            </a>
            <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ __('site.footer.about') }}</p>
            @if ($social)
                <div class="mt-5 flex gap-2" aria-label="{{ __('site.footer.follow') }}">
                    @foreach ($social as $network => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($network) }}"
                           class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-600 hover:border-brand-500 hover:text-brand-600 dark:border-slate-700 dark:text-slate-400 dark:hover:text-white">
                            <x-icon :name="$network" class="h-4 w-4" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-900 dark:text-white">{{ __('site.footer.services') }}</h2>
            <ul class="mt-4 space-y-2.5 text-sm">
                @foreach ($navServices as $service)
                    <li><a href="{{ $service->url() }}" class="text-slate-600 hover:text-brand-600 dark:text-slate-400 dark:hover:text-white">{{ $service->t('title') }}</a></li>
                @endforeach
                <li><a href="{{ lroute('hosting') }}" class="text-slate-600 hover:text-brand-600 dark:text-slate-400 dark:hover:text-white">{{ __('services.hosting_plans') }}</a></li>
            </ul>
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-900 dark:text-white">{{ __('site.footer.company') }}</h2>
            <ul class="mt-4 space-y-2.5 text-sm">
                @foreach (['about', 'portfolio', 'domains', 'contact', 'quote'] as $route)
                    <li><a href="{{ lroute($route) }}" class="text-slate-600 hover:text-brand-600 dark:text-slate-400 dark:hover:text-white">{{ __('site.nav.'.$route) }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-900 dark:text-white">{{ __('site.footer.contact') }}</h2>
            <ul class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-400">
                @if ($contact['email'])
                    <li class="flex items-center gap-2"><x-icon name="mail" class="h-4 w-4 shrink-0" /><a href="mailto:{{ $contact['email'] }}" class="hover:text-brand-600 dark:hover:text-white">{{ $contact['email'] }}</a></li>
                @endif
                @if ($contact['phone'])
                    <li class="flex items-center gap-2"><x-icon name="phone" class="h-4 w-4 shrink-0" /><a href="tel:{{ preg_replace('/[^\d+]/', '', $contact['phone']) }}" class="hover:text-brand-600 dark:hover:text-white">{{ $contact['phone'] }}</a></li>
                @endif
                @if ($contact['address'])
                    <li class="flex items-start gap-2"><x-icon name="map-pin" class="mt-0.5 h-4 w-4 shrink-0" /><span>{{ $contact['address'] }}</span></li>
                @endif
                <li><a href="{{ lroute('contact') }}" class="font-medium text-brand-700 hover:underline dark:text-brand-300">{{ __('site.actions.contact_us') }} →</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-200 dark:border-slate-800">
        <div class="container flex flex-col items-center justify-between gap-3 py-6 text-sm text-slate-500 sm:flex-row dark:text-slate-400">
            <p>&copy; {{ date('Y') }} {{ __('site.brand') }}. {{ __('site.footer.rights') }}</p>
            <nav class="flex gap-5" aria-label="{{ __('site.footer.legal') }}">
                <a href="{{ lroute('privacy') }}" class="hover:text-brand-600 dark:hover:text-white">{{ __('legal.privacy.title') }}</a>
                <a href="{{ lroute('terms') }}" class="hover:text-brand-600 dark:hover:text-white">{{ __('legal.terms.title') }}</a>
            </nav>
        </div>
    </div>
</footer>
