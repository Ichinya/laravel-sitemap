# Генератор для sitemap для Laravel

## Установка

`composer require ichinya/laravel-sitemap`

## Использование

### Генератор

Добавляем в расписание выполнение кода `\Ichinya\LaravelSitemap\Sitemap::generate()->writeToFile();`, в методе можно указать другое имя файла

При выполнении кода будет создан файл `sitemap.xml` в корне сайта, который будет доступен по ссылке http://site.ru/sitemap.xml

Чтобы произошла магия, нужно просто прописать у нужных моделей интерфейс `Ichinya\LaravelSitemap\Sitemapable;`

То есть должно получиться модель вида:

```php 
<?php

namespace App\Models;

use Ichinya\LaravelSitemap\Sitemapable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements Sitemapable
{
    use HasFactory;
}
```

При этом будут в данном случае использоваться маршруты `posts.index` и `posts.show` для их замены стандарных нужно прописать:

```php
// маршрут для списка, в примере posts.index
public ?string $sitemap_model_route_list = null;
// маршрут для каждой позиции в примере это posts.show
public ?string $sitemap_model_route_item = null;
```

Или добавить методы:
```php 
    public function getSitemapModelRouteItem(): string
    {
        return $this->sitemap_model_route_item;
    }
    
    public function getSitemapModelRouteList(): string
    {
        return $this->sitemap_model_route_list;
    }
```

Отметка времени будет браться из поля `updated_at`, соответственно оно меняется:
```php
    // можно указанть другое поле с отметкой времени, например published_at. Если поле данное поле у позиции будет null, то она не попадет в карту. Данное поведение можно поменять, смотрите ниже 
    public string $sitemap_datetime = 'updated_at';
    
    // можно использовать метод для использования любой логики
    public function getSitemapDatetime(): string
    {
        return $this->sitemap_datetime;
    }
```

Получения списка позиций:
```php
    // тут можно написать просто запрос, какие позиции попадут в карту
    public function getSitemapItems()
    {
        return self::whereNotNull($this->getSitemapDatetime())->get();
    }
```

### Стандартное использование 

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
* создавать несколько файлов
* использования конфига
* автогенерация в определенное время или по команде
