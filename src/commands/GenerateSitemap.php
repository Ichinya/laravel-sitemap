<?php

namespace Ichinya\LaravelSitemap\commands;

use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap';

    public function handle()
    {
        dispatch(function () {
            SitemapGenerator::create(config('app.url'))
                ->writeToFile(public_path('sitemap.xml'));
        })->onQueue('low');
    }
}
