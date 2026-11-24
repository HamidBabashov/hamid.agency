{{-- Yaratma/redaktə formasının ümumi çərçivəsi --}}
@props(['item', 'resource', 'files' => false])
@php $exists = $item->exists; @endphp
<form method="POST" action="{{ $exists ? route("admin.{$resource}.update", $item) : route("admin.{$resource}.store") }}"
      @if ($files) enctype="multipart/form-data" @endif class="space-y-6">
    @csrf
    @if ($exists) @method('PUT') @endif
    {{ $slot }}
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route("admin.{$resource}.index") }}" class="btn-outline">{{ __('admin.actions.cancel') }}</a>
        <button type="submit" class="btn-primary px-8">{{ __('admin.actions.save') }}</button>
    </div>
</form>
