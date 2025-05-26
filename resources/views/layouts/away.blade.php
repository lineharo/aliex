<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $seo_title }} :: {{ config('app.name', 'Aliex') }}</title>
        <meta name="description" content="{{ $seo_description }}">
        <meta name="keywords" content="{{ $seo_keywords}}">

        @isset($seo_canonical)
            <link rel="canonical" href="{{ $seo_canonical }}" />
        @endif

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#00aba9">
        <meta name="theme-color" content="#ffffff">

        @vite(['resources/css/front.sass'])

    </head>
    <body>
        @yield('content')

        @vite(['resources/js/app.js'])
    </body>
</html>
