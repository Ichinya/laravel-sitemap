# Генератор для sitemap для Laravel

прописать в config.app

Next, we need to add our new Service Provider in our config/app.php inside of the `providers[]` array:

```php 
'providers' => [
    /*
    * Laravel Framework Service Providers…
    */
    Illuminate\Auth\AuthServiceProvider::class,
    //.. Other providers
    
    Ichinya\LaravelSitemap\Providers\SitemapServiceProvider::class,
    
    ],
```
```
php artisan vendor:publish --provider=Ichinya\LaravelSitemap\Providers\SitemapServiceProvider
```
