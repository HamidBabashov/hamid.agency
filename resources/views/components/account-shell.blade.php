@props(['title', 'lead' => null])
@php
    $tabs = [
        ['route' => 'account', 'match' => ['account', 'account.orders.show'], 'icon' => 'cart', 'label' => __('account.nav.orders')],
        ['route' => 'account.profile', 'match' => ['account.profile'], 'icon' => 'user', 'label' => __('account.nav.profile')],
    ];
    $current = current_route_base();
@endphp
<x-page-header :title="$title" :lead="$lead" compact>
    <div class="mt-8 flex flex-wrap items-center gap-2">
        @foreach ($tabs as $tab)
            <a href="{{ lroute($tab['route']) }}" @class([
                'inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold transition',
                'bg-white text-slate-900' => in_array($current, $tab['match'], true),
                'border border-white/15 bg-white/5 text-slate-200 hover:bg-white/10' => ! in_array($current, $tab['match'], true),
            ])><x-icon :name="$tab['icon']" class="h-4 w-4" />{{ $tab['label'] }}</a>
        @endforeach
        <a href="{{ lroute('quote') }}" class="btn-accent ml-auto"><x-icon name="plus" class="h-4 w-4" />{{ __('account.dashboard.new_order') }}</a>
    </div>
</x-page-header>

<section class="bg-slate-50 py-12 sm:py-16 dark:bg-transparent">
    <div class="container">
        @if (session('success'))
            <x-alert class="mb-8">{{ session('success') }}</x-alert>
        @endif
        {{ $slot }}
    </div>
</section>
