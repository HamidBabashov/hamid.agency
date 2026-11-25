@if ($whatsapp = config('agency.contact.whatsapp'))
    <a href="https://wa.me/{{ preg_replace('/\D/', '', $whatsapp) }}?text={{ rawurlencode(__('site.whatsapp.message')) }}"
       target="_blank" rel="noopener" aria-label="{{ __('site.whatsapp.label') }}" title="{{ __('site.whatsapp.label') }}"
       class="fixed bottom-5 right-5 z-40 grid h-14 w-14 place-items-center rounded-full bg-[#25D366] text-white shadow-lg shadow-black/20 transition hover:scale-105 focus:outline-none focus-visible:ring-4 focus-visible:ring-[#25D366]/40">
        <x-icon name="whatsapp" class="h-7 w-7" />
    </a>
@endif
