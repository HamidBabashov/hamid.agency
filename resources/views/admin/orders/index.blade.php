@extends('layouts.admin')
@section('title', __('admin.nav.orders'))

@section('content')
    @include('admin.partials-filter', ['withService' => true])
    <x-admin.table :headers="[__('admin.fields.number'), __('admin.fields.name'), __('admin.fields.service_type'), __('admin.fields.phone'), __('admin.fields.created_at'), __('admin.fields.status')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr @class(['bg-accent-50/50 dark:bg-accent-500/5' => $item->status === 'new'])>
                <td class="px-4 py-3 font-mono text-xs">#{{ $item->id }}</td>
                <td class="px-4 py-3"><a href="{{ route('admin.orders.show', $item) }}" class="font-medium text-slate-900 hover:text-brand-700 dark:text-white">{{ $item->name }}</a><span class="block text-xs text-slate-500">{{ $item->email }}</span></td>
                <td class="px-4 py-3">{{ __('site.service_types.'.$item->service_type) }}</td>
                <td class="whitespace-nowrap px-4 py-3">{{ $item->phone }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $item->created_at->format('d.m.Y H:i') }}</td>
                <td class="px-4 py-3"><x-admin.status :status="$item->status" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :show="route('admin.orders.show', $item)" :destroy="route('admin.orders.destroy', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
    <div class="mt-6">{{ $items->links() }}</div>
@endsection
