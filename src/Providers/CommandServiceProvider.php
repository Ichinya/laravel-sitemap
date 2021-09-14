<?php

namespace Ichinya\LaravelSitemap\Providers;

use Ichinya\LaravelSitemap\Commands\GenerateSitemap;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            GenerateSitemap::class,
        ]);

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            /*
             * evita que todos os crons do servidor compartilhado
             * rodem exatamente na mesma hora
             * */
            $minutes = cache()->rememberForever('schedule-minutes', function () {
                return str_pad(rand(0, 59), 2, 0, STR_PAD_LEFT);
            });

            $schedule->command('sitemap:generate')
                ->withoutOverlapping()
                ->dailyAt("04:{$minutes}")
                ->appendOutputTo(storage_path('logs/command-sitemap-generate-' . date('Y-m-d') . '.log'));

        });
    }
}
