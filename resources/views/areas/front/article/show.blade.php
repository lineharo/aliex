@extends('layouts.front')

@section('content')

<div class="margins my-8">
    <div class="article">
        <div class="article__date">
            {{ $article->published_at->format('d/m/Y') }}
        </div>

        <div class="article__content">
            {!! Blade::render($article->content) !!}
        </div>

    </div>
</div>

@endsection
