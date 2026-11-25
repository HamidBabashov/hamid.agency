@extends('layouts.admin')
@section('title', __('admin.nav.hosting_plans'))

@section('content')
    <x-admin.index-header :create="route('admin.hosting-plans.create')" />
    <x-admin.table :headers="[__('admin.fields.sort_order'), __('admin.fields.name'), __('admin.fields.price_monthly'), __('admin.fields.price_yearly'), __('admin.fields.status')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr>
                <td class="px-4 py-3 text-slate-500">{{ $item->sort_order }}</td>
                <td class="px-4 py-3">
                    <span class="font-medium text-slate-900 dark:text-white">{{ $item->name_az }}</span> <span class="text-xs text-slate-500">/ {{ $item->name_en }}</span>
                    @if ($item->is_featured)<span class="ml-2 rounded-full bg-accent-100 px-2 py-0.5 text-xs font-semibold text-accent-700">{{ __('admin.labels.featured') }}</span>@endif
                </td>
                <td class="whitespace-nowrap px-4 py-3">{{ money($item->price_monthly) }}</td>
                <td class="whitespace-nowrap px-4 py-3">{{ money($item->price_yearly) }}</td>
                <td class="px-4 py-3"><x-admin.active :value="$item->is_active" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.hosting-plans.edit', $item)" :destroy="route('admin.hosting-plans.destroy', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
@endsection
