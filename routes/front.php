<?php

use App\Http\Controllers\Front\ArticleController;
use App\Http\Controllers\Front\SearchController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductClickController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromocodeController;
use Illuminate\Support\Facades\Route;

Route::name('front.')
    ->group(function() {

        Route::get('/', [FrontController::class, 'home'])->name('home');

        Route::prefix('/category')
            ->name('categories.')
            ->group(function()
            {
                //Route::get('/', [ProductController::class, 'categories'])->name('categories');
                Route::get('/{category}', [ProductController::class, 'category'])->name('show');
            });

        Route::prefix('/product')
            ->name('product.')
            ->group(function()
            {
                Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
            });

        Route::prefix('/promocodes')
            ->name('promocodes.')
            ->group(function()
            {
                Route::get('/', [PromocodeController::class, 'index'])->name('index');
                Route::get('/away/{promocode}', [PromocodeController::class, 'away'])->name('away');
            });

        Route::prefix('/article')
            ->name('article.')
            ->controller(ArticleController::class)
            ->group(function()
            {
                Route::get('/', 'index')->name('index');
                Route::get('/{slug}', 'show')->name('show');
            });

        Route::prefix('/search')
            ->name('search.')
            ->group(function()
            {
                Route::get('/', [SearchController::class, 'index'])->name('index');
            });

        Route::name('away.')
            ->group(function() {
                Route::get('/away/{product}', [FrontController::class, 'away'])->name('site');

                Route::get('/r/{product}', [FrontController::class, 'link_old'])->name('link_old');
                Route::get('/rv/{product}', [FrontController::class, 'link_vk'])->name('link_vk');
                Route::get('/rt/{product}', [FrontController::class, 'link_tg'])->name('link_tg');

                Route::get('/go/{productAId}', [ProductClickController::class, 'redirect'])->name('redirect');
            });

    });
