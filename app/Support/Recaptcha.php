<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class Recaptcha
{
    public function enabled(): bool
    {
        return filled(config('agency.recaptcha.site_key')) && filled(config('agency.recaptcha.secret_key'));
    }

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (blank($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->timeout(5)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('agency.recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ])->json();
        } catch (Throwable $e) {
            Log::warning('reCAPTCHA verification failed: '.$e->getMessage());

            return false;
        }

        return ($response['success'] ?? false)
            && (float) ($response['score'] ?? 0) >= config('agency.recaptcha.min_score');
    }
}
