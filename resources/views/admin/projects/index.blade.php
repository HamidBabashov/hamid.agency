@extends('layouts.admin')
@section('title', __('admin.nav.projects'))

@section('content')
    <x-admin.index-header :create="route('admin.projects.create')" />
    <x-admin.table :headers="[__('admin.fields.image'), __('admin.fields.title'), __('admin.fields.project_category_id'), __('admin.fields.year'), __('admin.fields.status')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr>
                <td class="px-4 py-3">
                    @if ($item->image)<img src="{{ upload_url($item->image) }}" alt="" class="h-12 w-20 rounded object-cover" loading="lazy">@else<span class="grid h-12 w-20 place-items-center rounded bg-slate-100 text-slate-400 dark:bg-slate-800"><x-icon name="image" class="h-5 w-5" /></span>@endif
                </td>
                <td class="px-4 py-3"><span class="font-medium text-slate-900 dark:text-white">{{ $item->title_az }}</span>
                    @if ($item->is_featured)<span class="ml-2 rounded-full bg-accent-100 px-2 py-0.5 text-xs font-semibold text-accent-700">{{ __('admin.labels.featured') }}</span>@endif
                    <span class="block text-xs text-slate-500">{{ $item->title_en }}</span></td>
                <td class="px-4 py-3">{{ $item->category?->name_az ?? __('admin.labels.none') }}</td>
                <td class="px-4 py-3">{{ $item->year ?? __('admin.labels.none') }}</td>
                <td class="px-4 py-3"><x-admin.active :value="$item->is_active" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.projects.edit', $item)" :destroy="route('admin.projects.destroy', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
    <div class="mt-6">{{ $items->links() }}</div>
@endsection
