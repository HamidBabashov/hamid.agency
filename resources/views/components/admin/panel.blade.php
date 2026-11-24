@props(['title' => null])
<section {{ $attributes->class('card p-6') }}>
    @if ($title)
        <h2 class="mb-5 text-base font-semibold text-slate-900 dark:text-white">{{ $title }}</h2>
    @endif
    <div class="space-y-5">{{ $slot }}</div>
</section>
