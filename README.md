# Генератор для sitemap для Laravel

[comment]: <> (прописать в config.app)

## Установка

Регистрируем в  `config/app.php` в разделе `providers[]`. Примерно так:

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

[comment]: <> (php artisan vendor:publish --provider=Ichinya\LaravelSitemap\Providers\SitemapServiceProvider)

## Использование

Добавляем маршрут

```php
Route::get('/sitemaps.xml', [SitemapController::class, 'index']);
```

В контроллере прописываем

```php
 public function index()
    {
        $article = \App\Models\Article::all();

        $sitemapUrsl = new \Ichinya\LaravelSitemap\SitemapUrls();
        // можно указать значения по умолчанию $changefreq = 'weekly', $priority = 0.5
        $sitemapUrsl = new \Ichinya\LaravelSitemap\SitemapUrls('weekly', 0.5);
     
        $sitemapUrsl->addModel($article, 'article.show'); // второй параметр имя роута, с помощью которого генерируютя ссылки
        $sitemapUrsl->addModelClass(\App\Models\Page::class) // имя роута будет page.show
        
        // при добавлении модели можно указать поле с датой следующим параметром.
        $sitemapUrsl->addModelClass(\App\Models\StaticPage::class, 'created_at')
        
        $sitemapUrsl
            ->addUrl(route('main'), time())
            ->addUrl(route('categories.edit', time()) // добавляем ссылки на различные страницы
            ->addUrl('/rules', time(), 'weekly', 0.5); // добавляем ссылки на различные страницы
        // время можно указать в timestamp, через Carbon, объектом DateTime или строкой 


        $sitemap = Sitemap::create($sitemapUrsl); // ядро ссылок собрали, теперь отправляем на создание

        return $sitemap->render();
    }
```

# ToDo
* маршруты создавать в пакете
* создавать несколько файлов
* использование кеш
* автогенерация в определенное время или по команде
