@extends('layouts.admin')
@section('title', __('admin.nav.customers'))

@section('content')
    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
        <label for="f-q" class="sr-only">{{ __('admin.actions.search') }}</label>
        <input id="f-q" type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('admin.filters.search_placeholder') }}" class="input w-64">
        <button class="btn-primary">{{ __('admin.actions.search') }}</button>
        @if (request()->filled('q'))
            <a href="{{ url()->current() }}" class="btn-outline">{{ __('admin.actions.reset') }}</a>
        @endif
    </form>
    <x-admin.table :headers="[__('admin.fields.name'), __('admin.fields.phone'), __('admin.fields.company'), __('admin.fields.orders_count'), __('admin.fields.registered_at')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr>
                <td class="px-4 py-3"><a href="{{ route('admin.customers.show', $item) }}" class="font-medium text-slate-900 hover:text-brand-700 dark:text-white">{{ $item->name }}</a><span class="block text-xs text-slate-500">{{ $item->email }}</span></td>
                <td class="whitespace-nowrap px-4 py-3">{{ $item->phone ?: __('admin.labels.none') }}</td>
                <td class="px-4 py-3">{{ $item->company ?: __('admin.labels.none') }}</td>
                <td class="px-4 py-3">{{ $item->orders_count }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $item->created_at->format('d.m.Y') }}</td>
                <td class="px-4 py-3"><x-admin.row-actions :show="route('admin.customers.show', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
    <div class="mt-6">{{ $items->links() }}</div>
@endsection
