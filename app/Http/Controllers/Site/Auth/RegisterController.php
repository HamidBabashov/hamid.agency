<?php

namespace App\Http\Controllers\Site\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    use RedirectsSafely;

    public function create(Request $request): View
    {
        return view('auth.register', ['redirect' => $this->safeRedirectPath($request)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9 +()\-]{7,30}$/'],
            'company' => ['nullable', 'string', 'max:150'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms' => ['accepted'],
        ], [], __('account.attributes'));

        $user = User::create([
            'name' => $data['name'],
            'email' => mb_strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'password' => $data['password'],
            'locale' => App::getLocale(),
        ]);

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectAfterAuth($request)->with('success', __('account.flash.registered'));
    }
}
