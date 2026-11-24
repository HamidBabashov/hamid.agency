<?php

return [
    'eyebrow' => 'Domains',
    'title' => 'Register your domain name today',
    'lead' => 'Search, register or transfer domains across popular extensions, including .az.',
    'search' => [
        'label' => 'Domain name',
        'placeholder' => 'yourcompany.com',
        'button' => 'Check',
        'searching' => 'Checking...',
        'hint' => 'Leave out the extension and we will check all the popular ones.',
        'results' => 'Search results',
    ],
    'status' => [
        'available' => 'Available',
        'taken' => 'Taken',
        'unknown' => 'Needs confirmation',
    ],
    'unknown_hint' => 'We couldn’t verify this automatically — place an order and we will check it for you.',
    'register' => 'Register',
    'order' => 'Order now',
    'request' => 'Send request',
    'per_year' => 'yr',
    'prices' => [
        'title' => 'Domain pricing',
        'lead' => 'Prices are for a 1-year registration and shown in :currency.',
        'extension' => 'Extension',
        'register' => 'Register',
        'renew' => 'Renew',
        'transfer' => 'Transfer',
        'empty' => 'Extension prices will be added soon.',
    ],
    'info' => [
        'title' => 'Good to know about domains',
        'items' => [
            ['icon' => 'globe', 'title' => '.az domains', 'text' => 'Registering a .az domain may require ID or company details — we will guide you through the process.'],
            ['icon' => 'arrow-right', 'title' => 'Transfers', 'text' => 'Transfer your existing domain to us with its EPP code and manage it together with your hosting.'],
            ['icon' => 'shield', 'title' => 'DNS management', 'text' => 'We configure DNS records, email and subdomain settings for you.'],
        ],
    ],
    'errors' => [
        'empty' => 'Please enter a domain name.',
        'invalid' => 'A domain name may only contain latin letters, numbers and hyphens (-).',
        'unsupported' => 'The :ext extension is not available at the moment.',
        'no_extensions' => 'No domain extensions have been added yet.',
    ],
];
