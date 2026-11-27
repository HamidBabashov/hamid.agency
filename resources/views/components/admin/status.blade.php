@props(['status'])
@php
    $styles = [
        'new' => 'bg-accent-100 text-accent-700 dark:bg-accent-500/20 dark:text-accent-300',
        'contacted' => 'bg-violet-100 text-violet-700 dark:bg-violet-500/20 dark:text-violet-300',
        'completed' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300',
    ];
@endphp
<span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $styles[$status] ?? 'bg-slate-100 text-slate-700' }}">{{ __('admin.statuses.'.$status) }}</span>
