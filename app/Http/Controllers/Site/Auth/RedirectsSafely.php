<?php

namespace App\Http\Controllers\Site\Auth;

use Illuminate\Http\Request;

/**
 * Girişdən sonra geri qayıtmaq üçün yalnız saytdaxili nisbi yollara icazə verilir
 * (açıq yönləndirmə — open redirect — hücumlarının qarşısını alır).
 */
trait RedirectsSafely
{
    protected function safeRedirectPath(Request $request): ?string
    {
        $path = (string) $request->input('redirect', $request->query('redirect', ''));

        return preg_match('#^/(?!/)[A-Za-z0-9\-._~/?=&%]*$#', $path) && ! str_contains($path, '\\') ? $path : null;
    }

    protected function redirectAfterAuth(Request $request)
    {
        return redirect($this->safeRedirectPath($request) ?? lroute('account'));
    }
}
