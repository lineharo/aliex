<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductClick;
use Illuminate\Http\Request;

class ProductClickController extends Controller
{
    public function redirect(Request $request, $productAId = null)
    {
        $product = Product::where('ali_id', $productAId)->first();

        $click = new ProductClick();

        if ($product) {
            $click->product_id = $product->id;
        }

        $click->ali_id = $productAId;

        $click->user_ip = getUserIpAddr();
        $click->user_agent = $request->header('User-Agent');

        $click->utm_source   = session('utm_source');
        $click->utm_medium   = session('utm_medium');
        $click->utm_campaign = session('utm_campaign');
        $click->utm_content  = session('utm_content');
        $click->utm_term     = session('utm_term');
        $click->referer      = session('referer');
        $click->erid         = session('erid');
        $click->user_ulid    = session('user_ulid');

        $click->save();

        if ($product) {
            $link = ProductClick::AFF_LINK .
                '&subid=' . $click->utm_source .
                '&subid1=' . $product->ali_id .
                '&subid2=' . session('user_ulid') .
                '&ulp=https%3A%2F%2Faliexpress.ru%2Fitem%2F' . $product->ali_id .
                '.html';
        } elseif ($productAId) {
            $link = ProductClick::AFF_LINK .
                '&subid=' . $click->utm_source .
                '&subid1=' . $productAId .
                '&subid2=' . session('user_ulid') .
                '&ulp=https%3A%2F%2Faliexpress.ru%2Fitem%2F' . $productAId .
                '.html';
        } else {
            $link = ProductClick::AFF_LINK .
            '&subid=' . $click->utm_source .
            '&subid1=home' .
            '&subid2=' . session('user_ulid') .
            '&ulp=https%3A%2F%2Faliexpress.ru%2Fitem%2F.html';
        }

        return redirect($link);

    }
}
