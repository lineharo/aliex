<?php

namespace App\Modules;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AliParser
{

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

        $responseCode = $response->status();

        return [
            $response->body(),
            $responseCode
        ];
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

        [$responseBody, $code] = $this->sendAliApi($url, 'post', $productParams);

        $data = json_decode($responseBody);

        if (!isset($data->data->productsFeed->productsV2)) return null;

        return collect($data->data->productsFeed->productsV2);
    }

    public function getAliProductId()
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
                    $aliP = $aliProduct->snippet;

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


    public function getProductPage($id)
    {
        $url = 'https://aliexpress.ru/item/' . $id . '.html';

        [$html, $code] = $this->sendAliApi($url, 'get', []);

        if ($code == 404) {
            return [
                'error' => '404',
                'html' => null,
            ];
        }

        return [
            'html' => $html
        ];
    }


    public function getAliProductProps($html)
    {
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
        } else {
            return [null, null];
        }

        $decodedData = json_decode($res, true);

        $searchTerms = [
            "SnowProductContent" => "uuid",
            "RedReviewsProductFeedbackList" => "uuid",
            "HazeProductCharacteristics" => "props",
            "SnowStoreContextWidget" => "props",
            "SnowProductContextWidget" => "props",
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


    function getAliProductWidgets($productId, $uuids)
    {
        $uuids = array_column($uuids, 'uuid');
        $uuidQuery = implode('&uuid=', $uuids);
        $url = "https://aliexpress.ru/widget?uuid=" . $uuidQuery . "&_bx-v=2.5.28";

        $addHeader['aer-url'] = 'https://aliexpress.ru/item/' . $productId . '.html';

        [$responseBody, $code] = $this->sendAliApi($url, 'get', [], $addHeader);

        $res = json_decode($responseBody, true)['widgets'];
        return $res;
    }


    // Функция рекурсивно проходит по JSON и ищет совпадения в widgetId
    public function findValuesByWidgetId($data, $searchTerms, &$props = [], &$uuids = []) {
        if (is_array($data)) {
            if (isset($data['widgetId'])) {
                foreach ($searchTerms as $term => $type) {

                    $wName = isset($data['widgetId']) ? $data['widgetId'] : $data['name'] ?? null;

                    if (strpos($wName, $term) !== false) {
                        if ($type === 'uuid' && isset($data['uuid'])) {
                            $uuids[] = ['widgetId' => $wName, 'uuid' => $data['uuid']];
                        } elseif ($type === 'props' && isset($data['props'])) {
                            $props[] = ['widgetId' => $wName, 'name' => $term, 'props' => $data['props']];
                        }
                    }
                }
            }
            foreach ($data as $key => $value) {
                $this->findValuesByWidgetId($value, $searchTerms, $uuids, $props);
            }
        }
    }

    function parseProductInfo($product, $props, $widgets)
    {
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
            $content = $review['root']['text'] ?? $review['root']['originalText'] ?? '';
            $content = Str::limit($content, 5000);
            return [
                'author' => $review['reviewer']['name'],
                'date' => $review['root']['date'],
                'content' => $content,
            ];
        }));


        $product->price     = $product->price * 100;
        $product->price_old = $product->price_old * 100;
        $product->published = 1;

        if (!$product->slug) {
            $product->slug      = lhTranslit($product->name, 60) . '-' . $product->ali_id;
        }

        return true;
    }

}