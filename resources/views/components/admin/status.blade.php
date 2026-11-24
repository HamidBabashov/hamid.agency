@props(['status'])
@php
    $styles = [
        'new' => 'bg-accent-100 text-accent-700 dark:bg-accent-500/20 dark:text-accent-300',
        'contacted' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300',
        'completed' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300',
    ];
@endphp
<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $styles[$status] ?? 'bg-slate-100 text-slate-700' }}">{{ __('admin.statuses.'.$status) }}</span>
