<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuoteRequest;
use App\Mail\NewOrder;
use App\Mail\OrderReceived;
use App\Models\DomainExtension;
use App\Models\HostingPlan;
use App\Models\Order;
use App\Models\Service;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function create(Request $request): View
    {
        $service = $request->query('service');

        return view('pages.quote', [
            'plans' => HostingPlan::active()->get(),
            'extensions' => DomainExtension::active()->get(),
            'prefill' => [
                'service_type' => in_array($service, Service::KEYS, true) ? $service : 'web_design',
                'plan' => $request->integer('plan') ?: null,
                'domain' => (string) $request->query('domain', ''),
                'billing' => $request->query('billing') === 'yearly' ? 'yearly' : 'monthly',
            ],
        ]);
    }

    public function store(QuoteRequest $request): RedirectResponse
    {
        $order = Order::create([
            'user_id' => $request->user()?->id,
            'service_type' => $request->input('service_type'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'company' => $request->input('company'),
            'message' => $request->input('message'),
            'details' => $request->details(),
            'locale' => App::getLocale(),
            'ip_address' => $request->ip(),
        ]);

        Notifier::send(new NewOrder($order));
        Notifier::customer($order->email, new OrderReceived($order), $order->locale);

        // Hesabı olan müştəri sifarişini dərhal görə bilər
        if ($order->user_id) {
            return redirect(lroute('account.orders.show', ['order' => $order->id]))
                ->with('success', __('quote.form.success', ['number' => $order->id]));
        }

        return redirect(lroute('quote'))->with('success', __('quote.form.success', ['number' => $order->id]));
    }
}
