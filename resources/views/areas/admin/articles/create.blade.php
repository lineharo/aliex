@extends('layouts.admin')

@section('content')

<h1>Создать статью</h1>

<form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-6 p-2">
    @csrf

    <!-- Название -->
    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Название</label>
        <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}" required>
    </div>

    <!-- Slug -->
    <div class="mb-4">
        <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug (уникальный)</label>
        <input type="text" id="slug" name="slug" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('slug') }}" required>
    </div>

    <!-- SEO Title -->
    <div class="mb-4">
        <label for="seo_title" class="block text-gray-700 text-sm font-bold mb-2">SEO Заголовок</label>
        <input type="text" id="seo_title" name="seo_title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('seo_title') }}">
    </div>

    <!-- SEO Description -->
    <div class="mb-4">
        <label for="seo_description" class="block text-gray-700 text-sm font-bold mb-2">SEO Описание</label>
        <input type="text" id="seo_description" name="seo_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('seo_description') }}">
    </div>

    <!-- SEO Keywords -->
    <div class="mb-4">
        <label for="seo_keywords" class="block text-gray-700 text-sm font-bold mb-2">SEO Ключевые слова</label>
        <input type="text" id="seo_keywords" name="seo_keywords" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('seo_keywords') }}">
    </div>

    <!-- Preview -->
    <div class="mb-4">
        <label for="preview" class="block text-gray-700 text-sm font-bold mb-2">Превью</label>
        <textarea id="preview" name="preview" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('preview') }}</textarea>
    </div>

    <!-- Content -->
    <div class="mb-4">
        <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Контент</label>
        <textarea id="content" name="content" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="10">{{ old('content')}}</textarea>
    </div>

    <!-- Published At -->
    <div class="mb-4">
        <label for="published_at" class="block text-gray-700 text-sm font-bold mb-2">Дата публикации</label>
        <input type="datetime-local" id="published_at" name="published_at" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}">
    </div>

    <!-- Views -->
    <div class="mb-4">
        <label for="views" class="block text-gray-700 text-sm font-bold mb-2">Просмотры</label>
        <input type="number" id="views" name="views" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('views', 0) }}" min="0">
    </div>

    <!-- Published -->
    <div class="mb-4">
        <label for="published" class="block text-gray-700 text-sm font-bold mb-2">Опубликовано</label>
        <input type="checkbox" id="published" name="published" class="shadow appearance-none border rounded text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="1" {{ old('published') ? 'checked' : '' }}>
    </div>

    <!-- Image Preview -->
    <div class="mb-4">
        <label for="image_preview" class="block text-gray-700 text-sm font-bold mb-2">Превью изображения</label>
        <input type="file" id="image_preview" name="image_preview" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>

    <!-- Кнопка отправки -->
    <div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Создать статью</button>
    </div>
</form>
@endsection
