<?php

namespace Ichinya\LaravelSitemap\Contract;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Model
 */
class SitemapableAbstract
{
    public string $sitemap_datetime = 'updated_at';

    public ?string $sitemap_model_route_list = null;
    public ?string $sitemap_model_route_item = null;

    public function getSitemapDatetime(): string
    {
        return $this->sitemap_datetime;
    }

    public function getSitemapModelRouteItem(): string
    {
        return $this->sitemap_model_route_item;
    }


    public function getSitemapModelRouteList(): string
    {
        return $this->sitemap_model_route_list;
    }

    public function getSitemapItems()
    {
        return self::whereNotNull($this->getSitemapDatetime())->get();
    }
}
