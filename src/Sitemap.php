<?php

namespace Ichinya\LaravelSitemap;

class Sitemap
{
    private $list = [];



    public function addUrl($loc, $lastmod = null, $changefreq = null, $priority = null)
    {
        $url = compact('loc', 'lastmod', 'changefreq', 'priority');
        $list[] = $url;
    }

}