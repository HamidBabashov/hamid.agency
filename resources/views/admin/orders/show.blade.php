@extends('layouts.admin')
@section('title', __('admin.headings.order', ['number' => $item->id]))

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="mb-6 inline-block text-sm font-medium text-brand-700 hover:underline dark:text-brand-300">← {{ __('admin.actions.back') }}</a>
    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <x-admin.panel :title="__('admin.headings.order_details')">
                <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.service_type') }}</dt><dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ __('site.service_types.'.$item->service_type) }}</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.created_at') }}</dt><dd class="mt-1">{{ $item->created_at->format('d.m.Y H:i') }}</dd></div>
                    @foreach ($item->detailRows() as $row)
                        <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ $row['label'] }}</dt><dd class="mt-1 font-medium text-slate-900 dark:text-white">{{ $row['value'] }}</dd></div>
                    @endforeach
                </dl>
                @if ($item->message)
                    <div><p class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.message') }}</p><p class="mt-1 whitespace-pre-line rounded-lg bg-slate-50 p-4 dark:bg-slate-800/60">{{ $item->message }}</p></div>
                @endif
            </x-admin.panel>

            <x-admin.panel :title="__('admin.headings.contact_info')">
                <dl class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.name') }}</dt><dd class="mt-1 font-medium">{{ $item->name }}</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.company') }}</dt><dd class="mt-1">{{ $item->company ?: __('admin.labels.none') }}</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.email') }}</dt><dd class="mt-1"><a href="mailto:{{ $item->email }}" class="text-brand-700 hover:underline dark:text-brand-300">{{ $item->email }}</a></dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.phone') }}</dt><dd class="mt-1"><a href="tel:{{ preg_replace('/[^\d+]/', '', (string) $item->phone) }}" class="text-brand-700 hover:underline dark:text-brand-300">{{ $item->phone }}</a></dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.locale') }}</dt><dd class="mt-1">{{ strtoupper($item->locale) }}</dd></div>
                    <div><dt class="text-xs uppercase tracking-wider text-slate-500">{{ __('admin.fields.ip_address') }}</dt><dd class="mt-1 font-mono text-xs">{{ $item->ip_address }}</dd></div>
                </dl>
                <div class="flex flex-wrap gap-3">
                    <a href="mailto:{{ $item->email }}" class="btn-outline"><x-icon name="mail" class="h-4 w-4" />{{ __('admin.actions.reply_email') }}</a>
                    @if ($item->phone)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $item->phone) }}" target="_blank" rel="noopener" class="btn-outline"><x-icon name="whatsapp" class="h-4 w-4" />{{ __('admin.actions.whatsapp') }}</a>
                    @endif
                </div>
            </x-admin.panel>
        </div>

        <div class="space-y-6">
            <x-admin.panel :title="__('admin.headings.status_update')">
                <form method="POST" action="{{ route('admin.orders.update', $item) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <x-field name="status" type="select" :label="__('admin.fields.status')" :value="$item->status" required
                             :options="collect(\App\Models\Order::STATUSES)->mapWithKeys(fn ($s) => [$s => __('admin.statuses.'.$s)])->all()" />
                    <x-field name="admin_note" type="textarea" rows="4" :label="__('admin.fields.admin_note')" :value="$item->admin_note" maxlength="5000" />
                    <button class="btn-primary w-full">{{ __('admin.actions.save') }}</button>
                </form>
            </x-admin.panel>
            <form method="POST" action="{{ route('admin.orders.destroy', $item) }}" onsubmit="return confirm(@js(__('admin.actions.confirm_delete')))">
                @csrf @method('DELETE')
                <button class="btn-danger w-full"><x-icon name="trash" class="h-4 w-4" />{{ __('admin.actions.delete') }}</button>
            </form>
        </div>
    </div>
@endsection
