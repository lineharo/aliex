<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        @vite(['resources/css/admin.sass'])

    </head>

    <body class="font-sans">


        <div class="md:flex">
            <aside class="w-full md:h-screen md:w-64 bg-gray-900 md:flex md:flex-col">
                <header class="border-b border-solid border-gray-800 flex-grow">
                    <h1 class="py-6 px-4 text-gray-100 text-base font-medium">AliEx</h1>
                </header>

                <nav class="overflow-y-auto h-full flex-grow">

                    <a href="{{ route('front.home') }}" class="mx-6 text-gray-100 text-base font-medium" target="_blank">сайт</a>

                    <header>
                        <span class="text-xs text-gray-500 block py-6 px-6">MENU</span>
                    </header>

                    <ul class="font-medium px-4 text-left">
                        <li class="text-gray-100">
                            <a href="{{ route('admin.promocodes.index')}}" class="rounded text-sm text-left block py-3 px-6 hover:bg-blue-600 w-full">Промокоды</a>
                        </li>
                    </ul>

                    <ul class="font-medium px-4 text-left">
                        <li class="text-gray-100">
                            <a href="{{ route('admin.products.index')}}" class="rounded text-sm text-left block py-3 px-6 hover:bg-blue-600 w-full">Товары</a>
                        </li>
                    </ul>

                    <ul class="font-medium px-4 text-left">
                        <li class="text-gray-100">
                            <a href="{{ route('admin.articles.index')}}" class="rounded text-sm text-left block py-3 px-6 hover:bg-blue-600 w-full">Статьи</a>
                        </li>
                    </ul>

                    <ul class="font-medium px-4 text-left">
                        <li class="text-gray-100">

                            <a href="{{ route('admin.vk_auth') }}" class="rounded text-sm text-left block py-3 px-6 hover:bg-blue-600 w-full">VK auth</a>

                            <a href="{{ route('admin.sber_auth') }}" class="rounded text-sm text-left block py-3 px-6 hover:bg-blue-600 w-full">Sber auth</a>

                            <a href="{{ route('admin.clicks') }}" class="rounded text-sm text-left block py-3 px-6 hover:bg-blue-600 w-full">Clicks</a>

                            <a href="{{ route('admin.search.index') }}" class="rounded text-sm text-left block py-3 px-6 hover:bg-blue-600 w-full">Поиск</a>

                        </li>
                    </ul>
                </nav>

            </aside>

            <main class="bg-gray-100 h-screen w-full overflow-y-auto">
                <section>
                    <header class="border-b border-solid border-gray-300 bg-white">
                        <h2 class="p-6">Performance</h2>
                    </header>

                    <section class="m-4 bg-white border border-gray-300 border-solid rounded shadow">

                        @yield('content')

                    </section>
                </section>
            </main>
        </div>

    </body>
</html>
