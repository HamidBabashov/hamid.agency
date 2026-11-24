@extends('layouts.admin')
@section('title', __('admin.nav.services').' · '.($item->exists ? __('admin.headings.edit') : __('admin.headings.create')))

@section('content')
    <x-admin.form :item="$item" resource="services" files>
        <x-admin.panel>
            <div class="grid gap-5 md:grid-cols-4">
                <x-field name="key" :label="__('admin.fields.key')" :value="$item->key" required class="md:col-span-2" :hint="__('admin.hints.key')" />
                <x-field name="icon" type="select" :label="__('admin.fields.icon')" :value="$item->icon" :options="collect($icons)->mapWithKeys(fn ($i) => [$i => __('admin.icons.'.$i)])->all()" required />
                <x-field name="sort_order" type="number" min="0" :label="__('admin.fields.sort_order')" :value="$item->sort_order ?? 0" />
                <x-field name="slug_az" :label="__('admin.fields.slug_az')" :value="$item->slug_az" required class="md:col-span-2" :hint="__('admin.hints.slug')" />
                <x-field name="slug_en" :label="__('admin.fields.slug_en')" :value="$item->slug_en" required class="md:col-span-2" :hint="__('admin.hints.slug')" />
            </div>
            <x-admin.checkbox name="is_active" :label="__('admin.fields.is_active')" :checked="$item->is_active" />
        </x-admin.panel>

        <x-admin.panel :title="__('admin.headings.translations')">
            <x-admin.translatable name="title" :item="$item" required maxlength="255" />
            <x-admin.translatable name="excerpt" :item="$item" type="textarea" rows="2" maxlength="500" />
            <x-admin.translatable name="body" :item="$item" type="textarea" rows="8" :hint="__('admin.hints.body')" />
            <x-admin.translatable name="features" :item="$item" type="textarea" rows="6" :hint="__('admin.hints.features')" />
        </x-admin.panel>

        <x-admin.panel :title="__('admin.headings.seo')">
            <x-admin.translatable name="meta_title" :item="$item" maxlength="255" />
            <x-admin.translatable name="meta_description" :item="$item" type="textarea" rows="2" maxlength="300" :hint="__('admin.hints.meta')" />
        </x-admin.panel>

        <x-admin.panel>
            <x-admin.image name="image" :current="$item->image" />
        </x-admin.panel>
    </x-admin.form>
@endsection
