@extends('layouts.admin')
@section('title', __('admin.nav.messages'))

@section('content')
    @include('admin.partials-filter')
    <x-admin.table :headers="[__('admin.fields.name'), __('admin.fields.message'), __('admin.fields.service_type'), __('admin.fields.created_at'), __('admin.fields.status')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr @class(['bg-accent-50/50 dark:bg-accent-500/5' => $item->status === 'new'])>
                <td class="px-4 py-3"><a href="{{ route('admin.messages.show', $item) }}" class="font-medium text-slate-900 hover:text-brand-700 dark:text-white">{{ $item->name }}</a><span class="block text-xs text-slate-500">{{ $item->email }}</span></td>
                <td class="max-w-md px-4 py-3 text-slate-600 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($item->message, 80) }}</td>
                <td class="px-4 py-3">{{ $item->service_type ? __('site.service_types.'.$item->service_type) : __('admin.labels.none') }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-slate-500">{{ $item->created_at->format('d.m.Y H:i') }}</td>
                <td class="px-4 py-3"><x-admin.status :status="$item->status" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :show="route('admin.messages.show', $item)" :destroy="route('admin.messages.destroy', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
    <div class="mt-6">{{ $items->links() }}</div>
@endsection
