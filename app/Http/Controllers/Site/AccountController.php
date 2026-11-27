<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->latest()->paginate(10);

        return view('account.index', [
            'orders' => $orders,
            'counts' => $request->user()->orders()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        // Müştəri yalnız öz sifarişini görə bilər
        abort_unless($order->user_id === $request->user()->id, 404);

        return view('account.show', ['order' => $order]);
    }

    public function profile(Request $request): View
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150', Rule::unique('users')->ignore($user)],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9 +()\-]{7,30}$/'],
            'company' => ['nullable', 'string', 'max:150'],
            'locale' => ['required', Rule::in(config('agency.locales'))],
        ], [], __('account.attributes'));

        $data['email'] = mb_strtolower($data['email']);
        $user->update($data);

        return redirect(lroute('account.profile', [], $user->locale))->with('success', __('account.flash.profile_updated', [], $user->locale));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [], __('account.attributes'));

        $request->user()->update(['password' => Hash::make($request->input('password'))]);

        return redirect(lroute('account.profile'))->with('success', __('account.flash.password_updated'));
    }
}
