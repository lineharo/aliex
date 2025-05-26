<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function show(Request $request, $slug)
    {
        $product = Product::where('ulid', $slug)
            ->orWhere(function ($query) use ($slug) {
                $query->where('published', 1)->where('slug', $slug);
        })->firstOrFail();

        if ($product->ulid === $slug) {
            return redirect()->route('front.product.show', ['slug' => $product->slug]);
        }

        Product::withoutTimestamps(fn () => $product->increment('shows'));

        $maxId = Product::where('published', 1)
        ->where('alicat_id', $product->alicat_id)
        ->max('id');

        $similarProducts = Product::where('published', 1)
            ->where('alicat_id', $product->alicat_id)
            ->where('id', '!=', $product->id)
            ->where('id', '>=', rand(1, $maxId))
            ->limit(4)
            ->get();

        return view('areas.front.product.show', [
            'seo_title' => $product->name,
            'seo_description' => 'Закажите со скидкой '. $product->name . ', рейтинг ' . $product->rating . ', продаж ' . $product->sales . ', цена ' . $product->price / 100 . ' ₽',
            'seo_keywords' => '',
            'product' => $product,
            'similarProducts' => $similarProducts,
        ]);
    }

    public function category(Request $request, $category)
    {

        $cat = collect(config('__.aliCatIds'))->where('id', $category)->first();

        $products = Product::where([
            ['published', 1],
            ['alicat_id', $category]
        ])
        ->orderBy('updated_at', 'desc')
        ->paginate(12);

        return view('areas.front.category.show', [
            'seo_title' => 'Товары со скидками на Aliexpress категории ' . $cat['name'],
            'seo_description' => 'Исследуйте подборку разнообразных товаров в категории ' . $cat['name'],
            'seo_keywords' => 'AliExpress, скидки, акции, выгодные покупки, товары с AliExpress, ' . $cat['name'],
            'products' => $products,
            'category' => $cat,
        ]);
    }

    public function getThumbImage(Request $request, $width, $height, $publicPath)
    {
        $filePathInfo = pathinfo(Storage::disk('images')->path($publicPath));

        $filePath = $filePathInfo['dirname'] . '/' . Product::IMAGES_THUMB_DIR;
        $fileName = $filePathInfo['filename'];
        $fileExt = $filePathInfo['extension'];

        if (!\File::exists($filePath)) {
            \File::makeDirectory($filePath);
        }

        $fileThumb = $fileName . '__' . $width . 'x' . $height . '.' . $fileExt;

        if (!\File::exists($filePath . $fileThumb)) {
            $imagick = new \Imagick(Storage::disk('images')->path($publicPath));
            $imagick->scaleimage($width, $height);
            $imagick->writeImage($filePath . $fileThumb);
        }

        return response()->file($filePath . $fileThumb, ['Content-Type' => 'image/jpeg']);
    }
}
