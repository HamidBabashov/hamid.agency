@extends('layouts.admin')
@section('title', __('admin.nav.domain_extensions'))

@section('content')
    <x-admin.index-header :create="route('admin.domain-extensions.create')" />
    <x-admin.table :headers="[__('admin.fields.sort_order'), __('admin.fields.extension'), __('admin.fields.register_price'), __('admin.fields.renew_price'), __('admin.fields.transfer_price'), __('admin.fields.status')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr>
                <td class="px-4 py-3 text-slate-500">{{ $item->sort_order }}</td>
                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">{{ $item->label() }}
                    @if ($item->is_featured)<span class="ml-2 rounded-full bg-accent-100 px-2 py-0.5 text-xs font-semibold text-accent-700">{{ __('admin.labels.featured') }}</span>@endif
                </td>
                <td class="whitespace-nowrap px-4 py-3">{{ money($item->register_price) }}</td>
                <td class="whitespace-nowrap px-4 py-3">{{ money($item->renew_price) }}</td>
                <td class="whitespace-nowrap px-4 py-3">{{ $item->transfer_price !== null ? money($item->transfer_price) : __('admin.labels.none') }}</td>
                <td class="px-4 py-3"><x-admin.active :value="$item->is_active" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.domain-extensions.edit', $item)" :destroy="route('admin.domain-extensions.destroy', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
@endsection
