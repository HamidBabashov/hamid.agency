<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusChanged;
use App\Models\Order;
use App\Models\Service;
use App\Support\Notifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $items = Order::query()
            ->status($request->query('status'))
            ->when(in_array($request->query('service'), Service::KEYS, true), fn ($q) => $q->where('service_type', $request->query('service')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->query('q').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('phone', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', ['items' => $items]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['item' => $order->load('user')]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $previous = $order->status;

        $order->update($request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
            'admin_note' => ['nullable', 'string', 'max:5000'],
        ], [], __('admin.fields')));

        // Status dəyişibsə və admin istəyirsə, müştəriyə öz dilində bildiriş
        if ($previous !== $order->status && $request->boolean('notify_customer')) {
            Notifier::customer($order->email, new OrderStatusChanged($order), $order->locale);
        }

        return back()->with('success', __('admin.flash.updated'));
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', __('admin.flash.deleted'));
    }
}
