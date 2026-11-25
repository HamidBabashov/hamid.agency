<?php

return [
    /*
    | Domen mövcudluğunu yoxlayan provayder.
    | Dəstəklənənlər: "whois" (pulsuz, API açarı lazım deyil),
    | "resellerclub", "namecheap", "whmcs".
    */
    'provider' => env('DOMAIN_PROVIDER', 'whois'),

    // Nəticələrin keşdə saxlanma müddəti (saniyə)
    'cache_ttl' => (int) env('DOMAIN_CACHE_TTL', 600),

    // Uzantı yazılmadıqda yoxlanılacaq maksimum uzantı sayı
    'max_suggestions' => 6,

    'providers' => [
        'whois' => [
            'timeout' => 5,
        ],

        'resellerclub' => [
            'auth_userid' => env('RESELLERCLUB_AUTH_USERID'),
            'api_key' => env('RESELLERCLUB_API_KEY'),
            'sandbox' => (bool) env('RESELLERCLUB_SANDBOX', true),
        ],

        'namecheap' => [
            'api_user' => env('NAMECHEAP_API_USER'),
            'api_key' => env('NAMECHEAP_API_KEY'),
            'username' => env('NAMECHEAP_USERNAME'),
            'client_ip' => env('NAMECHEAP_CLIENT_IP'),
            'sandbox' => (bool) env('NAMECHEAP_SANDBOX', true),
        ],

        'whmcs' => [
            'url' => env('WHMCS_URL'), // məs. https://billing.hamid.agency
            'identifier' => env('WHMCS_API_IDENTIFIER'),
            'secret' => env('WHMCS_API_SECRET'),
        ],
    ],
];
