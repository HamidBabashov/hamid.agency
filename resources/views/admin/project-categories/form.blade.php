@extends('layouts.admin')
@section('title', __('admin.nav.project_categories').' · '.($item->exists ? __('admin.headings.edit') : __('admin.headings.create')))

@section('content')
    <x-admin.form :item="$item" resource="project-categories">
        <x-admin.panel>
            <x-admin.translatable name="name" :item="$item" required maxlength="100" />
            <div class="grid gap-5 md:grid-cols-2">
                <x-field name="slug" :label="__('admin.fields.slug')" :value="$item->slug" maxlength="100" :hint="__('admin.hints.slug')" />
                <x-field name="sort_order" type="number" min="0" :label="__('admin.fields.sort_order')" :value="$item->sort_order ?? 0" />
            </div>
        </x-admin.panel>
    </x-admin.form>
@endsection
