@extends('layouts.admin')
@section('title', __('admin.nav.hosting_plans').' · '.($item->exists ? __('admin.headings.edit') : __('admin.headings.create')))

@section('content')
    <x-admin.form :item="$item" resource="hosting-plans">
        <x-admin.panel>
            <div class="grid gap-5 md:grid-cols-3">
                <x-field name="price_monthly" type="number" step="0.01" min="0" :label="__('admin.fields.price_monthly').' ('.config('agency.currency').')'" :value="$item->price_monthly" required />
                <x-field name="price_yearly" type="number" step="0.01" min="0" :label="__('admin.fields.price_yearly').' ('.config('agency.currency').')'" :value="$item->price_yearly" required :hint="__('admin.hints.price_yearly')" />
                <x-field name="sort_order" type="number" min="0" :label="__('admin.fields.sort_order')" :value="$item->sort_order ?? 0" />
            </div>
            <div class="flex flex-wrap gap-8">
                <x-admin.checkbox name="is_active" :label="__('admin.fields.is_active')" :checked="$item->is_active" />
                <x-admin.checkbox name="is_featured" :label="__('admin.fields.is_featured')" :checked="$item->is_featured" :hint="__('admin.hints.featured_plan')" />
            </div>
        </x-admin.panel>
        <x-admin.panel :title="__('admin.headings.translations')">
            <x-admin.translatable name="name" :item="$item" required maxlength="100" />
            <x-admin.translatable name="description" :item="$item" type="textarea" rows="2" maxlength="500" />
            <x-admin.translatable name="features" :item="$item" type="textarea" rows="7" :hint="__('admin.hints.features')" />
        </x-admin.panel>
    </x-admin.form>
@endsection
