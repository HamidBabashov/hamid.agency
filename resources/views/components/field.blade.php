@props(['name', 'label', 'type' => 'text', 'required' => false, 'placeholder' => null, 'value' => null, 'options' => null, 'rows' => 5, 'hint' => null, 'id' => null])
@php
    $id = $id ?: 'f-'.str_replace(['[', ']', '.'], '-', $name);
    $current = old($name, $value);
    $hasError = $errors->has($name);
@endphp
<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="label">
        {{ $label }}@if ($required)<span class="text-red-500"> *</span>@endif
    </label>
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" @required($required) placeholder="{{ $placeholder }}"
                  @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                  {{ $attributes->except('class')->class(['input', 'border-red-500' => $hasError]) }}>{{ $current }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" @required($required)
                @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
                {{ $attributes->except('class')->class(['input', 'border-red-500' => $hasError]) }}>
            <option value="">{{ __('site.form.select') }}</option>
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $current }}" @required($required) placeholder="{{ $placeholder }}"
               @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
               {{ $attributes->except('class')->class(['input', 'border-red-500' => $hasError]) }}>
    @endif
    @if ($hint)
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $hint }}</p>
    @endif
    @error($name)
        <p id="{{ $id }}-error" class="field-error">{{ $message }}</p>
    @enderror
</div>
