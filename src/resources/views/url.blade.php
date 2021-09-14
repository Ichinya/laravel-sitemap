<url>
    @if (! empty($url['loc']))
        <loc>{{ $url['loc'] }}</loc>
        @if (! empty($url['lastmod']))
            <lastmod>{{ $url['lastmod'] }}</lastmod>
        @endif
        @if (! empty($url['changefreq']))
            <changefreq>{{ $url['changefreq'] }}</changefreq>
        @endif
        @if (! empty($url['priority']))
            <priority>{{ $url['priority'] }}</priority>
        @endif
    @endif
</url>
