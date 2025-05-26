@extends('layouts.admin')

@section('content')

<div class="mb-4">
    <a href="{{ route('admin.articles.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded m-2">Создать статью</a>
</div>

<div class="px-2">
@foreach ($articles as $article)
    <article class="bg-white border rounded-lg p-6 mb-2">
        <div class="mb-4">
            <div class="text-sm">
                <span title="Опубликовано">{{ $article->published ? 'Y' : 'X' }}</span> |
                {{ $article->created_at }} |
                {{ $article->updated_at }} |
                {{ $article->published_at ?? '--' }} |
                <span>Просмотры: {{ $article->views }}</span>
            </div>

            <h2 class="text-xl font-bold">
                <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-blue-500 hover:underline">{{ $article->name }}</a>
            </h2>
        </div>
        <div>
            <img width="100" src="/{{ $article->image_preview }}" alt="{{ $article->name }}" class="mb-4">
            <p>{{ $article->preview }}</p>
        </div>
    </article>
@endforeach
</div>

{{ $articles->links('components.admin.paginator') }}

@endsection