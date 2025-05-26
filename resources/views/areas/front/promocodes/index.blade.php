@extends('layouts.front')

@section('content')

<div class="margins">

    <h1>Купоны и промокоды Aliexpress – экономьте на покупках легко!</h1>

    <div class="promocodes my-10">

        @foreach ($promocodes as $promocode)
            <div class="promocodes__item">
                <div class="promocodes__item-value">
                    <div class="promocodes__item-value-caption">
                        скидка
                    </div>
                    <div class="promocodes__item-value-digit">
                        @if ($promocode->offer_currency == '%')
                            до
                        @endif
                        {{ $promocode->offer_amount }}
                        {{ $promocode->offer_currency }}
                    </div>
                </div>

                <div class="promocodes__item-info">
                    <div class="promocodes__item-name">
                        {{ $promocode->name }}
                    </div>
                    <div class="promocodes__item-dates">
                        @if($promocode->date_to && $promocode->date_from)
                            @if (now() >= $promocode->date_to)
                                истекло {{ $promocode->date_to->format('d.m.Y') }}
                            @elseif (now() <= $promocode->date_from)
                                будет доступно с {{ $promocode->date_from->format('d.m.Y') }}
                            @else
                                истечёт {{ $promocode->date_to->format('d.m.Y') }}
                            @endif
                        @endif
                    </div>
                </div>

                <div class="promocodes__item-action">
                    @if ($promocode->code)
                        <button class="button promocode__open-code">
                            Получить<br>код
                        </button>

                        <div class="promocodes__code">
                            <div class="promocodes__code-value">
                                {{ $promocode->code }}

                                <div class="promocodes__code-copy">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 16.5L19.5 4.5L18.75 3.75H9L8.25 4.5L8.25 7.5L5.25 7.5L4.5 8.25V20.25L5.25 21H15L15.75 20.25V17.25H18.75L19.5 16.5ZM15.75 15.75L15.75 8.25L15 7.5L9.75 7.5V5.25L18 5.25V15.75H15.75ZM6 9L14.25 9L14.25 19.5L6 19.5L6 9Z" fill="#080341"/></svg>
                                </div>
                            </div>
                            <div class="promocodes__code-link">
                                <a class="button" href="{{ route('front.promocodes.away', ['promocode' => $promocode]) }}" target="_blank" rel="nofollow">
                                перейти на сайт
                                </a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('front.promocodes.away', ['promocode' => $promocode]) }}" class="button away-promocode" target="_blank" rel="nofollow">
                            Получить<br>скидку
                        </a>
                    @endif

                </div>

            </div>
        @endforeach

    </div>

    {{ $promocodes->links('components.front.paginator') }}

    @if (is_null($page_number))
        <hr class="my-10">

        <div class="my-10">
            <p>Если вы часто заказываете товары на Aliexpress, то знаете, как приятно получить скидку. С помощью купонов и промокодов можно значительно снизить стоимость покупки, будь то электроника, одежда, аксессуары или товары для дома. На этой странице мы собрали актуальные предложения, которые помогут вам покупать выгоднее.

            <h2>Как работают купоны и промокоды? Все просто:</h2>

            <p>Промокоды – это специальные коды, которые вводятся в корзине при оформлении заказа и дают скидку. Купоны бывают двух видов: от самого Aliexpress и от продавцов. Первые можно использовать для любых покупок, а вторые – только в конкретном магазине. Часто купоны доступны во время крупных распродаж, таких как 11.11, Черная пятница или сезонные акции. Но выгодные предложения появляются и в обычные дни – главное, не упустить момент.</p>

            <h2>Как использовать купон или промокод?</h2>

            <ul class="list-st">
                <li>Выберите подходящее предложение из списка.</li>
                <li>Нажмите на кнопку «Получить код» или «Активировать купон».</li>
                <li>Перейдите на Aliexpress и добавьте товары в корзину.</li>
                <li>Вставьте промокод в специальное поле при оформлении заказа и получите скидку.</li>
            </ul>

            <p>Экономить легко, когда знаешь, где искать лучшие предложения. Следите за обновлениями, чтобы не пропустить новые скидки и выгодные купоны!</p>
        </div>
    @endif

</div>

@endsection
