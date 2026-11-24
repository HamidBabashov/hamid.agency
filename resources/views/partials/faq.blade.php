<section class="section">
    <div class="container max-w-3xl">
        <x-section-heading :title="$title" />
        <div class="mt-10 space-y-3">
            @foreach ($items as $item)
                <details class="card group p-5 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-900 dark:text-white">
                        {{ $item['q'] }}
                        <x-icon name="chevron-down" class="h-5 w-5 shrink-0 transition group-open:rotate-180" />
                    </summary>
                    <p class="mt-3 text-slate-600 dark:text-slate-400">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
@push('head')
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($items)->map(fn ($i) => ['@type' => 'Question', 'name' => $i['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $i['a']]])->all(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush
