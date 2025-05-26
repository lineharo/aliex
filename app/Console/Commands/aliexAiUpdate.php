<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Modules\Sber;
use App\Modules\Gemini;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class aliexAiUpdate extends Command
{
    protected $signature = 'aliex:ai-update';
    protected $description = 'Контент для товара от ИИ ';

    public function handle()
    {
        // $sber = new Sber();
        // if (!$sber->checkAuth()) {
        //     $sber->getToken();
        //     usleep(15_000_000);
        // }

        // if (!$sber->checkAuth()) {
        //     return null;
        // }

        $gemini = new Gemini();


        // Добавить Описание
        $product = Product::whereNull('description')->whereNot('alicat_id', 16002)->first();

        //$product = Product::where('id', 4835)->first();
        if ($product) {
            $pProps = null;
            $pProps = $pChars = $pReviews = $pDescription = '';

            $prompt = 'Название товара: "' . htmlspecialchars($product->name) . '".';

            if ($product->ali_description) {
                $prompt .= ' Описание от продавца: "' . htmlspecialchars($product->ali_description) . '".';
            }
            if ($product->ali_properties) {
                $prompt .= ' Опции: "' . htmlspecialchars($product->ali_properties) . '".';
            }
            if ($product->ali_chars) {
                $prompt .= ' Характеристики: "' . htmlspecialchars($product->ali_chars) . '".';
            }
            if ($product->ali_reviews) {
                $prompt .= ' Отзывы покупателей: "' . htmlspecialchars($product->ali_reviews) . '".';
            }

            $prompt .= ' Напиши полное SEO-описание для страницы товара, основываясь на этих данных.';

            $description = $gemini->makeDescription($prompt);

            if ($description) {
                $product->description = $description;
            }

            $product->timestamps = false;
            $product->save();
        }

        // Если отсутствует name у товара
        $productWithoutName = Product::whereNull('name')->orWhere('name', '')->first();

        if ($productWithoutName) {
            $generatedName = $gemini->makeTitle('Сгенерируй название для товара: "' .
                htmlspecialchars($productWithoutName->ali_description ?? '') .
                ', slug:' . htmlspecialchars($productWithoutName->slug ?? '') .
            '". Одно готовое не очень длинное название одной строкой.');

            if ($generatedName) {
                $productWithoutName->name = $generatedName;
                $productWithoutName->timestamps = false;
                $productWithoutName->save();
            }
        }

        // Переписать дублирующиеся name. Только для одного товара
        $products = Product::select(
            'name',
            DB::raw('COUNT(*) as occurrences'),
            DB::raw('GROUP_CONCAT(id ORDER BY id ASC) as ids'),
        )
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->orderBy('occurrences', 'desc')
            ->get();

        if (count($products->pluck('ids')->all()) > 0) {

            $a = implode(', ', $products->pluck('ids')->all());
            preg_match_all('~[\d]+~', $a, $res);

            $product = Product::select('*')
                ->whereIn('id', $res[0])
                ->orderByRaw("FIELD(id, $a)")
                ->first();

            if ($product) {
                $name = $gemini->makeTitle('Перепиши название товара: ' . htmlspecialchars($product->name) . '. Укажи название без кавычек, средней длины');

                if ($name) {
                    $product->name = $name;
                    $product->timestamps = false;
                    $product->save();
                }
            }
        }

    }
}