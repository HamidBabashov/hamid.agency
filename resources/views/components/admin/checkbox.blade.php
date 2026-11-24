@props(['name', 'label', 'checked' => false, 'hint' => null])
<div>
    <input type="hidden" name="{{ $name }}" value="0">
    <label class="inline-flex items-center gap-2.5 text-sm font-medium text-slate-700 dark:text-slate-300">
        <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked)) class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
        {{ $label }}
    </label>
    @if ($hint)<p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $hint }}</p>@endif
</div>
