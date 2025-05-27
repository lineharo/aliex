@extends('layouts.admin')

@section('content')

<a href="{{ route('admin.products.dup_titles')}}" class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Дубликаты «Name»</a>

<form method="GET" action="{{ route('admin.products.index') }}" class="mb-4">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Поиск по имени"
        class="px-4 py-2 border rounded-md w-64 dark:bg-gray-800 dark:text-white" />
    <button type="submit"
        class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ml-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        Найти
    </button>
</form>


<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Название и описание
                </th>
                <th scope="col" class="px-6 py-3">
                    Изо
                </th>
                <th scope="col" class="px-6 py-3">
                    Данные
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="text-center">
                        <a class="font-bold" href="{{ route('admin.products.edit', ['product' => $product]) }}">{{ $product->id }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="mb-1 text-xs text-slate-400">
                            {{ $product->created_at }} — {{ $product->ulid }} — <a style="color: #f87" target="_blank" href="{{ route('front.product.show', ['slug' => $product->slug]) }}">На сайте</a> — <a style="color: #f87" target="_blank" href="https://aliexpress.ru/item/{{ $product->ali_id }}.html">На али</a>
                        </div>
                        {{ $product->name }}</a>
                        <div class="mt-4 text-xs" style="max-height: 200px; overflow-y: scroll">
                            {{ $product->description }}
                        </div>
                    </td>
                    <td width="700">
                        <div class="flex flex-wrap items-center gap-1">
                            @isset(json_decode($product->images, true)['common'])
                                @foreach (json_decode($product->images, true)['common'] as $image)
                                    <a href="/images/{{$image}}" target='_blank' style="border:1px solid #FF0000">
                                        <img width="50" src="/images/{{$image}}" alt="">
                                    </a>
                                @endforeach
                            @endisset

                            @isset(json_decode($product->images, true)['web']['preview'])
                                @foreach (json_decode($product->images, true)['web']['preview'] as $image)
                                    <a href="/images/{{$image}}" target='_blank' style="border:1px solid #0000ff">
                                        <img width="50" src="/images/{{$image}}" alt="">
                                    </a>
                                @endforeach
                            @endisset

                            <hr>

                            @isset(json_decode($product->images, true)['web']['detail'])
                                @foreach (json_decode($product->images, true)['web']['detail'] as $image)
                                    <a href="/images/{{$image}}" target='_blank' style="border:1px solid #00ff00">
                                        <img width="50" src="/images/{{$image}}" alt="" >
                                    </a>
                                @endforeach
                            @endisset

                        </div>
                    </td>
                    <td width="200">
                        {{ number_format($product->price / 100, 0, ',', ' ') }} ₽<br>
                        <span style="text-decoration: line-through;">{{ number_format($product->price_old / 100, 0, ',', ' ') }}</span> ₽<br>
                        {{ $product->rating }} : x{{ $product->sales }}<br>
                        {{ $product->shows }} просмотров

                        @if($product->posted_vk)
                            <div>
                                <a href="https://vk.com/wall-218217182_{{ $product->posted_vk }}">Перейти в ВК<a>
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.products.postVk') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <button type="submit" class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Отправить в VK</button>
                            </form>
                        @endif

                        @if($product->isExtraImages())
                            <form method="POST" action="{{ route('admin.products.removeImages') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <button type="submit" class="inline-block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-1 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">Удалить лишние изо</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $products->appends(['q' => request('q')])->links('components.admin.paginator') }}

@endsection