@props(['extensions'])
@if ($extensions->isEmpty())
    <p class="card p-8 text-center text-slate-600 dark:text-slate-400">{{ __('domains.prices.empty') }}</p>
@else
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[480px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 dark:bg-slate-800/60 dark:text-slate-400">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold">{{ __('domains.prices.extension') }}</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">{{ __('domains.prices.register') }}</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">{{ __('domains.prices.renew') }}</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">{{ __('domains.prices.transfer') }}</th>
                        <th scope="col" class="px-5 py-3.5"><span class="sr-only">{{ __('domains.order') }}</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach ($extensions as $ext)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40">
                            <th scope="row" class="px-5 py-4 text-base font-bold text-brand-950 dark:text-white">{{ $ext->label() }}</th>
                            <td class="px-5 py-4 font-semibold text-slate-900 dark:text-white">{{ money($ext->register_price) }}</td>
                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ money($ext->renew_price) }}</td>
                            <td class="px-5 py-4 text-slate-600 dark:text-slate-300">{{ $ext->transfer_price !== null ? money($ext->transfer_price) : '—' }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ lroute('domains') }}#search" class="font-semibold text-brand-700 hover:underline dark:text-brand-300">{{ __('domains.register') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">{{ __('domains.prices.lead', ['currency' => config('agency.currency')]) }}</p>
@endif
