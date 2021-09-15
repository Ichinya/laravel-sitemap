<?php

namespace Ichinya\LaravelSitemap;

use Ichinya\SitemapGenerator\Sitemap as SitemapCore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SitemapUrls
{
    /** @var SitemapCore */
    protected $sitemap;

    public function __construct($changefreq = 'weekly', $priority = 0.5)
    {
        $this->sitemap = new SitemapCore($changefreq, $priority);
    }

    public function addModelClass($class, $updated_at = 'updated_at')
    {
        $model = new $class;
        $routeName = Str::lower(basename($class));
        $this->addListLoop($model::all(), $routeName, $updated_at);
        return $this;
    }

    public function addModel($model, $routeName, $updated_at = 'updated_at')
    {
        $this->addListLoop($model, $routeName, $updated_at);
        return $this;
    }

    private function addListLoop($items, $routeName, $updated_at = 'updated_at')
    {
//        $this->sitemap->setGroup($routeName);
        foreach ($items as $item) {
            if ($updated_at instanceof Carbon) {
                $dt = $updated_at;
            } elseif (!isset($updated_at)) {
                $dt = Carbon::createFromFormat('Y-m-d H:i:s', $item->{$updated_at});
            } else {
                $dt = now();
            }
            $this->sitemap->addUrl(route($routeName, $item), $dt->timestamp);
        }
    }

    public function addUrl($loc, $lastmod = null, $changefreq = null, $priority = null)
    {
        $this->sitemap->addUrl($loc, Carbon::parse($lastmod)->timestamp, $changefreq, $priority);
        return $this;
    }

    public function getCore()
    {
        return $this->sitemap;
    }

    public function setGroup($name)
    {
        $this->sitemap->setGroup($name);
        return $this;
    }

    public function getList()
    {
        return $this->sitemap->getList();
    }public function getListGroup()
    {
        return $this->sitemap->getListGroup();
    }
}
