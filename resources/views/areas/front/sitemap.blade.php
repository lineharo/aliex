@php echo '<?xml version="1.0" encoding="UTF-8"?>' @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($urls as $item)
        <url>
            <loc>{{ $item['loc'] }}</loc>
            @if(!empty($item['lastmod']))
                <lastmod>{{ $item['lastmod'] }}</lastmod>
            @endif
        </url>
    @endforeach
</urlset>