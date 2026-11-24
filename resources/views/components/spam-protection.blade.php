{{-- Honeypot + vaxt yoxlaması + (istəyə bağlı) reCAPTCHA v3 --}}
<div class="absolute -left-[9999px] h-0 w-0 overflow-hidden" aria-hidden="true">
    <label for="hp-website">{{ __('site.form.honeypot') }}</label>
    <input type="text" id="hp-website" name="{{ \App\Http\Middleware\ProtectAgainstSpam::HONEYPOT_FIELD }}" value="" tabindex="-1" autocomplete="off">
</div>
<input type="hidden" name="{{ \App\Http\Middleware\ProtectAgainstSpam::TIME_FIELD }}" value="{{ \Illuminate\Support\Facades\Crypt::encryptString((string) time()) }}">

@if ($siteKey = config('agency.recaptcha.site_key'))
    <input type="hidden" name="g-recaptcha-response" value="">
    @once
        @push('scripts')
            <script src="https://www.google.com/recaptcha/api.js?render={{ $siteKey }}" async defer></script>
            <script>
                document.addEventListener('submit', function (e) {
                    var form = e.target;
                    var input = form.querySelector('input[name="g-recaptcha-response"]');
                    if (!input || form.dataset.recaptchaDone || !window.grecaptcha) return;
                    e.preventDefault();
                    grecaptcha.ready(function () {
                        grecaptcha.execute(@js($siteKey), { action: 'submit' }).then(function (token) {
                            input.value = token;
                            form.dataset.recaptchaDone = '1';
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        });
                    });
                }, true);
            </script>
        @endpush
    @endonce
@endif
