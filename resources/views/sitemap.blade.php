{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach(($urls ?? []) as $url)
    @php
        $loc = data_get($url, 'loc', url('/'));
        $lastmodRaw = data_get($url, 'lastmod', now());

        try {
            $lastmod = $lastmodRaw instanceof \DateTimeInterface
                ? \Illuminate\Support\Carbon::instance($lastmodRaw)->toAtomString()
                : \Illuminate\Support\Carbon::parse($lastmodRaw)->toAtomString();
        } catch (\Throwable $e) {
            $lastmod = now()->toAtomString();
        }
    @endphp
    <url>
        <loc>{{ $loc }}</loc>
        <lastmod>{{ $lastmod }}</lastmod>
    </url>
@endforeach
</urlset>
