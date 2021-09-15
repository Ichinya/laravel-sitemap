<?php

namespace Ichinya\LaravelSitemap;

use Ichinya\SitemapGenerator\Sitemap as SitemapCore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Sitemap
{

    /** @var SitemapUrls */
    private $sitemapUrls;

    private function __construct(SitemapUrls $sitemapUrls)
    {
        $this->sitemapUrls = $sitemapUrls;
    }

    public static function create($sitemapUrls)
    {
        return new Sitemap($sitemapUrls);
    }

    public function render()
    {
        $list = $this->sitemapUrls->getList();
        $listGroup = $this->sitemapUrls->getListGroup();
        if (!empty($listGroup)) {
            dd($listGroup);
            $listGroup['other'] = $list;
            return view('ichi-sitemap::sitemapIndex.index', ['sitemaps' => $listGroup]);
        }
        return view('ichi-sitemap::sitemap', ['urls' => $list]);
    }

}
