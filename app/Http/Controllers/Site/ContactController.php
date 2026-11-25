<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        $message = ContactMessage::create($request->validated() + [
            'locale' => App::getLocale(),
            'ip_address' => $request->ip(),
        ]);

        Notifier::send(new NewContactMessage($message));

        return redirect(lroute('contact'))->with('success', __('contact.form.success'));
    }
}
