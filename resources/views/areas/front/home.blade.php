@extends('layouts.front')

@section('content')

    <div class="margins">

        <h1>Скидки на товары AliExpress</h1>

        <div class="categories-list__wrap">
            <div class="categories-list my-10">
                <div class="categories-list__inner my-2">
                    @foreach ($categories as $cat_id => $cat_name)
                        <a href="{{ route('front.categories.show', ['category' => $cat_id]) }}" class="categories-list__item">
                            {{ $cat_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <x-front.product-list :products="$products" />

        {{ $products->links('components.front.paginator') }}

    </div>

    @if (is_null($page_number))
        <div class="margins mt-5">

            <div class="image_full text-center">
                <img src="/images/content/home/ali-ex-splash.webp" alt="Скидки и промокоды на Ali-ex.ru" loading="lazy" width="800" height="457">
            </div>

            <h2>Ваш гид по лучшим скидкам на Aliexpress</h2>

            <p>На нашем сайте вы найдете самую свежую информацию о скидках на товары с Aliexpress. Мы тщательно отбираем для вас лучшие предложения, чтобы вы могли экономить на покупках.</p>

            <h2>Что мы предлагаем?</h2>

            <ul class="list-st">
                <li>Актуальные скидки: Следите за нашими обновлениями, чтобы не пропустить самые горячие скидки на Aliexpress.</li>
                <li><a href="/promocodes">Промокоды</a>: Получайте эксклюзивные промокоды, которые помогут вам сэкономить еще больше.</li>
                <li><a href="/article">Полезные статьи</a>: Читайте наши статьи с советами и рекомендациями, как делать покупки на Aliexpress максимально выгодно и безопасно.</li>
            </ul>

            <h2>Присоединяйтесь к нашему Телеграм-каналу</h2>

            <p>Хотите быть в курсе всех новинок и специальных предложений? Присоединяйтесь к нашей группе в Телеграме, где мы ежедневно выкладываем лучшие скидки и промокоды.</p>

            <div class="w-auto">
                <a href="https://t.me/aliexweb" class="soc-button w-fit" target="_blank" rel="nofollow">
                    <div class="soc-button__caption">
                        Мы в Telegram
                    </div>
                    <div class="soc-button__icon">
                        <svg width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.78736 9.47086C8.76667 6.36615 13.4206 4.31934 15.7493 3.33042C22.3979 0.506852 23.7795 0.0163687 24.68 0.000172686C24.878 -0.00338946 25.3208 0.0467251 25.6077 0.284372C25.8499 0.485037 25.9165 0.756106 25.9484 0.946358C25.9803 1.13661 26.02 1.57001 25.9884 1.90866C25.6281 5.7739 24.0692 15.1538 23.276 19.483C22.9404 21.3148 22.2796 21.929 21.6399 21.9891C20.2496 22.1197 19.1938 21.051 17.8473 20.1497C15.7401 18.7394 14.5498 17.8615 12.5044 16.4853C10.1407 14.8949 11.673 14.0208 13.0201 12.5922C13.3726 12.2184 19.4983 6.52943 19.6169 6.01335C19.6317 5.94881 19.6455 5.70822 19.5055 5.58118C19.3655 5.45414 19.1589 5.49758 19.0098 5.53213C18.7985 5.5811 15.4323 7.85279 8.91132 12.3472C7.95585 13.0171 7.09041 13.3435 6.31501 13.3264C5.46019 13.3075 3.81586 12.8329 2.59347 12.4272C1.09416 11.9296 -0.0974649 11.6665 0.00629874 10.8214C0.0603452 10.3812 0.654034 9.93102 1.78736 9.47086Z" fill="#6C6C6C"/></svg>
                    </div>
                </a>
            </div>
        </div>
    @endif

@endsection
