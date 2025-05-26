<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromocodeController;
use Illuminate\Support\Facades\Route;


// Admin CP
Route::middleware(['auth', 'verified'])
    ->name('admin.')
    ->prefix('/admincp')
    ->group(function()
    {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/vk_auth', [AdminController::class, 'vk_auth'])->name('vk_auth');
        Route::get('/sber_auth', [AdminController::class, 'sber_auth'])->name('sber_auth');
        Route::get('/clicks', [AdminController::class, 'clicks'])->name('clicks');

        Route::prefix('/promocodes')
            ->name('promocodes.')
            ->group(function()
            {
                Route::get('/', [PromocodeController::class, 'indexAdmin'])->name('index');
                Route::get('/creade', [PromocodeController::class, 'create'])->name('create');
                Route::post('/store', [PromocodeController::class, 'store'])->name('store');
                Route::get('/edit/{promocode}', [PromocodeController::class, 'edit'])->name('edit');
                Route::post('/update/{promocode}', [PromocodeController::class, 'update'])->name('update');
            });

        Route::prefix('/products')
            ->name('products.')
            ->group(function()
            {
                Route::get('/', [ProductController::class, 'index'])->name('index');
                Route::get('/edit/{product}', [ProductController::class, 'edit'])->name('edit');
                Route::post('/update/{product}', [ProductController::class, 'update'])->name('update');
                Route::get('/dup_titles', [ProductController::class, 'dup_titles'])->name('dup_titles');

                Route::post('/postVk', [ProductController::class, 'postVk'])->name('postVk');
                Route::post('/removeImages', [ProductController::class, 'removeImages'])->name('removeImages');
            });

        Route::prefix('/articles')
            ->name('articles.')
            ->controller(ArticleController::class)
            ->group(function()
            {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{article}', 'edit')->name('edit');
                Route::post('/update/{article}', 'update')->name('update');
            });

        Route::prefix('/search')
            ->name('search.')
            ->controller(SearchController::class)
            ->group(function()
            {
                Route::get('/', 'index')->name('index');
            });

    });
