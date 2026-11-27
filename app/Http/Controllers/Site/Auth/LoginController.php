<?php

namespace App\Http\Controllers\Site\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    use RedirectsSafely;

    public function create(Request $request): View
    {
        return view('auth.login', ['redirect' => $this->safeRedirectPath($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [], __('account.attributes'));

        $credentials['email'] = mb_strtolower($credentials['email']);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }

        $request->session()->regenerate();

        // Son istifadə olunan dili yadda saxla (emaillər bu dildə gedəcək)
        $request->user()->forceFill(['locale' => App::getLocale()])->save();

        return $this->redirectAfterAuth($request);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(lroute('home'));
    }
}
