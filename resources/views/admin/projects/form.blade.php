@extends('layouts.admin')
@section('title', __('admin.nav.projects').' · '.($item->exists ? __('admin.headings.edit') : __('admin.headings.create')))

@section('content')
    <x-admin.form :item="$item" resource="projects" files>
        <x-admin.panel :title="__('admin.headings.translations')">
            <x-admin.translatable name="title" :item="$item" required maxlength="255" />
            <x-admin.translatable name="summary" :item="$item" type="textarea" rows="3" maxlength="500" />
        </x-admin.panel>
        <x-admin.panel>
            <div class="grid gap-5 md:grid-cols-2">
                <x-field name="project_category_id" type="select" :label="__('admin.fields.project_category_id')" :value="$item->project_category_id" :options="$categories->pluck('name_az', 'id')->all()" />
                <x-field name="client" :label="__('admin.fields.client')" :value="$item->client" maxlength="150" />
                <x-field name="url" type="url" :label="__('admin.fields.url')" :value="$item->url" maxlength="255" placeholder="https://" />
                <div class="grid grid-cols-2 gap-5">
                    <x-field name="year" type="number" min="2000" max="2100" :label="__('admin.fields.year')" :value="$item->year" />
                    <x-field name="sort_order" type="number" min="0" :label="__('admin.fields.sort_order')" :value="$item->sort_order ?? 0" />
                </div>
            </div>
            <div class="flex flex-wrap gap-8">
                <x-admin.checkbox name="is_active" :label="__('admin.fields.is_active')" :checked="$item->is_active" />
                <x-admin.checkbox name="is_featured" :label="__('admin.fields.is_featured')" :checked="$item->is_featured" :hint="__('admin.hints.featured_project')" />
            </div>
            <x-admin.image name="image" :current="$item->image" />
        </x-admin.panel>
    </x-admin.form>
@endsection
