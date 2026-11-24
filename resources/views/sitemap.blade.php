{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($entries as $urls)
@foreach ($urls as $locale => $url)
    <url>
        <loc>{{ $url }}</loc>
@foreach ($urls as $altLocale => $altUrl)
        <xhtml:link rel="alternate" hreflang="{{ $codes[$altLocale]['hreflang'] }}" href="{{ $altUrl }}"/>
@endforeach
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ $urls[config('agency.default_locale')] }}"/>
        <changefreq>weekly</changefreq>
        <priority>{{ $loop->parent->first ? '1.0' : '0.8' }}</priority>
    </url>
@endforeach
@endforeach
</urlset>
