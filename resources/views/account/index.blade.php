@extends('layouts.app')

@section('title', __('account.dashboard.title'))
@section('robots', 'noindex, nofollow')

@section('content')
    @php $icons = ['web_design' => 'monitor', 'mobile_app' => 'smartphone', 'hosting' => 'server', 'domain' => 'globe']; @endphp
    <x-account-shell :title="__('account.dashboard.welcome', ['name' => auth()->user()->name])" :lead="__('account.dashboard.lead')">
        <div class="grid gap-4 sm:grid-cols-4">
            <div class="card p-5">
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('account.dashboard.total') }}</p>
                <p class="mt-1 text-3xl font-bold text-slate-900 dark:text-white">{{ $counts->sum() }}</p>
            </div>
            @foreach (\App\Models\Order::STATUSES as $status)
                <div class="card p-5">
                    <x-order-status :status="$status" />
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ $counts[$status] ?? 0 }}</p>
                </div>
            @endforeach
        </div>

        <h2 class="mb-5 mt-12 text-xl font-bold text-slate-900 dark:text-white">{{ __('account.dashboard.orders') }}</h2>

        @if ($orders->isEmpty())
            <div class="card px-6 py-16 text-center">
                <span class="icon-tile mx-auto"><x-icon name="cart" class="h-6 w-6" /></span>
                <h3 class="mt-6 text-lg font-semibold text-slate-900 dark:text-white">{{ __('account.dashboard.empty_title') }}</h3>
                <p class="mx-auto mt-2 max-w-md text-slate-600 dark:text-slate-400">{{ __('account.dashboard.empty_text') }}</p>
                <a href="{{ lroute('quote') }}" class="btn-accent mt-8">{{ __('account.dashboard.new_order') }}</a>
            </div>
        @else
            <ul class="space-y-3">
                @foreach ($orders as $order)
                    <li>
                        <a href="{{ lroute('account.orders.show', ['order' => $order->id]) }}" class="card card-hover flex flex-wrap items-center gap-4 p-5">
                            <span class="icon-tile-soft"><x-icon :name="$icons[$order->service_type] ?? 'code'" class="h-5 w-5" /></span>
                            <span class="min-w-0 flex-1">
                                <span class="block font-semibold text-slate-900 dark:text-white">{{ __('site.service_types.'.$order->service_type) }}</span>
                                <span class="block text-sm text-slate-500 dark:text-slate-400">{{ __('account.orders.number', ['number' => $order->id]) }} · {{ $order->created_at->translatedFormat('d F Y') }}</span>
                            </span>
                            <x-order-status :status="$order->status" />
                            <x-icon name="arrow-right" class="hidden h-5 w-5 text-slate-400 sm:block" />
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </x-account-shell>
@endsection
