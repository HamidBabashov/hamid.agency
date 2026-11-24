@props(['create' => null])
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>{{ $slot }}</div>
    @if ($create)
        <a href="{{ $create }}" class="btn-primary"><x-icon name="plus" class="h-4 w-4" /> {{ __('admin.actions.create') }}</a>
    @endif
</div>
