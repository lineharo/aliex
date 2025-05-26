@extends('layouts.admin')

@section('content')

<form method="POST" action="{{ route('admin.products.update', $product->id) }}" class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    @csrf

    <div class="mb-4">
        <label for="ali_id" class="block text-sm font-medium text-gray-700">Ali ID</label>
        <input type="text" id="ali_id" name="ali_id" value="{{ old('ali_id', $product->ali_id) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="alicat_id" class="block text-sm font-medium text-gray-700">Alicat ID</label>
        <input type="number" id="alicat_id" name="alicat_id" value="{{ old('alicat_id', $product->alicat_id) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="ulid" class="block text-sm font-medium text-gray-700">ULID</label>
        <input type="text" id="ulid" name="ulid" value="{{ old('ulid', $product->ulid) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="mb-4">
        <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
        <input type="text" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="store_name" class="block text-sm font-medium text-gray-700">Store Name</label>
        <input type="text" id="store_name" name="store_name" value="{{ old('store_name', $product->store_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="store_url" class="block text-sm font-medium text-gray-700">Store URL</label>
        <input type="url" id="store_url" name="store_url" value="{{ old('store_url', $product->store_url) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="price_old" class="block text-sm font-medium text-gray-700">Old Price</label>
        <input type="number" id="price_old" name="price_old" value="{{ old('price_old', $product->price_old) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
        <input type="text" id="rating" name="rating" value="{{ old('rating', $product->rating) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="sales" class="block text-sm font-medium text-gray-700">Sales</label>
        <input type="number" id="sales" name="sales" value="{{ old('sales', $product->sales) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="shows" class="block text-sm font-medium text-gray-700">Shows</label>
        <input type="number" id="shows" name="shows" value="{{ old('shows', $product->shows) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mb-4">
        <label for="published" class="block text-sm font-medium text-gray-700">Published</label>
        <input type="number" id="published" name="published" value="{{ old('published', $product->published) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-zinc-500 focus:border-zinc-500 sm:text-sm">
    </div>

    <div class="mt-6">
        <button type="submit" class="px-4 py-2 bg-zinc-600 text-black font-medium rounded-md shadow-sm hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-500">Сохранить</button>
    </div>
</form>



@endsection