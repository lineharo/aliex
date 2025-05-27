@if($product)
    <div class="product-h">
        <div class="product-h__image">
            <div class="product-h__image-slider">
                @php
                    $images = $product->getDetailImages();
                @endphp
                @if ($images)
                    @foreach ($product->getPreviewImages() as $image)
                        <div class="product-h__image-slide">
                            <a href="{{ route('front.away.site', ['product' => $product->ali_id]) }}">
                                <img src="/images/{{ $image }}" loading="lazy" alt="{{ $product->name }}">
                                <div class="product-h__image-overlay"></div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="product-h__image-slide">
                        <img itemprop="image" src="/images/products/blank250.jpg" alt="Нет изображений">
                        <div class="product-h__image-overlay"></div>
                    </div>
                @endif
            </div>
        </div>
        <div class="product-h__info">
            <div class="product-h__store-name">{{ $product->store_name }}</div>
            <div class="product-h__name">
                <a href="{{ route('front.product.show', ['slug' => $product->slug]) }}" target="_blank">
                    {{ mb_strlen($product->name) > 70 ? mb_substr($product->name, 0, 67).'...' : $product->name }}
                </a>
            </div>
            <div class="product-h__popularity">
                <div class="product-h__rating">
                    @if ($product->rating > 0)
                        <svg width="10" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.4569 0.326903C4.67067 -0.108968 5.32933 -0.108968 5.5431 0.326903L6.56829 2.4173C6.65541 2.59495 6.83441 2.71726 7.04097 2.74029L9.47161 3.01134C9.97842 3.06786 10.182 3.65702 9.80726 3.98292L8.01022 5.5459C7.85751 5.67873 7.78914 5.87663 7.82968 6.06851L8.3067 8.32643C8.40616 8.79723 7.87329 9.16135 7.42795 8.9269L5.29213 7.80247C5.11063 7.70692 4.88937 7.70692 4.70787 7.80247L2.57205 8.9269C2.12671 9.16135 1.59383 8.79723 1.6933 8.32643L2.17032 6.06851C2.21086 5.87663 2.14249 5.67873 1.98978 5.5459L0.192742 3.98292C-0.181958 3.65702 0.02158 3.06786 0.528394 3.01134L2.95903 2.74029C3.16559 2.71726 3.34459 2.59495 3.43171 2.4173L4.4569 0.326903Z" fill="#F8211C"/></svg>
                        {{ $product->rating }}
                    @endif
                </div>
                <div class="product-h__sales">
                    Продаж: {{ $product->sales >= 10000 ? number_format($product->sales, 0, ',', ' ') : $product->sales }}
                </div>
            </div>
            <div class="product-h__price">
                <div class="product-h__price-final">
                    @if($product->price >= 1000000)
                        {{ number_format($product->price / 100, 0, ',', ' ') }} ₽
                    @elseif($product->price > 0)
                        {{ $product->price / 100 }} ₽
                    @else
                        —
                    @endif
                </div>
                <div class="product-h__price-full">
                    @if($product->price_old >= 1000000)
                        {{ number_format($product->price_old / 100, 0, ',', ' ') }} ₽
                    @elseif($product->price_old > 0)
                        {{ $product->price_old / 100}} ₽
                    @endif
                </div>
                <div class="product-h__price-discount">
                    @if($product->getDiscount() > 0)
                        -{{ $product->getDiscount() }}%
                    @endif
                </div>
            </div>
            <div class="product-h__button">
                <a href="{{ route('front.away.site', ['product' => $product->ali_id]) }}" class="button">КУПИТЬ В ALIEXPRESS</a>
            </div>
        </div>
    </div>
@endif
