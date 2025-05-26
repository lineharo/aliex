@extends('layouts.front')

@section('content')
    <x-breadcrumbs :showHome="true" :items="[
        [
            'name' => $product->category()['name'],
            'url' => route('front.categories.show', ['category' => $product->category()['id']])
        ],
        [
            'name' => $product->name,
            'url' => false,
        ],
    ]" />

    <div class="margins">

        <div class="product" itemscope itemtype="https://schema.org/Product">

            <h1 class="product__name" itemprop="name">{{ $product->name }}</h1>

            <div class="product__common">
                <div class="product__gallery">
                    <div class="product__slider-wrap">
                        <div class="product__slider">
                            @php
                                $images = $product->getDetailImages();
                            @endphp
                            @if ($images)
                                @foreach ($product->getDetailImages() as $image)
                                    <div class="product__slide">
                                        <img itemprop="image" src="/images/{{ $image }}" alt="{{ $product->name }}">
                                        <div class="product__slide-overlay"></div>
                                    </div>
                                @endforeach
                            @else
                                <div class="product__slide">
                                    <img itemprop="image" src="/images/products/blank400.jpg" alt="Нет изображений">
                                    <div class="product__slide-overlay"></div>
                                </div>
                            @endif
                            <div class="product__slide">
                                <div class="product__slide-more">
                                    <a href="{{ route('front.away.site', ['product' => $product->ali_id]) }}" class="button button_inline" rel="nofollow">Смотреть на aliexpress</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product__gallery-controls">
                        <div class="product__gallery-control product__gallery-control_left">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 12H20M4 12L8 8M4 12L8 16" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                        <div class="product__gallery-control product__gallery-control_right">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 12H20M20 12L16 8M20 12L16 16" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                </div>
                <div class="product__info">

                    @if($product->store_name)
                        <div class="product__store">

                            <div class="product-store__top">
                                <div class="product__store-image">
                                    <img src="/images/products/store-70.png" alt="{{ $product->store_name }}" width="70" height="70">
                                </div>
                                <div class="product__store-info">
                                    <div class="product__store-name">
                                        {{ $product->store_name }}
                                    </div>
                                    @if($product->store_rating )
                                        <div class="product__store-rating">
                                            Рейтинг: {{ $product->store_rating }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">
                    @endif

                    <div class="product__rating" itemprop="aggregateRating"
                    itemscope itemtype="https://schema.org/AggregateRating">
                        <svg width="20" height="19" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.4569 0.326903C4.67067 -0.108968 5.32933 -0.108968 5.5431 0.326903L6.56829 2.4173C6.65541 2.59495 6.83441 2.71726 7.04097 2.74029L9.47161 3.01134C9.97842 3.06786 10.182 3.65702 9.80726 3.98292L8.01022 5.5459C7.85751 5.67873 7.78914 5.87663 7.82968 6.06851L8.3067 8.32643C8.40616 8.79723 7.87329 9.16135 7.42795 8.9269L5.29213 7.80247C5.11063 7.70692 4.88937 7.70692 4.70787 7.80247L2.57205 8.9269C2.12671 9.16135 1.59383 8.79723 1.6933 8.32643L2.17032 6.06851C2.21086 5.87663 2.14249 5.67873 1.98978 5.5459L0.192742 3.98292C-0.181958 3.65702 0.02158 3.06786 0.528394 3.01134L2.95903 2.74029C3.16559 2.71726 3.34459 2.59495 3.43171 2.4173L4.4569 0.326903Z" fill="#F8211C"/></svg>

                        <div>
                            <span itemprop="ratingValue">{{ $product->rating ?? 1 }}</span>
                            • Отзывов:
                            <span itemprop="ratingCount">{{ $product->reviews ?? 1 }}</span>
                            • Продаж:
                            <span class="product__sales">
                                @if($product->sales >= 10000)
                                    {{ number_format($product->sales, 0, ',', ' ') }}
                                @elseif($product->sales > 0)
                                    {{ $product->sales }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="product__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">

                        <div class="product__price-final">
                            @if($product->price >= 10000)
                                <span itemprop="price" content="{{$product->price/100}}">{{ number_format($product->price / 100, 0, ',', ' ') }}</span> <span itemprop="priceCurrency" content="RUB">₽</span>
                            @elseif($product->price > 0)
                                <span itemprop="price" content="{{$product->price/100}}">{{ $product->price / 100 }}</span> <span itemprop="priceCurrency" content="RUB">₽</span>
                            @endif
                        </div>

                        <div class="product__price-full">
                            @if($product->price_old >= 1000000)
                                {{ number_format($product->price_old / 100, 0, ',', ' ') }} <span content="RUB">₽</span>
                            @elseif($product->price_old > 0)
                                {{ $product->price_old / 100 }} <span content="RUB">₽</span>
                            @endif
                        </div>

                        <div class="product__price-discount">
                            @if($product->getDiscount() > 0)
                            -{{ $product->getDiscount() }}%
                            @endif
                        </div>
                    </div>

                    <div class="product__caution">
                        @if ($product->price == 0 || $product->status == config('__.statuses.discontinued'))
                            <meta itemprop="availability" content="https://schema.org/OutOfStock">
                            Товар временно снят с продажи
                        @else
                            <meta itemprop="availability" content="https://schema.org/InStock">
                        @endif
                    </div>

                    <div class="product__button">
                        <a href="{{ route('front.away.site', ['product' => $product->ali_id]) }}" class="button button_inline" rel="nofollow">Купить на aliexpress</a>
                    </div>

                </div>
            </div>

            <div class="product_tabs mt-10">
                <x-front.tabs :tabs="['tab1' => 'Описание', 'tab2' => 'Опции', 'tab3' => 'Характеристики', 'tab4' => 'Отзывы']">
                    <x-slot name="slot_tab1">
                        <div class="product__description" itemprop="description">
                            {!! $product->description !!}
                        </div>
                    </x-slot>
                    <x-slot name="slot_tab2">
                        <div class="table">
                            <table class="product__table">
                                <tbody>
                                    @if($product->ali_properties)
                                        @foreach (json_decode($product->ali_properties) as $property)
                                            <tr>
                                                <td>{{ $property->name }}</td>
                                                <td>
                                                @foreach ($property->values as $value)
                                                    {{ $value->name }}<br>
                                                @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>--</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </x-slot>
                    <x-slot name="slot_tab3">
                        <div class="table">
                            <table class="product__table">
                                <tbody>
                                    @if ($product->ali_chars)
                                        @foreach (json_decode($product->ali_chars) as $char)
                                            <tr>
                                                <td>{{ $char->name }}</td>
                                                <td>{{ $char->value }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>--</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </x-slot>
                    <x-slot name="slot_tab4">
                        <div class="product__reviews">
                            @php
                                $reviews = collect(json_decode($product->ali_reviews));
                            @endphp
                            @if (count($reviews) > 0)
                                @foreach ($reviews as $review)

                                <div class="product__review" itemprop="review" itemscope itemtype="https://schema.org/Review">
                                    <div itemprop="author" itemtype="https://schema.org/Person" itemscope>
                                        <span itemprop="name">
                                            {{ $review->author }}
                                        </span>
                                    </div>
                                    <div class="product__review-text" itemprop="reviewBody">{{ $review->content }}</div>
                                </div>

                                @endforeach
                            @else
                                <div class="product__review">
                                    <div class="product__review-text">--</div>
                                </div>
                            @endif
                        </div>
                    </x-slot>
                </x-tabs>
            </div>

        </div>
    </div>

    <hr>

    <div class="margins my-5">
        <h2>Похожие товары</h2>
        <x-front.product-list :products="$similarProducts" />
    </div>

@endsection
