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

            <h3>{{ $product->name }}</h3>

        </div>
    </div>
@else
    ...
@endif