<?php

namespace App\Modules;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class VK
{
    const VK_URL = 'https://api.vk.com/method/';

    private function send($method, $params = [])
    {
        $user = USER::find(1);

        $stParams = [
            'v' => '5.131',
            'access_token' => $user->vk_token,
        ];

        $params = array_merge($params, $stParams);

        $response = Http::asForm()->get(self::VK_URL . $method, $params);
        $response = json_decode($response->body());

        return $response;
    }

    public function wallPost($text)
    {
        $data = [
            'owner_id' => '-206436661',
            'message' => $text,
        ];

        $this->send('wall.post', $data);
    }

    public function imagePost($group_id, $files, $text, $when)
    {

        if (is_string($files)) $files = [$files];
        if (!is_array($files)) return null;

        $attachments = [];
        foreach ($files as $file) {

            // Получить адрес сервера для загрузки фото на стену
            $attemp = 0;
            $params = ['group_id' => $group_id];

            do {
                $attemp++;
                if ($attemp > 1) usleep(500_000);
                $result = $this->send('photos.getWallUploadServer', $params);

            } while (!isset($result->response->upload_url) && $attemp < 4);

            $uploadServer = $result->response->upload_url;

            // Загрузить фотографию на сервер
            $cfile = new \CURLFile(Storage::Disk('images')->path($file), 'image/jpeg','photo.jpg');
            $ch = curl_init($uploadServer);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['photo' => $cfile]);
            $json = json_decode(curl_exec($ch));

            curl_close($ch);

            if (!isset($json->photo)) continue;

            // Загрузить фото на стену
            $params = array(
                'group_id' => $group_id,
                'photo' => $json->photo,
                'server' => $json->server,
                'hash'  => $json->hash,
            );

            $result = $this->send('photos.saveWallPhoto', $params);

            if (isset($result->response[0]->id))
                $attachments[] = [
                    'id' => $result->response[0]->id,
                    'ownerId' => $result->response[0]->owner_id,
                ];
        }

        $aRes = '';
        foreach ($attachments as $a)
        {
            $aRes .= 'photo' . $a['ownerId'] . '_' . $a['id'] . ',';
        }

        // Создать запись на стене
        $params = [
            'owner_id' => '-' . $group_id,
            'from_group' => '1',
            'attachments' => rtrim($aRes, ","),
            'message' => $text,
            'publish_date' => $when,
        ];

        $result = $this->send('wall.post', $params);
        return $result;
    }

    public function postVk(Product $product, $when)
    {
        $text = $product->name . PHP_EOL . PHP_EOL;

        $text .= '➡️ Заказать: https://ali-ex.ru/rv/' . PHP_EOL . PHP_EOL;

        $text = '🔵 Мы в Telegram: https://t.me/aliexweb' . PHP_EOL . PHP_EOL ;

        $text .= $product->ali_id . PHP_EOL . PHP_EOL;
        $text .= '🏷️ Цена: ' . round($product->price / 100) . ' ₽' . PHP_EOL;
        $text .= '🔹 Старая цена: ' . implode('&#822;', mb_str_split(round($product->price_old / 100))) . ' ₽' . '&#822;' . PHP_EOL . PHP_EOL;
        if ($product->sales > 5) {
            $text .= '⭐ Рейтинг: ' . $product->rating . PHP_EOL;
            $text .= '🔹Продаж: ' . $product->sales . PHP_EOL . PHP_EOL ;
        }

        $text .= '➡️ Подробнее: ' . route('front.product.show', ['slug' => $product->slug]);

        // Удаляем HTML теги и обрезаем текст
        $cleanText = strip_tags($product->description);
        if (strlen($cleanText) > 1000) {
            $cleanText = substr($cleanText, 0, 1000);
            $cleanText = substr($cleanText, 0, strrpos($cleanText, ' '));
        }

        // Добавляем к итоговому тексту с двумя переносами строки
        $text .= $cleanText . PHP_EOL . PHP_EOL;

        $tags = collect(config('__.poster.hashtags'))->shuffle()->slice(0, rand(5, 10))->implode(' #');
        $text .= '#aliexpress #алиэкспресс #' . $tags ;

        $vk = new VK();
        $result = $vk->imagePost('218217182', $product->getSocFilesImages(), $text, $when);

        return $result;
    }

}
