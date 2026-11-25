@extends('layouts.admin')
@section('title', __('admin.nav.testimonials'))

@section('content')
    <x-admin.index-header :create="route('admin.testimonials.create')">
        <p class="text-sm text-slate-500">{{ __('admin.hints.testimonial') }}</p>
    </x-admin.index-header>
    <x-admin.table :headers="[__('admin.fields.author_name'), __('admin.fields.content'), __('admin.fields.rating'), __('admin.fields.status')]" :empty="$items->isEmpty()">
        @foreach ($items as $item)
            <tr>
                <td class="px-4 py-3"><span class="font-medium text-slate-900 dark:text-white">{{ $item->author_name }}</span><span class="block text-xs text-slate-500">{{ $item->company }}</span></td>
                <td class="max-w-md px-4 py-3 text-slate-600 dark:text-slate-400">{{ \Illuminate\Support\Str::limit($item->content_az, 90) }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-accent-500">{{ str_repeat('★', $item->rating) }}</td>
                <td class="px-4 py-3"><x-admin.active :value="$item->is_active" /></td>
                <td class="px-4 py-3"><x-admin.row-actions :edit="route('admin.testimonials.edit', $item)" :destroy="route('admin.testimonials.destroy', $item)" /></td>
            </tr>
        @endforeach
    </x-admin.table>
    <div class="mt-6">{{ $items->links() }}</div>
@endsection
