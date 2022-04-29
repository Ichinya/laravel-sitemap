<?php

namespace Ichinya\LaravelSitemap;

class Sitemap
{

    private function __construct(private SitemapUrls $sitemapUrls)
    {
    }

    public static function create($sitemapUrls): Sitemap
    {
        return new Sitemap($sitemapUrls);
    }

    public static function generate(): Sitemap
    {
        $urls = new \Ichinya\LaravelSitemap\SitemapUrls();
        $dir = app_path('Models');
        $classes = \File::allFiles($dir);
        foreach ($classes as $class) {
            $classname = str_replace([app_path(), '/', '.php'], ['App', '\\', ''], $class->getRealPath());
            if (is_a($classname, Sitemapable::class, true)) {
                $urls->addModelClass($classname, 'updated_at');
            }
        }

        return new self($urls);
    }

    public function render($xml = true)
    {
        if ($xml) {
            header("Content-Type:text/xml");
        }
        $list = $this->sitemapUrls->getList();
        $listGroup = $this->sitemapUrls->getListGroup();
        if (!empty($listGroup)) {
            $listGroup['other'] = $list;
            return view('ichi-sitemap::sitemapIndex.index', ['sitemaps' => $listGroup]);
        }
        return view('ichi-sitemap::sitemap', ['urls' => $list]);
    }

    public function writeToFile(string $path = 'sitemap.xml'): void
    {
        ob_start();
        echo $this->render(false);
        $xml = ob_get_contents();
        ob_end_clean();
        file_put_contents($path, $xml);
    }

}
