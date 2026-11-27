@props(['eyebrow' => null, 'title', 'lead' => null, 'center' => true])
<div {{ $attributes->class(['max-w-2xl', 'mx-auto text-center' => $center]) }} data-reveal>
    @if ($eyebrow)
        <p class="eyebrow">{{ $eyebrow }}</p>
    @endif
    <h2 class="section-title {{ $eyebrow ? 'mt-4' : '' }}">{{ $title }}</h2>
    @if ($lead)
        <p class="section-lead">{{ $lead }}</p>
    @endif
</div>
