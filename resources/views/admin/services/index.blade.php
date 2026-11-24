@extends('layouts.admin')
@section('title', __('admin.nav.services'))

@section('content')
    <x-admin.index-header :create="route('admin.services.create')" />
    <x-admin.table :headers="[__('admin.fields.sort_order'), __('admin.fields.title'), __('admin.fields.key'), __('admin.fields.status')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr>
                <td class="px-4 py-3 text-slate-500">{{ $item->sort_order }}</td>
                <td class="px-4 py-3"><span class="flex items-center gap-2 font-medium text-slate-900 dark:text-white"><x-icon :name="$item->icon" class="h-4 w-4 text-brand-600" />{{ $item->title_az }}</span><span class="text-xs text-slate-500">{{ $item->title_en }}</span></td>
                <td class="px-4 py-3 font-mono text-xs">{{ $item->key }}</td>
                <td class="px-4 py-3"><x-admin.active :value="$item->is_active" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.services.edit', $item)" :destroy="route('admin.services.destroy', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
@endsection
