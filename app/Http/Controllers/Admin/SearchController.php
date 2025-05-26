<?php

namespace App\Http\Controllers\Admin;

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
        $searchLog = SearchLog::orderBy('id', 'desc')->paginate(100);

        return view('areas.admin.search.index', ['searches' => $searchLog]);
    }

}
