<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('front.home');
        }

        // Поиск в статьях
        $articles = Article::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('preview', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->orWhere('seo_title', 'LIKE', "%{$query}%")
            ->orWhere('seo_description', 'LIKE', "%{$query}%")
            ->orWhere('seo_keywords', 'LIKE', "%{$query}%")
            ->get();

        // Поиск в товарах
        $products = Product::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhere('ali_description', 'LIKE', "%{$query}%")
            ->orWhere('ali_properties', 'LIKE', "%{$query}%")
            ->orWhere('ali_chars', 'LIKE', "%{$query}%")
            ->get();

        // Объединение результатов
        $results = $articles->concat($products);

        // Ручная пагинация
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentResults = $results->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedResults = new LengthAwarePaginator(
            $currentResults,
            $results->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        SearchLog::create([
            'ip'             => getUserIpAddr(),
            'user_agent'     => $request->header('User-Agent'),
            'query'          => $query,
            'results_count'  => $results->count(),
            'referer'        => session('referer'),
        ]);

        return view('areas.front.search.results', [
            'results' => $paginatedResults,
            'seo_title' => 'Результаты поиска по запросу "' . $query . '"',
            'seo_description' => 'Результаты поиска по запросу "' . $query .'". Найдите статьи, товары и другую информацию, соответствующую вашим интересам.',
            'seo_keywords' => '',
            'page_number' => $currentPage,
        ]);
    }
}
