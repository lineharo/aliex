<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Response;

class FrontController extends Controller
{
    public function home(Request $request)
    {
        $products = Product::where([
                ['published', 1],
                ['alicat_id', '!=', '16002']
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        $categories = Product::select('alicat_id')->distinct()->get()->pluck('alicat_id');

        $catRes = [];
        foreach ($categories as $category) {
            foreach (config('__.aliCatIds') as $aliCat) {
                if ($category == $aliCat['id']) {
                    $catRes[$aliCat['id']] = $aliCat['name'];
                }
            }
        }

        $page_number = $request['page'];

        $page_number = $request['page'];
        $seo_robots = null;
        $seo_canonical = null;
        if ($page_number !== null) {
            $seo_robots = 'noindex, follow';
            $seo_canonical = route('front.home');
        }

        return view('areas.front.home', [
            'seo_title' => 'Скидки AliExpress: найдите лучшие предложения на нашем сайте',
            'seo_description' => 'Покупайте на AliExpress по выгодным ценам. Наши скидки и акции помогут вам сэкономить. Подборки товаров по актуальным предложениям.',
            'seo_keywords' => 'AliExpress, скидки, акции, выгодные покупки, агрегатор скидок, товары с AliExpress, экономия, купоны AliExpress',
            'products' => $products,
            'categories' => $catRes,
            'page_number' => $page_number,
            'seo_robots' => $seo_robots,
            'seo_canonical' => $seo_canonical,
        ]);
    }

    public function away(Request $request, $productAId)
    {
        return $this->showAway($productAId);
    }

    public function link_old(Request $request, $productAId)
    {
        return $this->showAway($productAId);
    }

    public function link_tg(Request $request, $productAId)
    {
        return $this->showAway($productAId);
    }

    public function link_vk(Request $request, $productAId)
    {
        return $this->showAway($productAId);
    }

    private function showAway($productAId)
    {
        return view('areas.front.away', [
            'seo_title' => 'Переход на страницу товара',
            'seo_description' => '',
            'seo_keywords' => '',
            'productAId' => $productAId,
        ]);
    }

    public function sitemap() {
        \Debugbar::disable();

        $urls = [
            [
                'loc' => route('front.home'),
            ],
            [
                'loc' => route('front.promocodes.index'),
            ]
        ];

        $urls = array_merge(
            $urls,
            Article::getUrlMap(),
            Product::getUrlMap(),
        );

        $response = Response::view('areas.front.sitemap', [
            'urls' => $urls,
        ]);
        $response->header('Content-Type', 'application/xml');
        return $response;
    }

}