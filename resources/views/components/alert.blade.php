@props(['type' => 'success'])
@php
    $styles = [
        'success' => 'border-green-200 bg-green-50 text-green-800 dark:border-green-900 dark:bg-green-950/50 dark:text-green-300',
        'error' => 'border-red-200 bg-red-50 text-red-800 dark:border-red-900 dark:bg-red-950/50 dark:text-red-300',
    ];
@endphp
<div role="{{ $type === 'error' ? 'alert' : 'status' }}" {{ $attributes->class(['flex gap-3 rounded-xl border p-4 text-sm', $styles[$type]]) }}>
    <x-icon :name="$type === 'error' ? 'x-circle' : 'check-circle'" class="h-5 w-5 shrink-0" />
    <div>{{ $slot }}</div>
</div>
