@php
    $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
@endphp
{!!  $xml !!}
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    @foreach($sitemaps as $sitemap)
        @yield('sitemap')
    @endforeach
</sitemapindex>
