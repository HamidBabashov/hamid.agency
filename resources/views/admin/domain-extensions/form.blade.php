@extends('layouts.admin')
@section('title', __('admin.nav.domain_extensions').' · '.($item->exists ? __('admin.headings.edit') : __('admin.headings.create')))

@section('content')
    <x-admin.form :item="$item" resource="domain-extensions">
        <x-admin.panel>
            <div class="grid gap-5 md:grid-cols-3">
                <x-field name="extension" :label="__('admin.fields.extension')" :value="$item->extension" required maxlength="30" :hint="__('admin.hints.extension')" />
                <x-field name="sort_order" type="number" min="0" :label="__('admin.fields.sort_order')" :value="$item->sort_order ?? 0" />
                <div></div>
                <x-field name="register_price" type="number" step="0.01" min="0" :label="__('admin.fields.register_price').' ('.config('agency.currency').')'" :value="$item->register_price" required />
                <x-field name="renew_price" type="number" step="0.01" min="0" :label="__('admin.fields.renew_price').' ('.config('agency.currency').')'" :value="$item->renew_price" required />
                <x-field name="transfer_price" type="number" step="0.01" min="0" :label="__('admin.fields.transfer_price').' ('.config('agency.currency').')'" :value="$item->transfer_price" />
            </div>
            <div class="flex flex-wrap gap-8">
                <x-admin.checkbox name="is_active" :label="__('admin.fields.is_active')" :checked="$item->is_active" />
                <x-admin.checkbox name="is_featured" :label="__('admin.fields.is_featured')" :checked="$item->is_featured" :hint="__('admin.hints.extension_featured')" />
            </div>
        </x-admin.panel>
    </x-admin.form>
@endsection
