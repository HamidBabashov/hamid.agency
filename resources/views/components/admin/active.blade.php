@props(['value'])
<span @class(['inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold', 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300' => $value, 'bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-300' => ! $value])>
    {{ $value ? __('admin.labels.active') : __('admin.labels.inactive') }}
</span>
