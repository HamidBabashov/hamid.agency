@props(['status'])
@php
    $styles = [
        'new' => 'bg-accent-100 text-accent-800 ring-accent-200 dark:bg-accent-500/15 dark:text-accent-300 dark:ring-accent-500/30',
        'contacted' => 'bg-sky-100 text-sky-800 ring-sky-200 dark:bg-sky-500/15 dark:text-sky-300 dark:ring-sky-500/30',
        'completed' => 'bg-green-100 text-green-800 ring-green-200 dark:bg-green-500/15 dark:text-green-300 dark:ring-green-500/30',
    ];
@endphp
<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset', $styles[$status] ?? '']) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ __('account.statuses.'.$status) }}
</span>
