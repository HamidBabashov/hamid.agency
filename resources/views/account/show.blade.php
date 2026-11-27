@extends('layouts.app')

@section('title', __('account.orders.number', ['number' => $order->id]))
@section('robots', 'noindex, nofollow')

@section('content')
    @php
        $steps = \App\Models\Order::STATUSES;
        $currentIndex = array_search($order->status, $steps, true);
    @endphp
    <x-account-shell :title="__('account.orders.number', ['number' => $order->id])" :lead="__('site.service_types.'.$order->service_type).' · '.$order->created_at->translatedFormat('d F Y, H:i')">
        <a href="{{ lroute('account') }}" class="mb-6 inline-flex items-center gap-1 text-sm font-semibold text-brand-700 hover:underline dark:text-brand-300">← {{ __('account.orders.back') }}</a>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                {{-- Gedişat --}}
                <div class="card p-6 sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('account.orders.progress') }}</h2>
                        <x-order-status :status="$order->status" />
                    </div>
                    <ol class="mt-8 grid grid-cols-3 gap-2">
                        @foreach ($steps as $i => $step)
                            <li class="relative">
                                <div @class(['h-1.5 rounded-full', 'bg-gradient-to-r from-brand-500 to-sky-400' => $i <= $currentIndex, 'bg-slate-200 dark:bg-white/10' => $i > $currentIndex])></div>
                                <p @class(['mt-3 text-sm font-semibold', 'text-slate-900 dark:text-white' => $i <= $currentIndex, 'text-slate-400' => $i > $currentIndex])>{{ __('account.statuses.'.$step) }}</p>
                            </li>
                        @endforeach
                    </ol>
                    <p class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600 dark:bg-white/5 dark:text-slate-300">{{ __('account.status_hints.'.$order->status) }}</p>
                </div>

                <div class="card p-6 sm:p-8">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('account.orders.details') }}</h2>
                    <dl class="mt-6 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                        <div><dt class="text-xs font-medium uppercase tracking-wider text-slate-500">{{ __('account.orders.service') }}</dt><dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ __('site.service_types.'.$order->service_type) }}</dd></div>
                        @foreach ($order->detailRows() as $row)
                            <div><dt class="text-xs font-medium uppercase tracking-wider text-slate-500">{{ $row['label'] }}</dt><dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $row['value'] }}</dd></div>
                        @endforeach
                    </dl>
                    @if ($order->message)
                        <h3 class="mt-8 text-xs font-medium uppercase tracking-wider text-slate-500">{{ __('account.orders.message') }}</h3>
                        <p class="mt-2 whitespace-pre-line rounded-2xl bg-slate-50 p-4 text-slate-700 dark:bg-white/5 dark:text-slate-300">{{ $order->message }}</p>
                    @endif
                </div>
            </div>

            <aside class="space-y-6">
                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('account.orders.contact') }}</h2>
                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex items-center gap-2.5"><x-icon name="user" class="h-4 w-4 text-slate-400" /><dd>{{ $order->name }}</dd></div>
                        <div class="flex items-center gap-2.5"><x-icon name="mail" class="h-4 w-4 text-slate-400" /><dd>{{ $order->email }}</dd></div>
                        @if ($order->phone)<div class="flex items-center gap-2.5"><x-icon name="phone" class="h-4 w-4 text-slate-400" /><dd>{{ $order->phone }}</dd></div>@endif
                        @if ($order->company)<div class="flex items-center gap-2.5"><x-icon name="layers" class="h-4 w-4 text-slate-400" /><dd>{{ $order->company }}</dd></div>@endif
                    </dl>
                </div>
                <div class="card p-6">
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('account.orders.help') }}</p>
                    <a href="{{ lroute('contact') }}" class="btn-outline mt-4 w-full">{{ __('site.actions.contact_us') }}</a>
                </div>
            </aside>
        </div>
    </x-account-shell>
@endsection
