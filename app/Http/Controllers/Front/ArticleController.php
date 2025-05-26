<?php

namespace App\Http\Controllers\Front;

use App\Models\Article;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::where('published_at', '<=', now())
            ->latest()
            ->paginate(21);

        return view('areas.front.article.index', [
            'articles' => $articles,
            'seo_title' => 'Лучшие товары со скидками с AliExpress — Обзоры, советы и тренды',
            'seo_description' => 'Откройте для себя топовые товары со скидками на AliExpress! Читайте полезные обзоры, лайфхаки по выгодным покупкам и узнайте о новинках со скидками. Экономьте больше с нами!',
            'seo_keywords' => 'товары со скидками AliExpress, лучшие скидки AliExpress, выгодные покупки AliExpress, обзоры товаров AliExpress, скидки на Алиэкспресс, лайфхаки для покупок AliExpress, новинки со скидками AliExpress',
            ]);
    }

    public function show(String $slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        return view('areas.front.article.show', [
            'article' => $article,
            'seo_title' => $article->seo_title,
            'seo_description' => $article->seo_description,
            'seo_keywords' => $article->seo_keywords,
        ]);
    }

}
