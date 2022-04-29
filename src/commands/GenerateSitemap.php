<?php

namespace Ichinya\LaravelSitemap\commands;

use Ichinya\LaravelSitemap\Sitemap;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap';

    public function handle()
    {
        dispatch(function () {
            Sitemap::generate()
                ->writeToFile(public_path('sitemap.xml'));
        })->onQueue('low');
    }
}
