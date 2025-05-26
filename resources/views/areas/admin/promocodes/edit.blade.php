@extends('layouts.admin')

@section('content')

<form action="{{ route('admin.promocodes.update', ['promocode' => $promocode]) }}" method="POST">
    @csrf

    <div class="mb-5">
        <label for="code" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Код</label>
        <input name="code" type="text" id="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $promocode->code }}" />
    </div>

    <div class="mb-5">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Название</label>
        <input name="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $promocode->name }}" required />
    </div>

    <div class="mb-5">
        <label for="offer_amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Сумма скидки</label>
        <input name="offer_amount" type="number" id="offer_amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $promocode->offer_amount }}" required />
    </div>

    <div class="mb-5">
        <label for="offer_currency" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ед. изм. скидки</label>
        <input name="offer_currency" type="text" id="offer_currency" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $promocode->offer_currency }}" required />
    </div>

    <div class="mb-5">
        <label for="store_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Название магазина</label>
        <input name="store_name" type="text" id="store_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $promocode->store_name }}" required />
    </div>

    <div class="mb-5">
        <label for="date_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Начало действия</label>
        <input name="date_from" type="date" id="date_from" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $promocode->date_from->format('Y-m-d') }}" required />
    </div>

    <div class="mb-5">
        <label for="date_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Конец действия</label>
        <input name="date_to" type="date" id="date_to" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $promocode->date_to->format('Y-m-d') }}" required />
    </div>

    <div class="mb-5">
        <label for="url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ссылка</label>
        <input name="url" type="text" id="url" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="{{ $promocode->url }}" required />
    </div>

    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Обновить</button>

</form>

@endsection