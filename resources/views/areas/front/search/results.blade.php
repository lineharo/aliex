@extends('layouts.front')

@section('content')
<div class="margins">

    <h1>Результаты поиска</h1>

    @if($results->isEmpty())
        <p>Ничего не найдено</p>
    @else
        <div class="search-results">
            @foreach($results as $result)
                @if($result instanceof \App\Models\Article)
                    <div class="search-results__item">
                        <div class="search-results__img">
                            <a href="{{ route('front.article.show', $result->slug) }}">
                                <img class="w-full" src="/{{ $result->image_preview }}" alt="{{ $result->name }}">
                            </a>
                        </div>
                        <div class="search-results__item-info">
                            <div class="search-results__name">
                                <a href="{{ route('front.article.show', $result->slug) }}">
                                    {{ $result->name }}
                                </a>
                            </div>
                            <div class="search-result__description">
                                {{ \Illuminate\Support\Str::limit($result->preview ?? $result->content, 150) }}
                            </div>
                            <a href="{{ route('front.article.show', $result->slug) }}">Читать далее</a>
                        </div>
                    </div>
                @elseif($result instanceof \App\Models\Product)
                    @php
                        $images = $result->getDetailImages();
                    @endphp
                    <div class="search-results__item">
                        <div class="search-results__img">
                            <a href="{{ route('front.product.show', ['slug' => $result->slug]) }}">
                                <img src="/images/{{ $images[0] ?? 'products/blank250.jpg' }}" alt="{{ $result->name }}">
                            </a>
                        </div>
                        <div class="search-results__item-info">
                            <div class="search-results__name">
                                <a href="{{ route('front.product.show', ['slug' => $result->slug]) }}">
                                    {{ $result->name }}
                                </a>
                            </div>
                            <div class="search-result__description">
                                {{ \Illuminate\Support\Str::limit($result->description, 150) }}
                            </div>

                            <a href="{{ route('front.product.show', ['slug' => $result->slug]) }}">Подробнее</a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    {{ $results->links('components.front.paginator') }}

</div>
@endsection