<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $items = User::customers()
            ->withCount('orders')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->query('q').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('phone', 'like', $term)->orWhere('company', 'like', $term));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.customers.index', ['items' => $items]);
    }

    public function show(User $customer): View
    {
        abort_if($customer->is_admin, 404);

        return view('admin.customers.show', ['item' => $customer, 'orders' => $customer->orders()->latest()->get()]);
    }
}
