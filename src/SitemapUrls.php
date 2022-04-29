<?php

namespace Ichinya\LaravelSitemap;

use Ichinya\LaravelSitemap\Contract\SitemapableAbstract;
use Ichinya\SitemapGenerator\Sitemap as SitemapCore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SitemapUrls
{
    /** @var SitemapCore */
    protected SitemapCore $sitemap;

    public function __construct($changefreq = 'weekly', $priority = 0.5)
    {
        $this->sitemap = new SitemapCore($changefreq, $priority);
    }

    public function addModelClass($class, $updated_at = null)
    {
        /** @var SitemapableAbstract $model */
        $model = new $class;
        if (method_exists($model, 'getSitemapDatetime')) {
            $updated_at = $model->getSitemapDatetime();
        } elseif ($model->sitemap_datetime) {
            $updated_at = $model->sitemap_datetime;
        } else {
            $updated_at = $updated_at ?? 'updated_at';
        }

        if (method_exists($model, 'getSitemapModelRouteList')) {
            $routeName = $model->getSitemapModelRouteList();
        } elseif ($model->sitemap_model_route_list) {
            $routeName = $model->sitemap_model_route_list;
        } else {
            $routeName = Str::plural(Str::lower(basename($class))) . '.index';
        }

        if (Route::has($routeName)) {
            $this->addListItem(route($routeName), null, $updated_at, 'daily');
        }

        if (method_exists($model, 'getSitemapModelRouteItem')) {
            $routeName = $model->getSitemapModelRouteItem();
        } elseif ($model->sitemap_model_route_item) {
            $routeName = $model->sitemap_model_route_item;
        } else {
            $routeName = Str::plural(Str::lower(basename($class))) . '.show';
        }

        if (method_exists($model, 'getSitemapItems')) {
            $items = $model->getSitemapItems();
        } else {
            $items = $model::whereNotNull($updated_at)->get();
        }

        if (Route::has($routeName)) {
            $this->addListLoop($items, $routeName, $updated_at);
        }

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
            $this->addListItem(route($routeName, $item), $routeName, $updated_at);
        }
    }

    private function addListItem($loc, $item, $updated_at, $changefreq = null, $priority = null)
    {
        if ($updated_at instanceof Carbon) {
            $dt = $updated_at;
        } elseif (!isset($updated_at)) {
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $item->{$updated_at});
        } else {
            $dt = now();
        }
        $this->sitemap->addUrl($loc, $dt->timestamp, $changefreq, $priority);
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
    }

    public function getListGroup()
    {
        return $this->sitemap->getListGroup();
    }
}
