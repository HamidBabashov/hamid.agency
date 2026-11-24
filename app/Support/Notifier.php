<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Admin bildirişləri. Email göndərilməsə belə məlumat bazada qalır —
 * istifadəçi xəta görmür, səbəb log faylına yazılır.
 */
class Notifier
{
    public static function send(Mailable $mailable): void
    {
        $to = config('agency.notify_email');

        if (blank($to)) {
            Log::warning('NOTIFY_EMAIL is not configured; notification email skipped.');

            return;
        }

        try {
            Mail::to($to)->locale(config('agency.default_locale'))->send($mailable);
        } catch (Throwable $e) {
            Log::error('Notification email failed: '.$e->getMessage());
        }
    }
}
