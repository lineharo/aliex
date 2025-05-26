@extends('layouts.admin')

@section('content')

<a href="{{ route('admin.promocodes.create')}}" class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Создать</a>

<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    ID
                </th>
                <th scope="col" class="px-6 py-3">
                    Название
                </th>
                <th scope="col" class="px-6 py-3">
                    Магазин
                </th>
                <th scope="col" class="px-6 py-3">
                    Скидка
                </th>
                <th scope="col" class="px-6 py-3">
                    Даты
                </th>
                <th scope="col" class="px-6 py-3">
                    Код
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promocodes as $promocode)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="text-center">
                        <div>{{ $promocode->id }}</div>
                        <div class="text-xs text-slate-400">{{ $promocode->created_at }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.promocodes.edit', ['promocode' => $promocode]) }}">{{ $promocode->name }}</a>
                    </td>
                    <td class="px-6 py-4">{{ $promocode->store_name }}</td>
                    <td class="px-6 py-4">{{ $promocode->offer_amount }} {{ $promocode->offer_currency }}</td>
                    <td class="px-6 py-4">{{ $promocode->date_from?->format('d.m.y') }} - {{ $promocode->date_to?->format('d.m.y') }} </td>
                    <td class="px-6 py-4">{{ $promocode->code }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $promocodes->links() }}

@endsection