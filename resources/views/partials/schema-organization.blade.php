@php
    $contact = config('agency.contact');
    $organization = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => __('site.brand'),
        'url' => url('/'),
        'logo' => asset('images/logo.png'),
        'description' => __('seo.default_description'),
        'email' => $contact['email'] ?: null,
        'telephone' => $contact['phone'] ?: null,
        'address' => $contact['address'] ? ['@type' => 'PostalAddress', 'streetAddress' => $contact['address'], 'addressCountry' => 'AZ'] : null,
        'sameAs' => array_values(array_filter(config('agency.social'))) ?: null,
    ]);
@endphp
<script type="application/ld+json">{!! json_encode($organization, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
