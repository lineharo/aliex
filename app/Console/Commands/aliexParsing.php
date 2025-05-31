<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Modules\TG;
use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class aliexParsing extends Command
{
    protected $signature = 'aliex:parsing';
    protected $description = 'Парсинг товаров с сайта';

    const MAX_ATTEMPS = 5;

    public function handle()
    {
        // Получить ID категории и ID товара из списка в случайной категории
        [$categoryId, $aliProductId] = $this->getAliProductId();

        usleep(1_000_000);
        // Получить HTML товара и извлечь параметры и ID необходимых выджетов
        [$props, $uuids] = $this->getAliProductProps($aliProductId);

        // Id для получения виджетов
        $colProps = collect($props);
        $SPCW = $colProps->where('name', 'SnowProductContextWidget')->first();

        $activeSkuId = $SPCW['props']['skuInfo']['priceList'][0]['skuId'] ?? null;

        usleep(1_000_000);

        // Получить виджеты
        $widgets = $this->getAliProductWidgets($aliProductId, $activeSkuId, $uuids);


        $product = new Product();
        $product->ali_id = $aliProductId;

        $product->alicat_id = $categoryId;

        $res = $this->parseProductInfo($product, $props, $widgets);

        if (!$res) {
            die('parse error');
        }

        $imgGallery = $product->imgGallery;
        unset($product->imgGallery);
        $product->save();


        $product->imagesPath = date('y/m'). '/' . $product->ulid . '/';

        $product->downloadImages($imgGallery, $product->imagesPath);
        $images = $product->prepareImages();
        $images['sources'] = $imgGallery;
        $product->images = json_encode($images);
        $product->save();

        $this->sendTg($product, $images['spec']['collage']);
        Storage::drive('images')->delete($images['spec']['collage']);
        $images['spec']['collage'] = null;


        $product->images = json_encode($images);
        $product->save();

    }


    private function getAliProductId()
    {
        $attempts = 0;
        $productId = null;

        $aliCatIds = config('__.aliCatIds');
        $aliCatCount = count($aliCatIds);

        do {
            if ($attempts > 0) {
                usleep(1_500_000);
            }
            $attempts++;

            $categoryId = $aliCatIds[rand(1, $aliCatCount - 1)]['id'];
            $page = rand(1, 20);

            $aliProducts = $this->getAliProducts($categoryId, $page);

            if (!is_null($aliProducts)) {

                foreach ($aliProducts as $aliProduct) {
                    $aliP = isset($aliProduct->product) ? $aliProduct->product : $aliProduct->snippet;

                    if (Product::where('ali_id', $aliP->id)->exists()) {
                        continue;
                    }

                    $productId = $aliP->id;
                    break;
                }

            }
        } while (is_null($productId) && $attempts <= self::MAX_ATTEMPS);

        return [$categoryId, $productId];
    }

    private function getAliProductProps($id)
    {
        $url = 'https://aliexpress.ru/item/' . $id . '.html';

        $html = $this->sendAliApi($url, 'get', []);

        $dom = new DOMDocument();

        // Отключаем ошибки загрузки, так как HTML может быть невалидным
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $scriptTag = $xpath->query('//script[@id="__AER_DATA__" and @type="application/json"]');

        $res = '';
        if ($scriptTag->length > 0) {
            $res = $scriptTag->item(0)->textContent;
        }

        $decodedData = json_decode($res, true);

        /*
        $searchTerms = [
            "SnowProductContent" => "uuid",
            "RedReviewsProductFeedbackList" => "uuid",
            "HazeProductCharacteristics" => "props",
            "SnowStoreContextWidget" => "props",
            "SnowProductContextWidget" => "props",
        ];
        */
        $searchTerms = [
            "SnowProductContent"           => "uuid",
            "SnowProductContextWidget"     => "props",
            "SnowStoreContextWidget"       => "props",
            "HazeProductDescription"       => "props",
            "HazeProductCharacteristics"   => "props",
        ];

        if ($decodedData) {
            $props = [];
            $uuids = [];

            $this->findValuesByWidgetId($decodedData, $searchTerms, $props, $uuids);
        } else {
            return [null, null];
        }

        return [$props, $uuids];
    }

    private function parseProduct()
    {
        $attempts = 0;
        $product = null;

        $aliCatIds = config('__.aliCatIds');
        $aliCatCount = count($aliCatIds);

        do {
            if ($attempts > 0) {
                usleep(1_500_000);
            }
            $attempts++;

            $categoryId = $aliCatIds[rand(1, $aliCatCount - 1)]['id'];
            $page = rand(1, 20);

            $aliProducts = $this->getAliProducts($categoryId, $page);

            if (!is_null($aliProducts)) {

                foreach ($aliProducts as $aliProduct) {
                    $aliP = $aliProduct->snippet;

                    if (Product::where('ali_id', $aliP->id)->exists()) {
                        continue;
                    }

                    // "text": "453 ₽",
                    // Проверка и извлечение fullPrice
                    $aliP->fullPrice = isset($aliP->secondaryPrice->element[0]->text)
                        ? preg_replace('/[^\d.,]/', '', $aliP->secondaryPrice->element[0]->text)
                        : null;
                    $aliP->fullPrice = $aliP->fullPrice !== null ? str_replace(',', '.', $aliP->fullPrice) : null;

                    // Проверка и извлечение finalPrice
                    $aliP->finalPrice = isset($aliP->finalPrice->element[0]->text)
                        ? preg_replace('/[^\d.,]/', '', $aliP->finalPrice->element[0]->text)
                        : null;
                    $aliP->finalPrice = $aliP->finalPrice !== null ? str_replace(',', '.', $aliP->finalPrice) : null;


                    if (is_null($aliP->fullPrice) || is_null($aliP->finalPrice) || $aliP->fullPrice == $aliP->finalPrice) {
                        continue;
                    }

                    $aliP->categoryId = $categoryId;
                    $product = $aliP;

                    break;
                }

            }
        } while (is_null($product) && $attempts <= self::MAX_ATTEMPS);

        return $product;
    }

    private function getAliProducts($categoryId, $page)
    {
        $productParams = [
            'catId' => strval($categoryId),
            'g' => 'y',
            'storeIds' => [],
            'pgChildren' => [],
            'aeBrainIds' => [],
            'source' => 'nav_category',
            'page' => $page,
            'searchInfo' => '',
            "brandValueIds" => '',
            "pvid" => '',
        ];

        $url = 'https://aliexpress.ru/aer-jsonapi/v1/category_filter?_bx-v=2.5.21';

        $response = $this->sendAliApi($url, 'post', $productParams);

        $data = json_decode($response);

        if (!isset($data->data->productsFeed->productsV2)) return null;

        return collect($data->data->productsFeed->productsV2);
    }

    // Отправить в телеграм
    private function sendTg($product, $image)
    {
        $link = 'https://ali-ex.ru/rt/' . $product->ali_id;

        $text = $product->name . PHP_EOL . PHP_EOL;
        $text .= '➡️ Подробнее:' . PHP_EOL;
        $text .= '<a href="' . route('front.product.show', ['slug' => $product->slug]) . '">ALI-EX.RU</a>' . PHP_EOL . PHP_EOL;
        $text .= '🏷️ Цена: ' . round($product->price / 100) . ' ₽' . PHP_EOL;
        $text .= '🔹 Старая цена: <s>' . round($product->price_old / 100) . ' ₽' . '</s>' . PHP_EOL . PHP_EOL;

        if ($product->sales > 5) {
            $text .= '⭐ Рейтинг: ' . $product->rating . PHP_EOL;
            $text .= '🔹Продаж: ' . $product->sales . PHP_EOL . PHP_EOL . PHP_EOL;
        }

        $tg = new TG();
        $tg->sendPhotoPost('@aliexweb', $text, Storage::disk('images')->path($image), $link);
    }


    private function sendAliApi($url, $method, $params, $addHeader = [])
    {
        $cookies = json_decode(file_get_contents(storage_path('/modules/parser/cookies.ckc')));

        $res = '';
        foreach ($cookies as $cookie) {
            $res .= $cookie->name . '=' . $cookie->value . '; ';
        }
        $cookies = $res;

        $headers = [
            'Connection'      => 'keep-alive',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept'          => '*/*',
            'Accept-Language' => 'ru-RU,ru;q=0.9,en-RU;q=0.8,en;q=0.7,en-US;q=0.6',
            'Content-Type'    => 'application/json',
            'User-Agent'      => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
            'Cookie'          => $cookies,
        ];

        $headers = array_merge($headers, $addHeader);

        $options = [
            'allow_redirects' => [
                'max'             => 3,
                'strict'          => true,
                'referer'         => true,
                'protocols'       => ['http', 'https'],
                'track_redirects' => true
            ],
            'timeout' => 60,
        ];

        if ($method == "post") {
            $response = Http::withHeaders($headers)
                ->withOptions($options)
                ->post($url, $params);
        } elseif ($method == "get") {
            $response = Http::withHeaders($headers)
                ->withOptions($options)
                ->get($url);
        }

        return $response->body();
    }

    // Функция рекурсивно проходит по JSON и ищет совпадения в widgetId
    function findValuesByWidgetId($data, $searchTerms, &$props = [], &$uuids = [])
    {
        //dd(file_put_contents(storage_path('a.json'), json_encode($data)));
        if (is_array($data)) {
            if (isset($data['widgetId'])) {
                foreach ($searchTerms as $term => $type) {

                    //$wName = isset($data['widgetId']) ? $data['widgetId'] : $data['name'] ?? null;
                    $wName = $data['widgetId'] ?? null;


                    if (strpos($wName, $term) !== false) {
                        /*
                        if ($type === 'uuid' && isset($data['uuid'])) {
                            $uuids[] = ['widgetId' => $wName, 'uuid' => $data['uuid']];
                        } elseif ($type === 'props' && isset($data['props'])) {
                            $props[] = ['widgetId' => $wName, 'name' => $term, 'props' => $data['props']];
                        }
                        */
                        if ($type === 'props' && isset($data['props'])) {
                            $props[] = ['widgetId' => $wName, 'name' => $term, 'props' => $data['props']];
                        } elseif ($type === 'uuid' && isset($data['uuid'])) {
                            $uuids[] = ['widgetId' => $wName, 'name' => $term, 'uuid' => $data['uuid']];
                        }
                    }
                }
            }
            foreach ($data as $key => $value) {
                $this->findValuesByWidgetId($value, $searchTerms, $uuids, $props);
            }
        }
    }

    function getAliProductWidgets($productId, $activeSkuId, $uuids)
    {
        $uuids = array_column($uuids, 'uuid');
        $uuidQuery = implode('&uuid=', $uuids);
        $url = "https://aliexpress.ru/widget?uuid=" . $uuidQuery . "&_bx-v=2.5.28";

        $addHeader['aer-url'] = 'https://aliexpress.ru/item/' . $productId . '.html?sku_id=' . $activeSkuId;

        $response = $this->sendAliApi($url, 'get', [], $addHeader);

        $res = json_decode($response, true)['widgets'];
        return $res;
    }

    function parseProductInfo($product, $props, $widgets)
    {
        $product->ulid = Str::ulid();

        $data = collect(array_merge($props, $widgets));
        //file_put_contents(storage_path($product->ali_id . '.json'), json_encode($data));
        //$data = collect(json_decode(file_get_contents(storage_path('a.json'))));


        $content = $data->where('name', 'SnowProductContent')->first()['state']['data']['html'] ?? null;
        $product->ali_description = strip_tags($content);


        $storeProps = $data->where('name', 'SnowStoreContextWidget')->first()['props'] ?? null;
        $storeStats = collect($storeProps['stats'] ?? null);
        $storeRating = $storeProps ? $storeStats->where('type', 1)->first()['value'] ?? null : null;

        $product->store_name = $storeProps['name'] ?? null;
        $product->store_url = $storeProps['url'] ?? null;
        $product->store_chat_url = $storeProps['chatLink'] ?? null;
        $product->store_rating = $storeRating;
        $product->store_image = $storeProps['image'] ?? null;

        $productProps = $data->where('name', 'SnowProductContextWidget')->first()['props'] ?? null;

        $product->name = $productProps['name'] ?? null;
        $product->ali_description = $productProps['description'] ?? null;

        if ($product->name == null) {
            return false;
        }

        $product->ali_properties = json_encode(collect($productProps['skuInfo']['propertyList'] ?? [])->map(function ($property) {
            return [
            'name' => $property['name'],
            'values' => collect($property['values'])->map(function ($value) {
                return [
                'name' => $value['name'],
                'colorValue' => $value['colorValue'] ?? null,
                ];
            }),
            ];
        }));

        $product->imgGallery = collect($productProps['gallery'] ?? [])->map(function ($image) {
            return $image['imageUrl'];
        });

        $product->price = $productProps['price']['minActivityAmount']['value'] ?? null;
        $product->price_old = $productProps['price']['maxAmount']['value'] ?? null;
        $product->rating = $productProps['rating']['middle'] ?? null;
        $product->reviews = $productProps['reviews'] ?? null;

        // Здесь внутренняя категория. Нужную можно получить из breadcrumbs. Сейчас беру из общей карточки товара.
        //$product->alicat_id = $productProps->productInfo->category->categoryId ?? null;

        $product->sales = $productProps['tradeInfo']['tradeCount'] ?? null;



        $prodChars = $data->where('name', 'HazeProductCharacteristics')->first()['props']['groups'] ?? null;
        $product->ali_chars = json_encode(collect($prodChars)->flatMap(function ($group) {
            return collect($group['properties'])->map(function ($attr) {
            return [
                'name' => $attr['name'],
                'value' => $attr['value'],
            ];
            });
        }));



        $prodReviws = $data->where('name','RedReviewsProductFeedbackList')->first()['state']['data']['reviews'] ?? null;
        $product->ali_reviews = json_encode(collect($prodReviws)->map(function ($review) {
            return [
                'author' => $review['reviewer']['name'],
                'date' => $review['root']['date'],
                'content' => $review['root']['text'] ?? $review['root']['originalText'] ?? null,
            ];
        }));


        $product->price     = $product->price * 100;
        $product->price_old = $product->price_old * 100;
        $product->published = 1;

        $product->slug      = lhTranslit($product->name, 60) . '-' . $product->ali_id;

        return true;
    }
}
