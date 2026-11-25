@if ($errors->any())
    <x-alert type="error" class="mb-6">
        <p class="font-semibold">{{ __('site.form.errors_title') }}</p>
        <ul class="mt-1 list-disc space-y-0.5 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
