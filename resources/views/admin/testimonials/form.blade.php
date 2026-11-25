@extends('layouts.admin')
@section('title', __('admin.nav.testimonials').' · '.($item->exists ? __('admin.headings.edit') : __('admin.headings.create')))

@section('content')
    <x-admin.form :item="$item" resource="testimonials" files>
        <x-admin.panel>
            <p class="text-sm text-slate-500">{{ __('admin.hints.testimonial') }}</p>
            <div class="grid gap-5 md:grid-cols-4">
                <x-field name="author_name" :label="__('admin.fields.author_name')" :value="$item->author_name" required maxlength="100" class="md:col-span-2" />
                <x-field name="company" :label="__('admin.fields.company')" :value="$item->company" maxlength="150" class="md:col-span-2" />
                <x-field name="rating" type="select" :label="__('admin.fields.rating')" :value="$item->rating" :options="[5 => '★★★★★', 4 => '★★★★', 3 => '★★★', 2 => '★★', 1 => '★']" required />
                <x-field name="sort_order" type="number" min="0" :label="__('admin.fields.sort_order')" :value="$item->sort_order ?? 0" />
            </div>
            <x-admin.checkbox name="is_active" :label="__('admin.fields.is_active')" :checked="$item->is_active" />
        </x-admin.panel>
        <x-admin.panel :title="__('admin.headings.translations')">
            <x-admin.translatable name="position" :item="$item" maxlength="150" />
            <x-admin.translatable name="content" :item="$item" type="textarea" rows="5" required maxlength="2000" />
        </x-admin.panel>
        <x-admin.panel>
            <x-admin.image name="photo" :current="$item->photo" />
        </x-admin.panel>
    </x-admin.form>
@endsection
