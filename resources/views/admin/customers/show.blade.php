@extends('layouts.admin')
@section('title', $item->name)

@section('content')
    <a href="{{ route('admin.customers.index') }}" class="mb-6 inline-block text-sm font-medium text-brand-700 hover:underline dark:text-brand-300">← {{ __('admin.actions.back') }}</a>
    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.panel :title="__('admin.headings.contact_info')">
            <dl class="space-y-4">
                <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.email') }}</dt><dd class="mt-1"><a href="mailto:{{ $item->email }}" class="text-brand-700 hover:underline dark:text-brand-300">{{ $item->email }}</a></dd></div>
                <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.phone') }}</dt><dd class="mt-1">{{ $item->phone ?: __('admin.labels.none') }}</dd></div>
                <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.company') }}</dt><dd class="mt-1">{{ $item->company ?: __('admin.labels.none') }}</dd></div>
                <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.locale') }}</dt><dd class="mt-1">{{ strtoupper($item->locale) }}</dd></div>
                <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.registered_at') }}</dt><dd class="mt-1">{{ $item->created_at->format('d.m.Y H:i') }}</dd></div>
            </dl>
        </x-admin.panel>
        <div class="xl:col-span-2">
            <x-admin.table :headers="[__('admin.fields.number'), __('admin.fields.service_type'), __('admin.fields.created_at'), __('admin.fields.status')]" :empty="$orders->isEmpty()">
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">#{{ $order->id }}</td>
                        <td class="px-4 py-3">{{ __('site.service_types.'.$order->service_type) }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-4 py-3"><x-admin.status :status="$order->status" /></td>
                        <td class="px-4 py-3"><x-admin.row-actions :show="route('admin.orders.show', $order)" /></td>
                    </tr>
                @endforeach
            </x-admin.table>
        </div>
    </div>
@endsection
