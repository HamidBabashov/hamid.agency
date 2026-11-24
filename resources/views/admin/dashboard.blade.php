@extends('layouts.admin')
@section('title', __('admin.nav.dashboard'))

@section('content')
    <p class="mb-6 text-slate-600 dark:text-slate-400">{{ __('admin.dashboard.welcome', ['name' => auth()->user()->name]) }}</p>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['new_orders', 'cart', route('admin.orders.index', ['status' => 'new'])],
            ['new_messages', 'inbox', route('admin.messages.index', ['status' => 'new'])],
            ['projects', 'image', route('admin.projects.index')],
            ['testimonials', 'quote', route('admin.testimonials.index')],
        ] as [$key, $icon, $url])
            <a href="{{ $url }}" class="card flex items-center gap-4 p-5 transition hover:border-brand-300">
                <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand-50 text-brand-700 dark:bg-brand-900/40 dark:text-brand-300"><x-icon :name="$icon" class="h-6 w-6" /></span>
                <span>
                    <span class="block text-2xl font-bold text-slate-900 dark:text-white">{{ $stats[$key] }}</span>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('admin.dashboard.'.$key) }}</span>
                </span>
            </a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 xl:grid-cols-2">
        <x-admin.panel :title="__('admin.dashboard.latest_orders')">
            <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($latestOrders as $order)
                    <li class="flex items-center justify-between gap-3 py-3">
                        <a href="{{ route('admin.orders.show', $order) }}" class="min-w-0">
                            <span class="block truncate font-medium text-slate-900 hover:text-brand-700 dark:text-white">#{{ $order->id }} · {{ $order->name }}</span>
                            <span class="text-xs text-slate-500">{{ __('site.service_types.'.$order->service_type) }} · {{ $order->created_at->format('d.m.Y H:i') }}</span>
                        </a>
                        <x-admin.status :status="$order->status" />
                    </li>
                @empty
                    <li class="py-3 text-sm text-slate-500">{{ __('admin.empty') }}</li>
                @endforelse
            </ul>
        </x-admin.panel>
        <x-admin.panel :title="__('admin.dashboard.latest_messages')">
            <ul class="divide-y divide-slate-200 dark:divide-slate-800">
                @forelse ($latestMessages as $message)
                    <li class="flex items-center justify-between gap-3 py-3">
                        <a href="{{ route('admin.messages.show', $message) }}" class="min-w-0">
                            <span class="block truncate font-medium text-slate-900 hover:text-brand-700 dark:text-white">{{ $message->name }}</span>
                            <span class="block truncate text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($message->message, 70) }}</span>
                        </a>
                        <x-admin.status :status="$message->status" />
                    </li>
                @empty
                    <li class="py-3 text-sm text-slate-500">{{ __('admin.empty') }}</li>
                @endforelse
            </ul>
        </x-admin.panel>
    </div>
@endsection
