<?php

namespace App\Traits;

trait UrlMapTrait
{
    // Для sitemap
    public static function getUrlMap()
    {
        $className = strtolower((new \ReflectionClass(static::class))->getShortName());

        $elements = (new static)::where('published', 1)
            ->orderByDesc('updated_at')
            ->get();

        $urlMap = [];

        foreach ($elements as $element) {
            $urlMap[] = [
                'loc' => route("front.$className.show", ['slug' => $element->slug]),
                'lastmod' => optional($element->updated_at)->toAtomString(), // RFC 3339 формат
            ];
        }

        return $urlMap;
    }
}