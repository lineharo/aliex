<?php

namespace App\Traits;

trait UrlMapTrait
{
    // Для sitemap
    public static function getUrlMap()
    {
        $className = strtolower((new \ReflectionClass(static::class))->getShortName());
        $urlMap = [];


        (new static)::select('slug', 'updated_at')
            ->where('published', 1)
            ->orderByDesc('updated_at')
            ->chunk(100, function ($elements) use (&$urlMap, $className) {
                foreach ($elements as $element) {
                    $urlMap[] = [
                        'loc' => route("front.$className.show", ['slug' => $element->slug]),
                        'lastmod' => optional($element->updated_at)->toAtomString(),
                    ];
                }
            });

        return $urlMap;
    }

}
