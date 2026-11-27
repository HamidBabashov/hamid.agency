@props(['plans'])
@if ($plans->isEmpty())
    <p class="card mx-auto max-w-xl p-8 text-center text-slate-600 dark:text-slate-400">{{ __('hosting.empty') }}</p>
@else
    @php $maxSaving = $plans->max(fn ($p) => $p->yearlySavingPercent()); @endphp
    <div x-data="pricing">
        {{-- Aylıq / illik keçid --}}
        <div class="flex justify-center">
            <div class="inline-flex items-center rounded-full border border-slate-200 bg-slate-100 p-1 dark:border-white/10 dark:bg-white/5" role="radiogroup" aria-label="{{ __('hosting.billing_toggle') }}">
                <button type="button" role="radio" :aria-checked="!yearly" @click="yearly = false"
                        :class="!yearly ? 'bg-white text-brand-950 shadow dark:bg-slate-950 dark:text-white' : 'text-slate-600 dark:text-slate-400'"
                        class="rounded-full px-5 py-2 text-sm font-semibold transition">{{ __('hosting.monthly') }}</button>
                <button type="button" role="radio" :aria-checked="yearly" @click="yearly = true"
                        :class="yearly ? 'bg-white text-brand-950 shadow dark:bg-slate-950 dark:text-white' : 'text-slate-600 dark:text-slate-400'"
                        class="flex items-center gap-2 rounded-full px-5 py-2 text-sm font-semibold transition">
                    {{ __('hosting.yearly') }}
                    @if ($maxSaving > 0)
                        <span class="rounded-full bg-accent-100 px-2 py-0.5 text-xs text-accent-700 dark:bg-accent-500/20 dark:text-accent-300">{{ __('hosting.save', ['percent' => $maxSaving]) }}</span>
                    @endif
                </button>
            </div>
        </div>

        @php $cols = [1 => 'lg:grid-cols-1 max-w-md mx-auto', 2 => 'lg:grid-cols-2 max-w-4xl mx-auto', 3 => 'lg:grid-cols-3', 4 => 'lg:grid-cols-4'][min(4, $plans->count())]; @endphp
        <div class="mt-10 grid gap-6 md:grid-cols-2 {{ $cols }}">
            @foreach ($plans as $plan)
                <div @class([
                    'relative flex flex-col rounded-3xl p-8 transition duration-300 hover:-translate-y-1',
                    'card' => ! $plan->is_featured,
                    'bg-ink text-white shadow-glow ring-1 ring-brand-500/50 lg:-my-4 lg:py-12' => $plan->is_featured,
                ]) data-reveal>
                    @if ($plan->is_featured)
                        <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-accent-300 to-accent-500 px-4 py-1 text-xs font-bold uppercase tracking-wide text-brand-950 shadow-lg shadow-accent-500/30">{{ __('hosting.popular') }}</span>
                    @endif
                    <h3 @class(['text-xl font-bold', 'text-slate-900 dark:text-white' => ! $plan->is_featured])>{{ $plan->t('name') }}</h3>
                    <p @class(['mt-2 min-h-[2.5rem] text-sm', 'text-slate-600 dark:text-slate-400' => ! $plan->is_featured, 'text-slate-300' => $plan->is_featured])>{{ $plan->t('description') }}</p>

                    <div class="mt-6">
                        <p class="flex items-baseline gap-1">
                            <span @class(['text-5xl font-extrabold tracking-tight', 'text-slate-900 dark:text-white' => ! $plan->is_featured])
                                  x-text="yearly ? @js(money($plan->yearlyPerMonth())) : @js(money($plan->price_monthly))">{{ money($plan->price_monthly) }}</span>
                            <span class="opacity-60">{{ __('site.per_month') }}</span>
                        </p>
                        <p class="mt-2 h-5 text-sm opacity-70">
                            <span x-show="!yearly">{{ __('hosting.billed_monthly') }}</span>
                            <span x-cloak x-show="yearly">
                                {{ __('hosting.billed_yearly', ['amount' => money($plan->price_yearly)]) }}
                                @if ($plan->yearlySavingPercent() > 0)
                                    · <span class="font-semibold text-green-600 dark:text-green-400">{{ __('hosting.save', ['percent' => $plan->yearlySavingPercent()]) }}</span>
                                @endif
                            </span>
                        </p>
                    </div>

                    <ul @class(['mt-8 flex-1 space-y-3.5 border-t pt-8 text-sm', 'border-slate-200 dark:border-white/10' => ! $plan->is_featured, 'border-white/10 text-slate-200' => $plan->is_featured])>
                        @foreach ($plan->lines('features') as $feature)
                            <li class="flex gap-2.5"><x-icon name="check" class="h-5 w-5 shrink-0 text-green-600 dark:text-green-400" /><span>{{ $feature }}</span></li>
                        @endforeach
                    </ul>

                    @php $orderUrl = lroute('quote', ['service' => 'hosting', 'plan' => $plan->id]); @endphp
                    <a :href="@js($orderUrl) + '&billing=' + (yearly ? 'yearly' : 'monthly')" href="{{ $orderUrl }}"
                       class="{{ $plan->is_featured ? 'btn-accent' : 'btn-outline' }} mt-10 w-full py-3.5">{{ __('hosting.choose') }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endif
