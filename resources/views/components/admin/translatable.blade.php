{{-- AZ və EN sahələri yan-yana --}}
@props(['name', 'item', 'type' => 'text', 'rows' => 4, 'required' => false, 'hint' => null, 'maxlength' => null])
<div class="grid gap-4 md:grid-cols-2">
    @foreach (config('agency.locales') as $locale)
        @php $field = "{$name}_{$locale}"; @endphp
        <div>
            <label for="f-{{ $field }}" class="label flex items-center gap-2">
                <span class="rounded bg-slate-200 px-1.5 py-0.5 text-[10px] font-bold uppercase text-slate-700 dark:bg-slate-700 dark:text-slate-200">{{ $locale }}</span>
                {{ __('admin.fields.'.$name) }}@if ($required)<span class="text-red-500">*</span>@endif
            </label>
            @if ($type === 'textarea')
                <textarea id="f-{{ $field }}" name="{{ $field }}" rows="{{ $rows }}" @required($required) @if ($maxlength) maxlength="{{ $maxlength }}" @endif
                          @class(['input', 'border-red-500' => $errors->has($field)])>{{ old($field, $item->{$field}) }}</textarea>
            @else
                <input id="f-{{ $field }}" type="text" name="{{ $field }}" value="{{ old($field, $item->{$field}) }}" @required($required) @if ($maxlength) maxlength="{{ $maxlength }}" @endif
                       @class(['input', 'border-red-500' => $errors->has($field)])>
            @endif
            @error($field)<p class="field-error">{{ $message }}</p>@enderror
        </div>
    @endforeach
    @if ($hint)
        <p class="-mt-2 text-xs text-slate-500 md:col-span-2 dark:text-slate-400">{{ $hint }}</p>
    @endif
</div>
