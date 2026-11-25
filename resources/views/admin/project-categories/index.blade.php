@extends('layouts.admin')
@section('title', __('admin.nav.project_categories'))

@section('content')
    <x-admin.index-header :create="route('admin.project-categories.create')" />
    <x-admin.table :headers="[__('admin.fields.sort_order'), __('admin.fields.name'), __('admin.fields.slug'), __('admin.labels.projects_count')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr>
                <td class="px-4 py-3 text-slate-500">{{ $item->sort_order }}</td>
                <td class="px-4 py-3"><span class="font-medium text-slate-900 dark:text-white">{{ $item->name_az }}</span> <span class="text-xs text-slate-500">/ {{ $item->name_en }}</span></td>
                <td class="px-4 py-3 font-mono text-xs">{{ $item->slug }}</td>
                <td class="px-4 py-3">{{ $item->projects_count }}</td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.project-categories.edit', $item)" :destroy="route('admin.project-categories.destroy', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
@endsection
