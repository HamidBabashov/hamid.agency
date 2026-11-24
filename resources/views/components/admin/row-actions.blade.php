@props(['edit' => null, 'show' => null, 'destroy' => null])
<div class="flex items-center justify-end gap-1">
    @if ($show)
        <a href="{{ $show }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-brand-700 dark:hover:bg-slate-800" title="{{ __('admin.actions.view') }}"><x-icon name="arrow-right" class="h-4 w-4" /></a>
    @endif
    @if ($edit)
        <a href="{{ $edit }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-brand-700 dark:hover:bg-slate-800" title="{{ __('admin.actions.edit') }}"><x-icon name="pencil" class="h-4 w-4" /></a>
    @endif
    @if ($destroy)
        <form method="POST" action="{{ $destroy }}" onsubmit="return confirm(@js(__('admin.actions.confirm_delete')))">
            @csrf @method('DELETE')
            <button class="rounded-lg p-2 text-slate-500 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/40" title="{{ __('admin.actions.delete') }}"><x-icon name="trash" class="h-4 w-4" /></button>
        </form>
    @endif
</div>
