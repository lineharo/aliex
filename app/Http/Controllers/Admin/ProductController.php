<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Modules\VK;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::orderBy('updated_at', 'desc');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $products = $query->paginate(12);

        return view('areas.admin.products.index', [
            'products' => $products,
        ]);
    }

    public function edit(Product $product, Request $request)
    {
        return view('areas.admin.products.edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $product->fill($request->all());
        $product->save();
        return redirect()->back();
    }

    public function dup_titles()
    {
        $products = Product::select(
            'name',
            DB::raw('COUNT(*) as occurrences'),
            DB::raw('GROUP_CONCAT(id ORDER BY id ASC) as ids'),
        )
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->orderBy('occurrences', 'desc')
            ->get();

        $a = implode(', ', $products->pluck('ids')->all());
        preg_match_all('~[\d]+~', $a, $res);

        $products = Product::select('*')
            ->whereIn('id', $res[0])
            ->orderByRaw("FIELD(id, $a)")
            ->paginate(20);

        return view('areas.admin.products.index', [
            'products' => $products,
        ]);
    }

    public function postVk(Request $request)
    {
        $product = Product::where([
            'id' => $request->id,
            'posted_vk' => null,
        ])->first();

        if (!$product) return redirect()->back();

        $scheduleFile = json_decode(file_get_contents(storage_path('modules/vk/settings.json')));
        $lastTime = Carbon::createFromTimestamp($scheduleFile->lastPost);
        $now = now();

        if (floor($lastTime->diffInHours($now)) >= 1) {
            $when = $now->addHour(1)->timestamp;
        } else {
            $when = $lastTime->addHour(1)->timestamp;
        }

        file_put_contents(storage_path('modules/vk/settings.json'), json_encode([
            'lastPost' => $when,
        ]));

        $vk = new VK();
        $res = $vk->postVk($product, $when);

        if (isset($res->response->post_id)) {
            $product->posted_vk = $res->response->post_id;
            $product->removeImages();
            $product->timestamps = false;
            $product->save();
        } else {
            dump(strlen($product->description));
            dd($res);
        }

        return redirect()->back();
    }

    public function removeImages(Request $request)
    {
        $product = Product::where('id', $request->id)->first();

        $product->removeImages();
        return redirect()->back();
    }
}
