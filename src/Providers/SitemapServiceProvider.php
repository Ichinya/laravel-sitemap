<?php

namespace Ichinya\LaravelSitemap\Providers;

use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->providers();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ichi-sitemap');
        $this->mergeConfigFrom(
            __DIR__.'/../config/sitemap.php', 'sitemap'
        );
        $this->publishes([
            __DIR__.'/../config/sitemap.php' => config_path('sitemap.php'),
        ]);
    }

    public function register()
    {
      //
    }

    protected function providers()
    {
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

}
