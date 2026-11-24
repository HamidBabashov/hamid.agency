<?php

namespace App\Http\Middleware;

use App\Support\Recaptcha;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Formaları botlardan qoruyur:
 *  1) honeypot — gizli "website" sahəsi boş olmalıdır
 *  2) vaxt yoxlaması — forma çox tez göndərilməməlidir
 *  3) reCAPTCHA v3 — .env-də açarlar varsa
 */
class ProtectAgainstSpam
{
    public const HONEYPOT_FIELD = 'website';
    public const TIME_FIELD = '_hp_time';

    public function __construct(private Recaptcha $recaptcha)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (filled($request->input(self::HONEYPOT_FIELD)) || ! $this->passesTimeCheck($request)) {
            Log::notice('Spam form submission blocked', ['ip' => $request->ip(), 'path' => $request->path()]);

            return $this->reject();
        }

        if ($this->recaptcha->enabled() && ! $this->recaptcha->verify($request->input('g-recaptcha-response'), $request->ip())) {
            return $this->reject();
        }

        return $next($request);
    }

    private function passesTimeCheck(Request $request): bool
    {
        try {
            $startedAt = (int) Crypt::decryptString((string) $request->input(self::TIME_FIELD));
        } catch (DecryptException) {
            return false;
        }

        return (time() - $startedAt) >= config('agency.honeypot_min_seconds');
    }

    private function reject(): Response
    {
        return back()->withInput()->withErrors(['spam' => __('site.form.spam')]);
    }
}
