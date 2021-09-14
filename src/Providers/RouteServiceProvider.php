<?php

namespace Ichinya\LaravelSitemap\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureRateLimiting();

        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, __DIR__ . '/../route/web.php');

        $this->routes(function () use ($path) {
            Route::middleware('web')
                ->group($path);
        });
    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60);
        });
    }
}
