@props(['name' => 'image', 'current' => null, 'label' => null])
<div>
    <label for="f-{{ $name }}" class="label">{{ $label ?? __('admin.fields.'.$name) }}</label>
    @if ($current)
        <div class="mb-3 flex items-center gap-4">
            <img src="{{ upload_url($current) }}" alt="{{ __('admin.labels.current_image') }}" class="h-20 w-32 rounded-lg object-cover">
            <label class="inline-flex items-center gap-2 text-sm text-red-600"><input type="checkbox" name="remove_{{ $name }}" value="1" class="rounded border-slate-300 text-red-600"> {{ __('admin.actions.remove_image') }}</label>
        </div>
    @endif
    <input id="f-{{ $name }}" type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/webp,image/gif"
           class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100 dark:text-slate-400 dark:file:bg-slate-800 dark:file:text-slate-200">
    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('admin.hints.image') }}</p>
    @error($name)<p class="field-error">{{ $message }}</p>@enderror
</div>
