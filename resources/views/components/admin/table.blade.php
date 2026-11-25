@props(['headers' => [], 'empty' => false])
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="whitespace-nowrap px-4 py-3 font-semibold">{{ $header }}</th>
                    @endforeach
                    <th scope="col" class="px-4 py-3 text-right"><span class="sr-only">{{ __('admin.labels.actions') }}</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if ($empty)
        <p class="p-8 text-center text-slate-500">{{ __('admin.empty') }}</p>
    @endif
</div>
