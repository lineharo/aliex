<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            if (isset($page_number) && !is_null($page_number)) {
                $pageTitle = ' , страница ' . $page_number;
                $pageDescription = ' | Страница ' . $page_number;
            } else {
                $pageTitle = null;
                $pageDescription = null;
            }
        @endphp

        <title>{{ $seo_title ?? '' }}{{ $pageTitle }} :: {{ config('app.name', 'Aliex') }}</title>

        <meta name="description" content="{{ $seo_description ?? ''}}{{ $pageDescription }}">
        <meta name="keywords" content="{{ $seo_keywords ?? ''}}">

        @isset($seo_canonical)
            <link rel="canonical" href="{{ $seo_canonical }}" >
        @else
            <link rel="canonical" href="{{ url()->current() }}" >
        @endisset

        @isset($seo_robots)
            <meta name="robots" content="{{ $seo_robots }}">
        @endisset

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#00aba9">
        <meta name="theme-color" content="#ffffff">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">

        @vite(['resources/css/front.sass'])

        @unless(config('app.debug'))
            <!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date(); for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }} k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(95477317, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/95477317" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
        @endunless

    </head>
    <body>

        <x-front.header />
        <x-front.search />

        <div class="my-4">
            @yield('content')
        </div>

        <x-front.footer />

        @vite(['resources/js/app.js'])
    </body>
</html>
