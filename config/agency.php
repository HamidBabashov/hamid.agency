<?php

return [
    // Dillər: birinci element əsas dildir
    'locales' => ['az', 'en'],
    'default_locale' => 'az',

    // hreflang / Open Graph üçün dil kodları
    'locale_codes' => [
        'az' => ['hreflang' => 'az', 'og' => 'az_AZ'],
        'en' => ['hreflang' => 'en', 'og' => 'en_US'],
    ],

    'name' => env('APP_NAME', 'Hamid Agency'),

    // Əlaqə məlumatları — .env-dən
    'contact' => [
        'email' => env('AGENCY_EMAIL'),
        'phone' => env('AGENCY_PHONE'),
        'whatsapp' => env('AGENCY_WHATSAPP'), // yalnız rəqəmlər, məs. 994501234567
        'address' => env('AGENCY_ADDRESS'),
    ],

    'social' => [
        'instagram' => env('SOCIAL_INSTAGRAM'),
        'facebook' => env('SOCIAL_FACEBOOK'),
        'linkedin' => env('SOCIAL_LINKEDIN'),
        'github' => env('SOCIAL_GITHUB'),
    ],

    // Sifariş və mesaj bildirişləri bu ünvana göndərilir
    'notify_email' => env('NOTIFY_EMAIL', env('AGENCY_EMAIL')),

    'currency' => env('AGENCY_CURRENCY', 'AZN'),
    'currency_symbol' => env('AGENCY_CURRENCY_SYMBOL', '₼'),

    // Spam qorunması
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'min_score' => (float) env('RECAPTCHA_MIN_SCORE', 0.5),
    ],
    'honeypot_min_seconds' => (int) env('HONEYPOT_MIN_SECONDS', 3),

    // Şəkil yükləmə
    'images' => [
        'max_width' => 1600,
        'quality' => 80,
    ],

    // Admin hesabı (AdminSeeder). config:cache-dən sonra env() işləmədiyi üçün buradan oxunur.
    'admin' => [
        'name' => env('ADMIN_NAME', 'Admin'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

    // Fayl göndərmə ilə deploy (SSH olmadıqda) üçün gizli açar
    'deploy_token' => env('DEPLOY_TOKEN'),
];
