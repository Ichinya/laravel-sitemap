@php
    $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
@endphp
{!!  $xml !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    @foreach($urls as $url)
        @include('url')
    @endforeach
</urlset>
